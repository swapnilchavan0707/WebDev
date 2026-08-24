
# SecureID - IAM Authentication & Registration System

Full implementation of Login + Registration + MFA + OTP + Session + JWT

## Structure
```
secureid-complete/
├── backend/
│   ├── server.js
│   ├── routes/auth.js
│   ├── middleware/auth.js
│   ├── utils/otp.js
│   └── package.json
├── frontend/
│   ├── index.html (Main App - contains Login + Registration)
│   ├── login.html (Standalone Login)
│   ├── register.html (Standalone Registration)
│   ├── css/style.css
│   └── js/
│       ├── api.js
│       ├── auth.js
│       ├── otp.js
│       └── app.js
└── vercel.json
```

## API Endpoints Implemented
POST /api/register
POST /api/send-email-otp
POST /api/verify-email-otp
POST /api/send-sms-otp
POST /api/verify-sms-otp
POST /api/login
POST /api/verify-login-otp
GET  /api/me
POST /api/logout
POST /api/token
GET  /api/protected

## Deploy to Vercel
vercel --prod -> https://your-name-secureid.vercel.app
