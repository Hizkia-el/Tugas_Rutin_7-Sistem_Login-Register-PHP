<?php
require_once 'functions.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $confirmPassword = (string)($_POST['confirm_password'] ?? '');

    // Validasi nama
    if ($name === '') {
        $errors[] = 'Nama wajib diisi.';
    } elseif (mb_strlen($name) < 3) {
        $errors[] = 'Nama minimal 3 karakter.';
    }

    // Validasi email
    if ($email === '') {
        $errors[] = 'Email wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    }

    // Validasi password
    if ($password === '') {
        $errors[] = 'Password wajib diisi.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password minimal 6 karakter.';
    }

    if ($confirmPassword === '') {
        $errors[] = 'Konfirmasi password wajib diisi.';
    } elseif ($password !== $confirmPassword) {
        $errors[] = 'Konfirmasi password tidak sama dengan password.';
    }

    // Cek email duplikat
    foreach (getUsers() as $user) {
        if (
            isset($user['email']) &&
            strtolower((string)$user['email']) === strtolower($email)
        ) {
            $errors[] = 'Email sudah terdaftar. Gunakan email lain.';
            break;
        }
    }

    if (empty($errors)) {
        $users = getUsers();

        $newUser = [
            'id' => uniqid('USR_', true),
            'name' => $name,
            'email' => strtolower($email),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $users[] = $newUser;

        if (saveUsers($users)) {
            setFlash('success', 'Registrasi berhasil. Silakan login.');
            redirect('login.php');
        }

        $errors[] = 'Data gagal disimpan ke file JSON.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?= e(APP_NAME) ?></title>
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
        <h1>Buat Akun</h1>
        <p class="subtitle">Registrasi akun baru dengan PHP Native + JSON.</p>

        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <strong>Registrasi gagal:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php" novalidate>
            <label for="name">Nama</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= e($name) ?>"
                placeholder="Masukkan nama"
                maxlength="100"
                required
            >

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= e($email) ?>"
                placeholder="nama@email.com"
                maxlength="150"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Minimal 6 karakter"
                minlength="6"
                required
            >

            <label for="confirm_password">Konfirmasi Password</label>
            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                placeholder="Ulangi password"
                minlength="6"
                required
            >

            <button type="submit" class="btn">Daftar</button>
        </form>

        <p class="bottom-text">
            Sudah punya akun?
            <a href="login.php">Login di sini</a>
        </p>
    </div>
</div>
</body>
</html>
