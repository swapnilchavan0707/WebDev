
// Main SPA Controller - handles all screens from screenshots
let state = {
  flow: 'login', // login | register
  loginStep: 1,
  regStep: 1,
  challengeId: null,
  userId: null,
  email: 'priya.sharma@email.com',
  attempts: 3
};

function render(){
  const root = document.getElementById('root');
  if(state.flow==='login') renderLogin(root);
  else renderRegister(root);
}

function renderLogin(root){
  const steps = {
    1: `
      <div style="text-align:center"><div style="width:56px;height:56px;background:#EEF2FF;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;font-size:24px">🛡️</div>
      <h1>Welcome back!</h1><p class="sub">Login to your account</p></div>
      <div class="input-group"><label>Email or Username</label><input id="email" value="${state.email}" placeholder="Email or Username"></div>
      <div class="input-group"><label>Password</label><input id="pwd" type="password" placeholder="Password"><span style="position:absolute;right:12px;top:32px;cursor:pointer" onclick="this.previousElementSibling.type=this.previousElementSibling.type==='password'?'text':'password'">👁️</span></div>
      <div style="display:flex;justify-content:space-between;font-size:12px;margin:8px 0"><label><input type="checkbox"> Remember me</label><a href="#">Forgot password?</a></div>
      <button class="btn" onclick="doLogin()">Login</button>
      <div class="divider">or</div>
      <button class="google-btn"><img src="https://www.google.com/favicon.ico" width="16"> Continue with Google</button>
      <p style="text-align:center;font-size:12px;margin-top:12px">New here? <a href="#" onclick="state.flow='register';state.regStep=1;render()">Create an account</a></p>
    `,
    2: `
      <div style="text-align:center"><div style="width:56px;height:56px;background:#FEF2F2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;font-size:24px">🔒</div>
      <h1>Welcome back!</h1><p class="sub">Login to your account</p></div>
      <div class="input-group error"><label>Email or Username</label><input value="${state.email}"><span style="position:absolute;right:12px;top:32px;color:#DC2626">ⓧ</span><div class="error-text">Invalid email or password. Please try again.</div></div>
      <div class="input-group error"><label>Password</label><input type="password" value="********"><span style="position:absolute;right:12px;top:32px">👁️</span></div>
      <button class="btn" onclick="state.loginStep=1;render()">Login</button>
    `,
    3: `
      <h1>Verify your identity</h1><p class="sub">Choose a method to continue</p>
      <div class="method selected" onclick="selectMethod(this)"><span>📧</span><div><b>Email OTP</b><div style="font-size:11px;color:#6B7280">Receive a code on your email</div></div><span style="margin-left:auto">🔘</span></div>
      <div class="method" onclick="selectMethod(this)"><span>💬</span><div><b>SMS OTP</b><div style="font-size:11px">Receive a code on your mobile</div></div><span style="margin-left:auto">⚪</span></div>
      <div class="method" onclick="selectMethod(this)"><span>🔐</span><div><b>Authenticator App</b><div style="font-size:11px">Use code from authenticator app</div></div><span style="margin-left:auto">⚪</span></div>
      <button class="btn" style="margin-top:16px" onclick="state.loginStep=4;renderOTP()">Continue</button>
    `,
    4: renderOTPBlock('Email Verification','priya.sharma@email.com', false, false),
    5: renderOTPBlock('Email Verification','priya.sharma@email.com', true, false),
    6: renderOTPBlock('Email Verification','priya.sharma@email.com', false, true)
  };
  root.innerHTML = steps[state.loginStep] || steps[1];
  if(state.loginStep>=4) initOTP();
}

function renderOTPBlock(title, email, isWrong, isExpired){
  return `
    <div style="text-align:center"><div style="width:56px;height:56px;background:#EEF2FF;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto">📧</div>
    <h1>${title}</h1><p class="sub">Enter the 6-digit code sent to<br/><b>${email}</b></p></div>
    <div class="otp-boxes">${[0,1,2,3,4,5].map(i=>`<input class="otp-box ${isWrong&&i===5?'error':''}" maxlength="1">`).join('')}</div>
    <div id="otp-error" class="error-text" style="text-align:center">${isWrong?'Incorrect code. Please try again.<br/>You have 2 attempts left.': isExpired?'Code expired.':''}</div>
    <div class="timer" id="timer">Code expires in <b>02:45</b></div>
    <div style="text-align:center"><button class="btn" style="background:none;color:#1A4DD8;font-size:12px" id="resend-btn" onclick="resendOTP()">${isExpired?'Resend code':'Resend code (00:25)'}</button></div>
    <div style="text-align:center;margin-top:8px"><a href="#" style="font-size:12px">Didn't receive the code?</a></div>
    ${!isExpired?'<div class="keypad"><button class="key">1</button><button class="key">2</button><button class="key">3</button><button class="key">4</button><button class="key">5</button><button class="key">6</button><button class="key">7</button><button class="key">8</button><button class="key">9</button><button class="key"></button><button class="key">0</button><button class="key">⌫</button></div>': isExpired?'<button class="btn" style="margin-top:16px" onclick="resendOTP()">Resend code</button><p style="font-size:11px;text-align:center;margin-top:8px">You can request a new code in <b>00:28</b></p>':''}
  `;
}

