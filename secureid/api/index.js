import express from 'express';
import cors from 'cors';
import bcrypt from 'bcryptjs';
import jwt from 'jsonwebtoken';
import cookieParser from 'cookie-parser';
import { v4 as uuidv4 } from 'uuid';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const app = express();
const PORT = process.env.PORT || 3000;
const JWT_SECRET = process.env.JWT_SECRET || 'secureid-jwt-secret-2024';

app.use(cors({ origin: true, credentials: true }));
app.use(express.json());
app.use(cookieParser());
app.use(express.static(path.join(__dirname, '../public')));

const users = new Map();
const challenges = new Map();
const sessions = new Map();
const loginAttempts = new Map();

function genOTP(){ return Math.floor(100000 + Math.random()*900000).toString(); }
function hashOTP(otp){ return bcrypt.hashSync(otp, 8); }
function createChallenge(userId, channel){
  const challengeId = uuidv4();
  const otp = genOTP();
  const expiresAt = Date.now() + 2.5*60*1000;
  console.log(`\n[SIMULATED ${channel.toUpperCase()}] To: ${userId} OTP: ${otp} Challenge: ${challengeId}\n`);
  challenges.set(challengeId, {
    userId, channel, otpHash: hashOTP(otp), otpPlain: otp,
    expiresAt, attempts:0, maxAttempts:3, used:false
  });
  return { challengeId, expiresAt };
}

app.post('/api/register', async (req,res)=>{
  const { fullName, email, mobile, password } = req.body;
  if(!email || !password || !fullName) return res.status(400).json({error:'Missing fields'});
  if(users.has(email)) return res.status(400).json({error:'User already exists'});
  if(password.length<8) return res.status(400).json({error:'Password must be 8+ chars'});
  const passwordHash = await bcrypt.hash(password, 10);
  const userId = uuidv4();
  const user = { id:userId, fullName, email, mobile, passwordHash, emailVerified:false, mobileVerified:false, mfaEnabled:false, createdAt:Date.now() };
  users.set(email, user);
  const { challengeId, expiresAt } = createChallenge(email, 'email');
  return res.json({ message:'User created, OTP sent', challengeId, expiresAt, userId });
});

app.post('/api/send-email-otp', (req,res)=>{
  const { email } = req.body;
  if(!users.has(email)) return res.status(404).json({error:'User not found'});
  const { challengeId, expiresAt } = createChallenge(email, 'email');
  res.json({ challengeId, expiresAt });
});

app.post('/api/verify-email-otp', (req,res)=>{
  const { challengeId, otp } = req.body;
  const ch = challenges.get(challengeId);
  if(!ch) return res.status(400).json({error:'Invalid challenge'});
  if(ch.used) return res.status(400).json({error:'OTP already used'});
  if(Date.now()>ch.expiresAt){ challenges.delete(challengeId); return res.status(400).json({error:'Code expired.', code:'EXPIRED'}); }
  if(ch.attempts>=ch.maxAttempts) return res.status(400).json({error:'Maximum attempts reached. Please request a new code.', code:'MAX_ATTEMPTS'});
  if(!bcrypt.compareSync(otp, ch.otpHash)){
    ch.attempts++;
    const left = ch.maxAttempts - ch.attempts;
    return res.status(400).json({error:'Incorrect code. Please try again.', attemptsLeft:left, code:'WRONG'});
  }
  ch.used=true;
  const user = users.get(ch.userId);
  if(user){ user.emailVerified=true; }
  challenges.delete(challengeId);
  res.json({ message:'Email verified' });
});

app.post('/api/send-sms-otp', (req,res)=>{
  const { email } = req.body;
  const user = users.get(email);
  if(!user || !user.emailVerified) return res.status(400).json({error:'Email not verified'});
  const { challengeId, expiresAt } = createChallenge(email, 'sms');
  res.json({ challengeId, expiresAt });
});

app.post('/api/verify-sms-otp', (req,res)=>{
  const { challengeId, otp } = req.body;
  const ch = challenges.get(challengeId);
  if(!ch) return res.status(400).json({error:'Invalid challenge'});
  if(ch.used) return res.status(400).json({error:'Already used'});
  if(Date.now()>ch.expiresAt) return res.status(400).json({error:'This code has expired.', code:'EXPIRED'});
  if(ch.attempts>=ch.maxAttempts) return res.status(400).json({error:'Maximum attempts reached.', code:'MAX_ATTEMPTS'});
  if(!bcrypt.compareSync(otp, ch.otpHash)){
    ch.attempts++;
    return res.status(400).json({error:'Incorrect code. Please try again.', attemptsLeft: ch.maxAttempts-ch.attempts, code:'WRONG'});
  }
  ch.used=true;
  const user = users.get(ch.userId);
  user.mobileVerified=true;
  user.mfaEnabled=true;
  challenges.delete(challengeId);
  res.json({ message:'Mobile verified, MFA enabled' });
});

