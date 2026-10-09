<?php
require_once 'functions.php';

requireLogin();
$user = currentUser();

if ($user === null) {
    session_unset();
    session_destroy();
    redirect('login.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="shooting-stars" aria-hidden="true">
    <span class="shooting-star"></span>
    <span class="shooting-star"></span>
    <span class="shooting-star"></span>
    <span class="shooting-star"></span>
</div>
<div class="container">
    <div class="topbar">
        <div>
            <div class="badge">TUGAS RUTIN 7</div>
            <h1>Dashboard</h1>
            <p class="subtitle">Halaman ini hanya dapat diakses setelah login.</p>
        </div>
        <a class="btn btn-danger" href="logout.php">Logout</a>
    </div>

    <div class="grid">
        <div class="card">
            <h2>Selamat datang, <?= e($user['name']) ?>!</h2>
            <p>Akun berhasil login dan session sedang aktif.</p>

            <div class="info-box">
                <span class="info-label">Nama</span>
                <strong><?= e($user['name']) ?></strong>
            </div>

            <div class="info-box">
                <span class="info-label">Email</span>
                <strong><?= e($user['email']) ?></strong>
            </div>

            <div class="info-box">
                <span class="info-label">Terdaftar</span>
                <strong><?= e($user['created_at'] ?? '-') ?></strong>
            </div>

            <a class="btn" href="profile.php">Edit Profile</a>
        </div>

        <div class="card">
            <h2>Checklist Tugas</h2>
            <ul class="checklist">
                <li>Form registrasi + validasi</li>
                <li>Validasi email dengan filter_var()</li>
                <li>Password menggunakan password_hash()</li>
                <li>Data disimpan ke JSON</li>
                <li>Login menggunakan session</li>
                <li>Dashboard diproteksi</li>
                <li>Logout dengan session_destroy()</li>
                <li>Sanitasi output dengan htmlspecialchars()</li>
                <li>Pesan error dan sukses jelas</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
