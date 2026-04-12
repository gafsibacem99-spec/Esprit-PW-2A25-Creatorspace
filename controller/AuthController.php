<?php
/**
 * CreatorSpace — AuthController
 * REFACTOR: Controller is now a pure intermediary.
 * FIX: All validation logic moved to AuthModel::validateLogin() / validateRegister().
 * FIX: Session management delegated to SessionManager (not AuthModel).
 * FIX: Controller only: receives request → calls Model → redirects.
 */
class AuthController
{
    private AuthModel $authModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=home');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // FIX: Validation delegated to Model — no business logic in Controller.
        $errors = $this->authModel->validateLogin($email, $password);
        if (!empty($errors)) {
            SessionManager::setFlash('error', $errors[0]);
            header('Location: index.php?page=home');
            exit;
        }

        $user = $this->authModel->authenticate($email, $password);
        if ($user) {
            // FIX: Session set via SessionManager, not AuthModel.
            SessionManager::setUser($user);
            SessionManager::setFlash('success', 'Bienvenue, ' . htmlspecialchars($user['name']) . ' 👋');
            header('Location: index.php?page=profile');
        } else {
            SessionManager::setFlash('error', 'Email ou mot de passe incorrect.');
            header('Location: index.php?page=home');
        }
        exit;
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=home');
            exit;
        }

        $firstname = trim($_POST['firstname'] ?? '');
        $lastname  = trim($_POST['lastname'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';
        $type      = $_POST['account_type'] ?? 'creator';
        $terms     = isset($_POST['terms']);

        // FIX: Validation delegated to Model — no business logic in Controller.
        $errors = $this->authModel->validateRegister($firstname, $lastname, $email, $password, $terms);
        if (!empty($errors)) {
            SessionManager::setFlash('error', $errors[0]);
            header('Location: index.php?page=home');
            exit;
        }

        // FIX: User creation delegated to Model (renamed from register() to createUser()).
        $newUser = $this->authModel->createUser($firstname, $lastname, $email, $password, $type);
        SessionManager::setUser($newUser);
        SessionManager::setFlash('success', 'Compte créé avec succès ! ✨');
        header('Location: index.php?page=home');
        exit;
    }

    public function logout(): void
    {
        // FIX: Session destruction via SessionManager, not AuthModel.
        SessionManager::destroy();
        header('Location: index.php?page=home');
        exit;
    }
}