app.post('/api/login', async (req,res)=>{
  const { email, password } = req.body;
  const user = users.get(email);
  if(!user) return res.status(401).json({error:'Invalid email or password. Please try again.'});
  const attempt = loginAttempts.get(email) || {count:0, lockUntil:0};
  if(Date.now()<attempt.lockUntil) return res.status(423).json({error:'Account temporarily locked. Try later.'});
  const ok = await bcrypt.compare(password, user.passwordHash);
  if(!ok){
    attempt.count++; if(attempt.count>=5) attempt.lockUntil=Date.now()+5*60*1000;
    loginAttempts.set(email, attempt);
    return res.status(401).json({error:'Invalid email or password. Please try again.'});
  }
  loginAttempts.delete(email);
  if(user.mfaEnabled){
    const { challengeId, expiresAt } = createChallenge(email, 'email');
    return res.json({ mfaRequired:true, method:'email', challengeId, expiresAt });
  }
  const sessionId = uuidv4();
  sessions.set(sessionId, email);
  res.cookie('sessionId', sessionId, { httpOnly:true, secure:false, sameSite:'lax', maxAge:24*3600*1000 });
  res.json({ message:'Login success', mfaRequired:false });
});

app.post('/api/verify-login-otp', (req,res)=>{
  const { challengeId, otp } = req.body;
  const ch = challenges.get(challengeId);
  if(!ch) return res.status(400).json({error:'Invalid challenge'});
  if(Date.now()>ch.expiresAt) return res.status(400).json({error:'Code expired.', code:'EXPIRED'});
  if(ch.attempts>=ch.maxAttempts) return res.status(400).json({error:'Maximum attempts reached.', code:'MAX_ATTEMPTS'});
  if(!bcrypt.compareSync(otp, ch.otpHash)){
    ch.attempts++;
    return res.status(400).json({error:'Incorrect code. Please try again.', attemptsLeft: ch.maxAttempts-ch.attempts, code:'WRONG', expiresAt:ch.expiresAt});
  }
  ch.used=true;
  challenges.delete(challengeId);
  const sessionId = uuidv4();
  sessions.set(sessionId, ch.userId);
  res.cookie('sessionId', sessionId, { httpOnly:true, secure:false, sameSite:'lax', maxAge:24*3600*1000 });
  const token = jwt.sign({ email: ch.userId }, JWT_SECRET, { expiresIn:'15m' });
  res.json({ message:'Login verified', token });
});

app.get('/api/me', (req,res)=>{
  const sid = req.cookies.sessionId;
  if(!sid || !sessions.has(sid)) return res.status(401).json({error:'Not authenticated'});
  const email = sessions.get(sid);
  const user = users.get(email);
  if(!user) return res.status(401).json({error:'User not found'});
  res.json({ id:user.id, fullName:user.fullName, email:user.email, mobile:user.mobile, emailVerified:user.emailVerified, mobileVerified:user.mobileVerified, mfaEnabled:user.mfaEnabled });
});

app.post('/api/logout', (req,res)=>{
  const sid = req.cookies.sessionId;
  if(sid) sessions.delete(sid);
  res.clearCookie('sessionId');
  res.json({ message:'Logged out' });
});

app.get('/api/_debug/otp/:challengeId', (req,res)=>{
  const ch = challenges.get(req.params.challengeId);
  if(!ch) return res.status(404).json({error:'Not found or expired'});
  res.json({ otp: ch.otpPlain, expiresAt: ch.expiresAt, attempts: ch.attempts });
});

app.get('/api/health', (req,res)=>res.json({ ok:true, users: users.size }));

app.get('*', (req,res)=>{
  if(req.path.startsWith('/api')) return res.status(404).json({error:'Not found'});
  res.sendFile(path.join(__dirname, '../public/index.html'));
});

app.listen(PORT, ()=>console.log(`SecureID running on http://localhost:${PORT}`));
export default app;
