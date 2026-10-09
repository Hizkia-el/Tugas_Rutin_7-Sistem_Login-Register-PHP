<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Membaca semua user dari file JSON.
 */
function getUsers(): array
{
    if (!file_exists(DATA_FILE)) {
        return [];
    }

    $json = file_get_contents(DATA_FILE);
    if ($json === false || trim($json) === '') {
        return [];
    }

    $users = json_decode($json, true);

    return is_array($users) ? $users : [];
}

/**
 * Menyimpan semua user ke file JSON.
 */
function saveUsers(array $users): bool
{
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if ($json === false) {
        return false;
    }

    return file_put_contents(DATA_FILE, $json, LOCK_EX) !== false;
}

/**
 * Escape output untuk mencegah XSS.
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect sederhana.
 */
function redirect(string $page): never
{
    header('Location: ' . $page);
    exit;
}

/**
 * Mengecek apakah user sudah login.
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_email'], $_SESSION['user_name']);
}

/**
 * Memaksa halaman hanya bisa dibuka setelah login.
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

/**
 * Mengambil user yang sedang login.
 */
function currentUser(): ?array
{
    if (!isLoggedIn()) {
        return null;
    }

    foreach (getUsers() as $user) {
        if (isset($user['email']) && strtolower($user['email']) === strtolower($_SESSION['user_email'])) {
            return $user;
        }
    }

    return null;
}

/**
 * Membuat flash message yang tampil satu kali.
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

/**
 * Memuat user dari Remember Me cookie.
 */
function loadRememberedUser(): void
{
    if (isLoggedIn() || empty($_COOKIE['remember_email'])) {
        return;
    }

    $rememberEmail = (string)$_COOKIE['remember_email'];

    foreach (getUsers() as $user) {
        if (
            isset($user['email']) &&
            strtolower((string)$user['email']) === strtolower($rememberEmail)
        ) {
            session_regenerate_id(true);
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            return;
        }
    }

    setcookie('remember_email', '', time() - 3600, '/');
}

// Coba login otomatis jika cookie Remember Me masih tersedia.
loadRememberedUser();