function renderRegister(root){
  const map = {
    1: `<h1>Create your account</h1><p class="sub">Let's get you started</p>
      <div class="input-group"><label>Full Name</label><input value="Priya Sharma"></div>
      <div class="input-group"><label>Email</label><input value="${state.email}"></div>
      <div class="input-group"><label>Mobile Number</label><div style="display:flex;gap:6px"><select style="height:44px;border:1px solid #E5E7EB;border-radius:8px"><option>+91</option></select><input value="98765 43210" style="flex:1"></div></div>
      <div class="input-group"><label>Password</label><input type="password" value="Test@123"><div class="checklist"><li class="valid">✓ At least 8 characters</li><li class="valid">✓ 1 uppercase letter</li><li class="valid">✓ 1 number</li><li class="valid">✓ 1 special character</li></div></div>
      <label style="font-size:11px"><input type="checkbox" checked> I agree to the Terms & Conditions and Privacy Policy</label>
      <button class="btn" style="margin-top:12px" onclick="state.regStep=2;render()">Create Account</button>`,
    2: renderOTPBlock('Verify your email', state.email, false, false),
    3: `<h1>Verify your mobile</h1><p class="sub">We have sent a 6-digit code to<br/><b>+91 98765 43210</b></p>` + renderOTPBlock('', '', false, false).split('</p></div>')[1],
    4: `<div style="text-align:center"><div style="width:56px;height:56px;background:#EEF2FF;border-radius:50%;margin:0 auto;display:flex;align-items:center;justify-content:center">🛡️</div><h1>Set up Multi-Factor Auth</h1><p class="sub">Add an extra layer of security to protect your account.</p></div>
      <div class="method selected"><span>🔐</span><div><b>Authenticator App</b><div style="font-size:11px">(Google Authenticator / Authy)</div></div><span style="margin-left:auto">🔘</span></div>
      <div class="method"><span>💬</span><div><b>SMS Authentication</b><div style="font-size:11px">Receive codes on your mobile</div></div><span>⚪</span></div>
      <div class="method"><span>📧</span><div><b>Email Authentication</b><div style="font-size:11px">Receive codes on your email</div></div><span>⚪</span></div>
      <button class="btn" style="margin-top:12px" onclick="state.regStep=5;render()">Continue</button>`,
    5: `<div style="text-align:center"><h1>Scan QR Code</h1><p class="sub">Open your authenticator app and scan this QR code</p><div style="width:180px;height:180px;background:black;margin:16px auto;display:flex;align-items:center;justify-content:center;color:white">QR CODE</div><a href="#" style="font-size:12px">Can't scan? Enter setup key</a></div><button class="btn" style="margin-top:16px" onclick="state.regStep=6;render()">Continue</button>`,
    6: `<div style="text-align:center"><div style="width:48px;height:48px;background:#EEF2FF;border-radius:50%;margin:0 auto;display:flex;align-items:center;justify-content:center">🛡️</div><h1>Enter the 6-digit code</h1><p class="sub">Enter the code from your authenticator app</p></div><div class="otp-boxes">${[0,1,2,3,4,5].map(()=>`<input class="otp-box" maxlength="1">`).join('')}</div><div class="timer">Code expires in <b>00:28</b></div><a href="#" style="font-size:12px;display:block;text-align:center">Can't access your app?</a><button class="btn" style="margin-top:16px" onclick="state.regStep=7;render()">Verify</button>`,
    7: `<div style="text-align:center"><div style="width:64px;height:64px;background:#DCFCE7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;font-size:28px">✓</div><h1>Account created!</h1><p class="sub">Your account has been created successfully and MFA is enabled.</p><div style="text-align:left;font-size:12px;margin:16px 0"><div>✓ Email verified</div><div>✓ Mobile verified</div><div>✓ MFA enabled</div></div><button class="btn" onclick="state.flow='login';state.loginStep=1;render()">Continue to Login</button></div>`
  };
  root.innerHTML = map[state.regStep] || map[1];
  if([2,3,6].includes(state.regStep)) initOTP();
}

function initOTP(){
  const boxes = document.querySelectorAll('.otp-box');
  boxes.forEach((b,i)=>{
    b.addEventListener('input',()=>{ if(b.value && i<boxes.length-1) boxes[i+1].focus(); if([...boxes].every(x=>x.value)) onOTPComplete([...boxes].map(x=>x.value).join('')); });
  });
}
function onOTPComplete(code){
  console.log('[SIMULATED OTP ENTERED]', code);
  if(code==='482913' || code.length===6){ setTimeout(()=>{ if(state.flow==='login'){ alert('Login Success - Session created, JWT issued'); state.loginStep=1; } else { state.regStep++; } render(); }, 500); }
  else { document.getElementById('otp-error').textContent='Incorrect code. Please try again.'; }
}
function doLogin(){
  const email = document.getElementById('email').value;
  if(!email.includes('@')){ state.loginStep=2; render(); return; }
  state.loginStep=3; render();
}
function resendOTP(){ alert('New OTP sent - check console: 482913'); }
function selectMethod(el){ document.querySelectorAll('.method').forEach(m=>m.classList.remove('selected')); el.classList.add('selected'); }

render();
