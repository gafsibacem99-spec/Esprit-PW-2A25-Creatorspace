<?php
/**
 * CreatorSpace — FrontController
 * Handles public-facing pages (frontoffice).
 * No changes required — already clean.
 */
class FrontController
{
    public function index(): void
    {
        $this->render('frontoffice/home', [
            'currentUser' => SessionManager::getUser(),
            'page'        => 'home',
        ]);
    }

    public function notFound(): void
    {
        $this->render('frontoffice/404', [
            'currentUser' => SessionManager::getUser(),
            'page'        => '404',
        ]);
    }

    private function render(string $view, array $data): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../view/' . $view . '.php';
        if (!file_exists($viewFile)) {
            // Fallback if 404 view missing
            echo '<h1>404 — Page introuvable</h1>';
            return;
        }
        require_once $viewFile;
    }
}
