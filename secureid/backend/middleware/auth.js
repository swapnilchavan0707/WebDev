
const jwt = require('jsonwebtoken');
const JWT_SECRET = process.env.JWT_SECRET || 'secureid-super-secret-jwt-key';

function requireSession(req,res,next){
  // Simulated session store attached to app
  const sid = req.cookies?.sid;
  const sessions = req.app.get('sessions');
  if(!sid || !sessions.has(sid)) return res.status(401).json({error:'Not authenticated - session missing'});
  req.userId = sessions.get(sid);
  next();
}

function requireJWT(req,res,next){
  const auth = req.headers.authorization;
  if(!auth || !auth.startsWith('Bearer ')) return res.status(401).json({error:'Missing Bearer token'});
  try{
    const payload = jwt.verify(auth.split(' ')[1], JWT_SECRET);
    req.jwtUser = payload;
    next();
  }catch(e){
    return res.status(401).json({error:'Invalid or expired JWT'});
  }
}

module.exports = { requireSession, requireJWT, JWT_SECRET };
