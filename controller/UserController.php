<?php
/**
 * UserController — handles user CRUD for backoffice.
 * Validation: PHP server-side (here) + JS client-side (view/js/validate.js).
 * NO HTML5 validation attributes used anywhere.
 */
class UserController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->requireAuth();
    }

    private function requireAuth(): void
    {
        if (!SessionManager::isLoggedIn()) {
            SessionManager::setFlash('error', 'Accès réservé. Veuillez vous connecter.');
            header('Location: index.php?page=home');
            exit;
        }
    }

    // ── LIST ──────────────────────────────────────────────────

    public function list(): void
    {
        $search  = trim($_GET['search'] ?? '');
        $role    = $_GET['role'] ?? '';
        $status  = $_GET['status'] ?? '';
        $pageNum = max(1, (int)($_GET['p'] ?? 1));

        $filtered = $this->userModel->search($search, $role, $status);
        $paged    = $this->userModel->paginate($filtered, $pageNum);

        $success = isset($_GET['success']);

        $this->render('backoffice/list_users', [
            'currentUser'  => SessionManager::getUser(),
            'users'        => $paged['items'],
            'total'        => $paged['total'],
            'currentPage'  => $paged['currentPage'],
            'totalPages'   => $paged['totalPages'],
            'search'       => $search,
            'roleFilter'   => $role,
            'statusFilter' => $status,
            'success'      => $success,
            'errors'       => [],
            'page'         => 'users',
        ]);
    }

    // ── CREATE ────────────────────────────────────────────────

    public function create(): void
    {
        $errors = [];
        $data   = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateForm($_POST);

            if (empty($errors)) {
                $this->userModel->createUser($_POST);
                header('Location: index.php?action=list&success=1');
                exit;
            }
            // Keep submitted values to repopulate form
            $data = $_POST;
        }

        $this->render('backoffice/add_user', [
            'currentUser' => SessionManager::getUser(),
            'errors'      => $errors,
            'data'        => $data,
            'page'        => 'users',
        ]);
    }

    // ── EDIT ──────────────────────────────────────────────────

    public function edit(): void
    {
        $id     = (int)($_GET['id'] ?? 0);
        $errors = [];
        $user   = $this->userModel->findById($id);

        if (!$user) {
            SessionManager::setFlash('error', 'Utilisateur introuvable.');
            header('Location: index.php?action=list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateForm($_POST);

            if (empty($errors)) {
                $this->userModel->updateUser($id, $_POST);
                header('Location: index.php?action=list&success=1');
                exit;
            }
            // Keep submitted values to repopulate form
            $user = array_merge($user, $_POST);
        }

        $this->render('backoffice/edit_user', [
            'currentUser' => SessionManager::getUser(),
            'errors'      => $errors,
            'user'        => $user,
            'page'        => 'users',
        ]);
    }

    // ── DELETE ────────────────────────────────────────────────

    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $this->userModel->deleteUser($id);
        header('Location: index.php?action=list&success=1');
        exit;
    }

    // ── VALIDATION ────────────────────────────────────────────

    /**
     * Server-side validation — mirrors validate.js rules exactly.
     * This is the security fallback when JS is disabled.
     * Returns array of error messages; empty = valid.
     */
    private function validateForm(array $data): array
    {
        $errors   = [];
        $nom      = trim($data['nom']      ?? '');
        $prenom   = trim($data['prenom']   ?? '');
        $email    = trim($data['email']    ?? '');
        $password = trim($data['password'] ?? '');

        // RULE 1: All fields required
        if ($nom === '')      $errors[] = 'Le champ Nom est obligatoire.';
        if ($prenom === '')   $errors[] = 'Le champ Prénom est obligatoire.';
        if ($email === '')    $errors[] = 'Le champ Email est obligatoire.';
        if ($password === '') $errors[] = 'Le champ Mot de passe est obligatoire.';

        // RULE 2: Nom — letters only
        if ($nom !== '' && !preg_match('/^[a-zA-ZÀ-ÿ]+$/u', $nom)) {
            $errors[] = 'Le Nom doit contenir uniquement des lettres.';
        }

        // RULE 3: Prenom — letters only
        if ($prenom !== '' && !preg_match('/^[a-zA-ZÀ-ÿ]+$/u', $prenom)) {
            $errors[] = 'Le Prénom doit contenir uniquement des lettres.';
        }

        // RULE 4: Email must end with @gmail.com
        if ($email !== '' && !preg_match('/^[a-zA-Z0-9._%+\-]+@gmail\.com$/', $email)) {
            $errors[] = "L'email doit être au format exemple@gmail.com.";
        }

        // RULE 5: Password minimum 6 characters
        if ($password !== '' && strlen($password) < 6) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }

        return $errors;
    }

    // ── RENDER ────────────────────────────────────────────────

    private function render(string $view, array $data): void
    {
        extract($data);
        require_once __DIR__ . '/../view/' . $view . '.php';
    }
}
