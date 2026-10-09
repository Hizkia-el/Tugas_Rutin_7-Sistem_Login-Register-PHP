<?php
require_once 'functions.php';

requireLogin();

$user = currentUser();

if ($user === null) {
    redirect('logout.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = trim((string)($_POST['name'] ?? ''));

    if ($newName === '') {
        $errors[] = 'Nama wajib diisi.';
    } elseif (mb_strlen($newName) < 3) {
        $errors[] = 'Nama minimal 3 karakter.';
    }

    if (empty($errors)) {
        $users = getUsers();

        foreach ($users as &$storedUser) {
            if (
                isset($storedUser['email']) &&
                strtolower((string)$storedUser['email']) === strtolower((string)$user['email'])
            ) {
                $storedUser['name'] = $newName;
                $_SESSION['user_name'] = $newName;
                $user = $storedUser;
                break;
            }
        }
        unset($storedUser);

        if (saveUsers($users)) {
            setFlash('success', 'Profil berhasil diperbarui.');
            redirect('dashboard.php');
        }

        $errors[] = 'Profil gagal disimpan.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - <?= e(APP_NAME) ?></title>
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
        <div class="badge">BONUS</div>
        <h1>Edit Profile</h1>
        <p class="subtitle">Fitur bonus untuk memperbarui nama pengguna.</p>

        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="profile.php">
            <label for="name">Nama</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= e($user['name']) ?>"
                maxlength="100"
                required
            >

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                value="<?= e($user['email']) ?>"
                disabled
            >

            <button type="submit" class="btn">Simpan Perubahan</button>
            <a class="btn btn-secondary" href="dashboard.php">Kembali</a>
        </form>
    </div>
</div>
</body>
</html>
