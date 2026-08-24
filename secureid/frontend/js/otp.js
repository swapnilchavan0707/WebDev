
class OTPManager{
  constructor(boxesSelector, timerSelector){
    this.boxes = document.querySelectorAll(boxesSelector);
    this.timerEl = document.querySelector(timerSelector);
    this.expiry = null;
    this.resendTimer = null;
    this.timeLeft = 180;
    this.resendLeft = 30;
    this.attachEvents();
  }
  attachEvents(){
    this.boxes.forEach((box,i)=>{
      box.addEventListener('input', (e)=>{
        if(e.target.value.length===1 && i<this.boxes.length-1) this.boxes[i+1].focus();
        this.onChange();
      });
      box.addEventListener('keydown', (e)=>{
        if(e.key==='Backspace' && !e.target.value && i>0) this.boxes[i-1].focus();
      });
    });
  }
  onChange(){}
  getCode(){ return [...this.boxes].map(b=>b.value).join(''); }
  setError(msg){ 
    this.boxes.forEach(b=>b.classList.add('error'));
    const errEl = document.getElementById('otp-error');
    if(errEl) errEl.textContent = msg;
  }
  clearError(){ this.boxes.forEach(b=>b.classList.remove('error')); }
  startExpiry(seconds=180){
    this.timeLeft = seconds;
    clearInterval(this.expiry);
    this.expiry = setInterval(()=>{
      this.timeLeft--;
      if(this.timerEl) this.timerEl.innerHTML = `Code expires in <b>00:${String(this.timeLeft%60).padStart(2,'0')}</b>`;
      if(this.timeLeft<=0){ clearInterval(this.expiry); this.onExpire(); }
    },1000);
  }
  onExpire(){}
  startResend(seconds=25){
    this.resendLeft = seconds;
    clearInterval(this.resendTimer);
    const resendBtn = document.getElementById('resend-btn');
    this.resendTimer = setInterval(()=>{
      this.resendLeft--;
      if(resendBtn) resendBtn.textContent = `Resend code (00:${String(this.resendLeft).padStart(2,'0')})`;
      if(this.resendLeft<=0){ clearInterval(this.resendTimer); if(resendBtn) resendBtn.textContent='Resend code'; resendBtn.disabled=false; }
    },1000);
  }
}
