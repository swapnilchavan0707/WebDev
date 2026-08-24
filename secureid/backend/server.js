
const express = require('express');
const cors = require('cors');
const cookieParser = require('cookie-parser');
const jwt = require('jsonwebtoken');
const authRoutes = require('./routes/auth');
const { requireSession, requireJWT, JWT_SECRET } = require('./middleware/auth');

const app = express();
const PORT = process.env.PORT || 5000;

app.use(cors({ origin: true, credentials: true }));
app.use(express.json());
app.use(cookieParser());

// In-memory stores
const sessions = new Map();
app.set('sessions', sessions);

app.use('/api', authRoutes);

// Session Auth
app.get('/api/me', requireSession, (req,res)=>{
  const users = authRoutes.users;
  const user = [...users.values()].find(u=>u.id===req.userId);
  if(!user) return res.status(404).json({error:'User not found'});
  res.json({ id:user.id, email:user.email, fullName:user.fullName, mobile:user.mobile, mfaEnabled:user.mfaEnabled, emailVerified:user.emailVerified, mobileVerified:user.mobileVerified });
});

app.post('/api/logout', (req,res)=>{
  const sid = req.cookies?.sid;
  if(sid) sessions.delete(sid);
  res.clearCookie('sid');
  res.json({ loggedOut:true });
});

// JWT Auth
app.post('/api/token', (req,res)=>{
  const { email } = req.body;
  const users = authRoutes.users;
  const user = users.get(email) || [...users.values()][0];
  if(!user) return res.status(404).json({error:'No user found, register first'});
  const token = jwt.sign({ id:user.id, email:user.email }, JWT_SECRET, { expiresIn:'15m' });
  res.json({ token, expiresIn:'15m' });
});

app.get('/api/protected', requireJWT, (req,res)=>{
  res.json({ message:'JWT validated - protected resource accessed', user: req.jwtUser, timestamp: new Date().toISOString() });
});

app.get('/api/health', (req,res)=> res.json({ status:'ok', users: authRoutes.users.size, sessions: sessions.size }));

app.listen(PORT, ()=> console.log(`\n SecureID Backend running on http://localhost:${PORT}\n OTPs will be logged as [SIMULATED EMAIL/SMS]\n`));

module.exports = app;
