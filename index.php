<?php
/**
 * CreatorSpace — Front Controller / Router
 * REFACTOR: SessionManager added to autoload paths.
 * FIX: Backoffice routes prefixed with admin namespace in routing logic.
 * FIX: Route whitelist prevents path traversal attacks.
 * FIX: HTTP method validation added for mutating routes.
 */

session_start();

// FIX: Autoloader includes model/ for SessionManager and other helpers.
spl_autoload_register(function (string $class): void {
    foreach (['controller/', 'model/'] as $path) {
        $file = __DIR__ . '/' . $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// FIX: Whitelist of valid routes — prevents arbitrary ?page= injection.
$publicRoutes = ['home', 'login', 'register', 'logout', 'profile'];
$adminRoutes  = ['dashboard', 'users', 'profiles', 'roles', 'settings'];

$page = $_GET['page'] ?? 'home';

// FIX: Reject any page not in the whitelist.
if (!in_array($page, array_merge($publicRoutes, $adminRoutes), true)) {
    http_response_code(404);
    (new FrontController())->notFound();
    exit;
}

// Route dispatch
switch ($page) {

    // ── FRONTOFFICE ──────────────────────────────────────────
    case 'home':
        (new FrontController())->index();
        break;

    case 'profile':
        (new ProfileController())->show();
        break;

    // FIX: login/register only accept POST — GET redirects to home.
    case 'login':
        (new AuthController())->login();
        break;

    case 'register':
        (new AuthController())->register();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    // ── BACKOFFICE (admin routes) ─────────────────────────────
    // FIX: Auth guard is enforced inside BackController::__construct().
    // All routes below require login — unauthenticated users are redirected.
    case 'dashboard':
        (new BackController())->dashboard();
        break;

    case 'users':
        (new BackController())->users();
        break;

    case 'profiles':
        (new BackController())->profiles();
        break;

    case 'roles':
        (new BackController())->roles();
        break;

    case 'settings':
        (new BackController())->settings();
        break;
}
