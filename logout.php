<?php
require_once 'functions.php';

// Hapus cookie Remember Me
setcookie('remember_email', '', time() - 3600, '/');

// Hapus semua data session
$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        (bool)$params['secure'],
        (bool)$params['httponly']
    );
}

session_destroy();

header('Location: login.php');
exit;
