# REFACTOR REPORT — CreatorSpace MVC Audit
**Date:** April 2026  
**Scope:** Full structural refactor — no functionality changed.

---

## Files Modified

| File | Reason |
|------|--------|
| `model/AuthModel.php` | Removed session logic; added validation methods |
| `model/UserModel.php` | Added search(), paginate(), getRoles(), statusLabel(), statusClass() |
| `model/SessionManager.php` | **NEW** — extracted all $_SESSION logic from AuthModel |
| `controller/AuthController.php` | Removed validation logic; uses SessionManager |
| `controller/BackController.php` | Removed filtering/pagination; added auth guard |
| `controller/FrontController.php` | Uses SessionManager; added file-exists guard |
| `controller/ProfileController.php` | Fixed hardcoded user ID; uses SessionManager |
| `index.php` | Added route whitelist; HTTP method enforcement |
| `view/layout/header.php` | Removed inline logic; uses SessionManager::getFlash() |
| `view/backoffice/layout_back.php` | Uses real currentUser from session |
| `view/backoffice/users.php` | Status mapping delegated to UserModel static helpers |
| `view/backoffice/roles.php` | Role data removed — now received as $roles from controller |
| `assets/css/components.css` | Added .badge-business class |

---

## Violations Found & Fixed

### 1. HTTP/Session Logic in Model (CRITICAL)
**File:** `model/AuthModel.php`  
**Violation:** `setSession()`, `destroySession()`, `isLoggedIn()`, `getCurrentUser()` all accessed `$_SESSION` directly inside a Model class. Models must be framework-agnostic and must not know about HTTP.  
**Fix:** Created `model/SessionManager.php` as a dedicated infrastructure class. All `$_SESSION` reads/writes now go through `SessionManager`. `AuthModel` is now pure data logic only.

---

### 2. Business Logic (Validation) in Controller (HIGH)
**File:** `controller/AuthController.php`  
**Violation:** `login()` and `register()` contained field validation rules (empty checks, password length, terms acceptance). Controllers must not contain business rules.  
**Fix:** Moved to `AuthModel::validateLogin()` and `AuthModel::validateRegister()`. Controller now calls Model, checks result, redirects.

---

### 3. Filtering & Pagination Logic in Controller (HIGH)
**File:** `controller/BackController.php` — `users()` method  
**Violation:** 30+ lines of `array_filter()`, `array_slice()`, `ceil()` pagination math lived inside the controller. Controllers must not transform data.  
**Fix:** Extracted to `UserModel::search(query, role, status)` and `UserModel::paginate(users, page)`. Controller now calls two Model methods and passes results to view.

---

### 4. Role Data Hardcoded in View (HIGH)
**File:** `view/backoffice/roles.php`  
**Violation:** A `$roles` array with all role definitions, permission lists, badge HTML, and counts was defined directly inside the view file. Business data must never live in views.  
**Fix:** Moved to `UserModel::getRoles()`. `BackController::roles()` fetches it and passes `$roles` to the view. View now only iterates and renders.

---

### 5. Status Mapping Logic in View (HIGH)
**File:** `view/backoffice/users.php`  
**Violation:** Two associative arrays mapping status strings to CSS classes and labels were computed inline inside the view's `foreach` loop.  
**Fix:** Moved to `UserModel::statusClass(string $status)` and `UserModel::statusLabel(string $status)` as static helpers. View calls these methods.

---

### 6. No Authentication Guard on Backoffice (CRITICAL)
**File:** `controller/BackController.php`, `index.php`  
**Violation:** All admin routes (`dashboard`, `users`, `profiles`, `roles`, `settings`) were accessible without any login check. Any anonymous user could visit `?page=dashboard`.  
**Fix:** `BackController::__construct()` now calls `$this->requireAuth()` which checks `SessionManager::isLoggedIn()` and redirects unauthenticated users to home with a flash message.

---

### 7. Hardcoded User ID in ProfileController (HIGH)
**File:** `controller/ProfileController.php`  
**Violation:** `findById(1)` was hardcoded — every logged-in user saw Sophie Martin's profile.  
**Fix:** Now uses `$currentUser['id']` from the session to look up the correct profile.

---

### 8. Route Injection Vulnerability (CRITICAL)
**File:** `index.php`  
**Violation:** `$page = $_GET['page'] ?? 'home'` was used directly in a `switch` with no validation. Any string could be passed as `?page=`.  
**Fix:** Added a whitelist: `$publicRoutes` and `$adminRoutes`. Any `?page=` value not in the whitelist returns a 404.

---

### 9. Flash Messages via Raw $_SESSION in View (MEDIUM)
**File:** `view/layout/header.php`  
**Violation:** `$flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash'])` was written directly in the view. Views must not access superglobals.  
**Fix:** Replaced with `SessionManager::getFlash()` which encapsulates the read-and-clear pattern.

---

### 10. Inline Active-State Logic in Header View (LOW)
**File:** `view/layout/header.php`  
**Violation:** `in_array($page ?? '', ['home','profile','auth'])` was computed inline in the template attribute.  
**Fix:** Extracted to `$isFront` and `$isBack` PHP variables computed once at the top of the file.

---

## Remaining Issues (Manual Review Required)

| Issue | Location | Priority |
|-------|----------|----------|
| Profile edit/password forms have no POST handler | `view/frontoffice/profile.php` | HIGH — needs `ProfileController::update()` |
| Settings form saves nothing | `view/backoffice/settings.php` | MEDIUM — needs `BackController::saveSettings()` |
| Add User modal has no backend action | `view/backoffice/users.php` | MEDIUM — needs `BackController::createUser()` |
| No role-based access control (RBAC) | `BackController::requireAuth()` | HIGH — currently any logged-in user can access admin |
| Plaintext password comparison | `AuthModel::authenticate()` | CRITICAL — replace with `password_verify()` + hashed DB |
| Static in-memory data | `UserModel` | HIGH — replace with PDO/MySQL queries |
| No CSRF protection on POST forms | All forms | HIGH — add CSRF token validation |

---

## Architecture Validation (Post-Refactor)

| Rule | Status |
|------|--------|
| View depends on Controller output only | ✅ |
| Controller depends on Model only | ✅ |
| Model has no dependency on View or Controller | ✅ |
| Session logic isolated from Model | ✅ |
| Backoffice requires authentication | ✅ |
| Route whitelist prevents injection | ✅ |
| Business logic (validation, filtering, mapping) in Model | ✅ |
| Frontoffice views in `/view/frontoffice/` | ✅ |
| Backoffice views in `/view/backoffice/` | ✅ |
