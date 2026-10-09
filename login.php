<?php
require_once 'functions.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$errors = [];
$email = '';

$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Masukkan email yang valid.';
    }

    if ($password === '') {
        $errors[] = 'Password wajib diisi.';
    }

    if (empty($errors)) {
        $users = getUsers();
        $authenticatedUser = null;

        foreach ($users as $user) {
            if (
                isset($user['email'], $user['password']) &&
                strtolower((string)$user['email']) === strtolower($email) &&
                password_verify($password, (string)$user['password'])
            ) {
                $authenticatedUser = $user;
                break;
            }
        }

        if ($authenticatedUser !== null) {
            session_regenerate_id(true);

            $_SESSION['user_email'] = $authenticatedUser['email'];
            $_SESSION['user_name'] = $authenticatedUser['name'];

            if ($remember) {
                setcookie(
                    'remember_email',
                    $authenticatedUser['email'],
                    [
                        'expires' => time() + (30 * 24 * 60 * 60),
                        'path' => '/',
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]
                );
            } else {
                setcookie('remember_email', '', time() - 3600, '/');
            }

            redirect('dashboard.php');
        }

        $errors[] = 'Email atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="shooting-stars" aria-hidden="true">
    <span class="shooting-star"></span>
    <span class="shooting-star"></span>
    <span class="shooting-star"></span>
    <span class="shooting-star"></span>
</div>
<div class="container auth-container">
    <div class="card">
        <div class="badge">TUGAS RUTIN 7</div>
        <h1>Login</h1>
        <p class="subtitle">Masuk ke dashboard aplikasi.</p>

        <?php if ($flash): ?>
            <div class="alert success">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <strong>Login gagal:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= e($email) ?>"
                placeholder="nama@email.com"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan password"
                required
            >

            <label class="checkbox-row">
                <input type="checkbox" name="remember" value="1">
                <span>Remember Me</span>
            </label>

            <button type="submit" class="btn">Login</button>
        </form>

        <p class="bottom-text">
            Belum punya akun?
            <a href="register.php">Daftar sekarang</a>
        </p>
    </div>
</div>
</body>
</html>
