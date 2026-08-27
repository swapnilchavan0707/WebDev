
# SecureID - IAM Auth & Registration
Implements all guidelines from assignment.

## Run locally
npm install
npm start -> http://localhost:3000

## API
POST /api/register
POST /api/send-email-otp
POST /api/verify-email-otp
POST /api/send-sms-otp
POST /api/verify-sms-otp
POST /api/login
POST /api/verify-login-otp
GET /api/me (session cookie)
POST /api/logout
POST /api/token (issue JWT from session)
GET /api/protected (Bearer JWT)
GET /api/_debug/otp/:challengeId (dev only - shows OTP)

## Security
- OTP generated server-side, hashed with bcrypt, never returned
- Short expiry 2:45, attempts 3, single-use, invalidated after success
- Password hashed with bcrypt
- Session cookie HttpOnly, SameSite=lax
- JWT short-lived 15m
- No localStorage for tokens
- Simulated delivery via console.log [SIMULATED EMAIL]

## Deploy to Vercel
vercel --prod
Domain: your-name-secureid.vercel.app (set in Vercel dashboard -> Settings -> Domains)

## Flows match Figma
Login: Default -> Invalid -> Choose Method -> Email OTP -> Wrong -> Expired
Registration: Details -> Email OTP -> Wrong -> Expired -> Mobile OTP -> Wrong -> Max -> Set Up MFA -> QR -> MFA Verify -> Wrong -> Success -> Login
