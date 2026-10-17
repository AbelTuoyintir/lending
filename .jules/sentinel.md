## 2026-03-30 - Password Reset Without Token Verification (Account Takeover)
**Vulnerability:** The password reset implementation in `AuthController.php` accepted `email` and `password` and directly updated user credentials without requiring or validating a reset token.
**Learning:** Custom password reset endpoints must always utilize Laravel's built-in `Password::reset()` broker or verify tokens generated via `Password::createToken()`. Relying only on `Request::validate()` without token verification leaves authentication bypass and account takeover risks open.
**Prevention:** Always delegate password resets to `Password::reset()` or enforce strict token verification against the `password_reset_tokens` table.
