
const API_BASE = window.API_BASE || 'http://localhost:5000';

async function api(path, opts={}){
  const res = await fetch(`${API_BASE}${path}`, {
    ...opts,
    headers: { 'Content-Type':'application/json', ...(opts.headers||{}) },
    credentials:'include'
  });
  const data = await res.json().catch(()=>({}));
  if(!res.ok) throw data;
  return data;
}

const AuthAPI = {
  register: (payload)=> api('/api/register', {method:'POST', body:JSON.stringify(payload)}),
  sendEmailOTP: (email)=> api('/api/send-email-otp', {method:'POST', body:JSON.stringify({email})}),
  verifyEmailOTP: (challengeId, otp)=> api('/api/verify-email-otp', {method:'POST', body:JSON.stringify({challengeId, otp})}),
  sendSmsOTP: (userId)=> api('/api/send-sms-otp', {method:'POST', body:JSON.stringify({userId})}),
  verifySmsOTP: (challengeId, otp)=> api('/api/verify-sms-otp', {method:'POST', body:JSON.stringify({challengeId, otp})}),
  login: (email,password)=> api('/api/login', {method:'POST', body:JSON.stringify({email,password})}),
  verifyLoginOTP: (challengeId, otp)=> api('/api/verify-login-otp', {method:'POST', body:JSON.stringify({challengeId, otp})}),
  me: ()=> api('/api/me'),
  logout: ()=> api('/api/logout', {method:'POST'}),
  token: (email)=> api('/api/token', {method:'POST', body:JSON.stringify({email})}),
  protected: (jwt)=> api('/api/protected', {headers:{Authorization:`Bearer ${jwt}`}})
};
