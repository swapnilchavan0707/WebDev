
const bcrypt = require('bcryptjs');
const { v4: uuid } = require('uuid');

const challenges = new Map(); // challengeId -> challenge

function genOTP(){ return Math.floor(100000 + Math.random()*900000).toString(); }

function createChallenge(userId, channel='email'){
  const otp = genOTP();
  const challengeId = uuid();
  const challenge = {
    challengeId,
    userId,
    channel,
    otpHash: bcrypt.hashSync(otp, 8),
    otp, // only for simulated log
    expiresAt: Date.now() + 3*60*1000, // 3 min expiry
    attempts: 0,
    maxAttempts: 3,
    used: false,
    createdAt: Date.now()
  };
  challenges.set(challengeId, challenge);
  console.log(`\n[SIMULATED ${channel.toUpperCase()}] To: ${userId} | OTP: ${otp} | ID: ${challengeId} | Expires: ${new Date(challenge.expiresAt).toLocaleTimeString()}\n`);
  return { challengeId, expiresAt: challenge.expiresAt, otpForDev: otp };
}

function verifyChallenge(challengeId, inputOtp){
  const ch = challenges.get(challengeId);
  if(!ch) return { valid:false, error:'Challenge not found', code:'NOT_FOUND' };
  if(ch.used) return { valid:false, error:'OTP already used', code:'USED', expired:true };
  if(Date.now() > ch.expiresAt) return { valid:false, error:'Code expired', code:'EXPIRED', expired:true };
  if(ch.attempts >= ch.maxAttempts) return { valid:false, error:'Maximum attempts reached. Please request a new code.', code:'MAX_ATTEMPTS', maxAttempts:true };
  
  ch.attempts++;
  const match = bcrypt.compareSync(inputOtp, ch.otpHash);
  if(!match){
    const left = ch.maxAttempts - ch.attempts;
    return { valid:false, error:'Incorrect code. Please try again.', code:'WRONG', attemptsLeft:left };
  }
  ch.used = true;
  return { valid:true, challenge: ch };
}

module.exports = { challenges, createChallenge, verifyChallenge, genOTP };
