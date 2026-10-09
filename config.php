<?php
// Konfigurasi dasar aplikasi
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_NAME', 'Sistem Login/Register');
define('BASE_PATH', __DIR__);
define('DATA_FILE', BASE_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'users.json');

// Pastikan file JSON tersedia.
if (!file_exists(DATA_FILE)) {
    file_put_contents(DATA_FILE, json_encode([], JSON_PRETTY_PRINT), LOCK_EX);
}
