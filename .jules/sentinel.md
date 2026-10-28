## 2026-03-30 - Password Reset Without Token Verification (Account Takeover)
**Vulnerability:** The password reset implementation in `AuthController.php` accepted `email` and `password` and directly updated user credentials without requiring or validating a reset token.
**Learning:** Custom password reset endpoints must always utilize Laravel's built-in `Password::reset()` broker or verify tokens generated via `Password::createToken()`. Relying only on `Request::validate()` without token verification leaves authentication bypass and account takeover risks open.
**Prevention:** Always delegate password resets to `Password::reset()` or enforce strict token verification against the `password_reset_tokens` table.

## 2026-10-19 - Account Takeover via Phone Matching in Self-Registration
**Vulnerability:** Public user portal registration matched and linked existing customer records using `Customer::where('email', $email)->orWhere('phone', $phone)`. An attacker could supply a target customer's phone number along with a new email address to gain access to that customer's account and financial details.
**Learning:** Automatically linking existing profiles during unauthenticated public self-registration via easily spoofable/public fields (like phone numbers) allows account takeover (ATO) and unauthorized access (IDOR).
**Prevention:** Match customer records strictly by unique, verified identifier (such as email) and never automatically link accounts based solely on unverified or non-unique profile attributes.

## 2026-11-03 - User Role Isolation in Administrative Management Queries
**Vulnerability:** `AdministratorController::index` queried `User::query()` without scoping by `role = 'admin'`. This exposed customer accounts on the system administrator management page, misclassifying customer users as system administrators.
**Learning:** Admin management controllers must explicitly scope database queries by administrative role (`role = 'admin'`) and group search conditions (`where(function ($query) { ... })`) to prevent leaking customer accounts and breaking boolean logic on keyword searches.
**Prevention:** Always restrict role-specific administrative queries using `where('role', 'admin')` and wrap optional search parameters in nested closure clauses.

## 2026-11-12 - Nested Condition Grouping for Search Controller OR Queries
**Vulnerability:** `SearchController` performed multiple top-level `orWhere` database conditions across Customer, Loan, and Payment models without wrapping them in a nested closure.
**Learning:** Unwrapped `orWhere` conditions break SQL boolean operator precedence (`AND` vs `OR`), causing additional filter constraints (or soft delete / tenant / status scopes) to be ignored for subsequent `OR` branches.
**Prevention:** Always wrap multiple `orWhere` search conditions inside a `where(function ($q) use ($query) { ... })` closure to isolate keyword search logic from scope constraints.
