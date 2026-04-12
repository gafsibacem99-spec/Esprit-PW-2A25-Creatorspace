<?php
/**
 * CreatorSpace — SessionManager
 * FIX: HTTP/session logic extracted from AuthModel.
 * Models must not touch $_SESSION — that is infrastructure concern.
 * This class is the single place that reads/writes the PHP session.
 */
class SessionManager
{
    public static function setUser(array $user): void
    {
        $_SESSION['user'] = $user;
    }

    public static function getUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function destroy(): void
    {
        unset($_SESSION['user']);
        session_destroy();
    }

    public static function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    public static function getFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }
}
