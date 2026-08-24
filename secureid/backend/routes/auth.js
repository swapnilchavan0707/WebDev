
const express = require('express');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const { v4: uuid } = require('uuid');
const { createChallenge, verifyChallenge, challenges } = require('../utils/otp');
const { JWT_SECRET } = require('../middleware/auth');

const router = express.Router();

// In-memory users - replace with DB in production
const users = new Map(); // email -> user

router.post('/register', async (req,res)=>{
  const { fullName, email, mobile, password } = req.body;
  if(!fullName || !email || !password) return res.status(400).json({error:'Missing required fields'});
  if(users.has(email)) return res.status(409).json({error:'User already exists'});
  // Password strength check
  const strong = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/;
  if(!strong.test(password)) return res.status(400).json({error:'Password must contain 8 chars, uppercase, number, special char'});
  
  const passwordHash = await bcrypt.hash(password, 10);
  const user = { id: uuid(), fullName, email, mobile, passwordHash, emailVerified:false, mobileVerified:false, mfaEnabled:false, createdAt: Date.now() };
  users.set(email, user);
  const { challengeId, expiresAt } = createChallenge(user.id, 'email');
  res.json({ message:'User created, OTP sent', challengeId, expiresAt, next:'email-otp', userId:user.id });
});

router.post('/send-email-otp', (req,res)=>{
  const { email, userId } = req.body;
  const user = users.get(email) || [...users.values()].find(u=>u.id===userId);
  if(!user) return res.status(404).json({error:'User not found'});
  const { challengeId, expiresAt } = createChallenge(user.id, 'email');
  res.json({ challengeId, expiresAt, channel:'email' });
});

router.post('/verify-email-otp', (req,res)=>{
  const { challengeId, otp } = req.body;
  const result = verifyChallenge(challengeId, otp);
  if(!result.valid) return res.status(400).json(result);
  const user = [...users.values()].find(u=>u.id===result.challenge.userId);
  if(user) user.emailVerified = true;
  // auto create next challenge for SMS
  const next = createChallenge(result.challenge.userId, 'sms');
  res.json({ verified:true, next:'sms-otp', nextChallengeId: next.challengeId, expiresAt: next.expiresAt });
});

router.post('/send-sms-otp', (req,res)=>{
  const { email, userId } = req.body;
  const user = users.get(email) || [...users.values()].find(u=>u.id===userId);
  if(!user) return res.status(404).json({error:'User not found'});
  const { challengeId, expiresAt } = createChallenge(user.id, 'sms');
  res.json({ challengeId, expiresAt, channel:'sms' });
});

router.post('/verify-sms-otp', (req,res)=>{
  const { challengeId, otp } = req.body;
  const result = verifyChallenge(challengeId, otp);
  if(!result.valid) return res.status(400).json(result);
  const user = [...users.values()].find(u=>u.id===result.challenge.userId);
  if(user){ user.mobileVerified=true; user.mfaEnabled=true; }
  res.json({ verified:true, mfaEnabled:true, next:'mfa-setup' });
});

router.post('/login', async (req,res)=>{
  const { email, password } = req.body;
  const user = users.get(email);
  if(!user) return res.status(401).json({error:'Invalid email or password. Please try again.', code:'INVALID'});
  const ok = await bcrypt.compare(password, user.passwordHash);
  if(!ok) return res.status(401).json({error:'Invalid email or password. Please try again.', code:'INVALID'});
  const { challengeId, expiresAt } = createChallenge(user.id, 'email');
  res.json({ mfaRequired:true, method:'email', challengeId, expiresAt, userId:user.id, email:user.email });
});

router.post('/verify-login-otp', (req,res)=>{
  const { challengeId, otp, email } = req.body;
  const result = verifyChallenge(challengeId, otp);
  if(!result.valid) return res.status(400).json(result);
  
  const user = [...users.values()].find(u=>u.id===result.challenge.userId) || users.get(email);
  if(!user) return res.status(404).json({error:'User not found'});
  
  const sessions = req.app.get('sessions');
  const sessionId = uuid();
  sessions.set(sessionId, user.id);
  
  res.cookie('sid', sessionId, { httpOnly:true, secure: process.env.NODE_ENV==='production', sameSite:'Lax', maxAge: 24*3600*1000 });
  
  const token = jwt.sign({ id:user.id, email:user.email, fullName:user.fullName }, JWT_SECRET, { expiresIn:'15m' });
  res.json({ success:true, token, user:{ id:user.id, email:user.email, fullName:user.fullName, mfaEnabled:user.mfaEnabled }, sessionId });
});

// Attach users map to app for other routes
router.users = users;
module.exports = router;
