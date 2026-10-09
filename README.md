# Tugas Rutin 7 - Sistem Login/Register

Aplikasi PHP Native sederhana untuk memenuhi requirement:
1. Form registrasi + validasi nama, email, password.
2. Validasi email dengan `filter_var()`.
3. Password di-hash dengan `password_hash()`.
4. Data user disimpan ke file JSON.
5. Duplikasi email dicegah.
6. Login menggunakan session.
7. Dashboard dilindungi dari user yang belum login.
8. Logout menggunakan `session_destroy()`.
9. Output disanitasi dengan `htmlspecialchars()`.
10. Pesan error/sukses ditampilkan dengan jelas.

Bonus:
- Remember Me dengan cookie.
- Edit Profile.
- CSS tampilan gelap/purple.
- Proteksi output menggunakan helper `e()`.

## Struktur folder

```text
tugas_rutin_7_login_register/
│
├── index.php
├── config.php
├── functions.php
├── register.php
├── login.php
├── dashboard.php
├── profile.php
├── logout.php
├── README.md
│
├── assets/
│   └── style.css
│
└── data/
    └── users.json
```

## Cara menjalankan dengan XAMPP

1. Install XAMPP.
2. Jalankan **Apache** pada XAMPP Control Panel.
3. Salin folder `tugas_rutin_7_login_register` ke:
   `C:\xampp\htdocs\`
4. Pastikan struktur akhirnya seperti:
   `C:\xampp\htdocs\tugas_rutin_7_login_register\`
5. Buka browser.
6. Akses:
   `http://localhost/tugas_rutin_7_login_register/`
7. Daftar akun.
8. Login menggunakan akun yang baru dibuat.
9. Coba akses:
   `http://localhost/tugas_rutin_7_login_register/dashboard.php`
   sebelum login. Seharusnya otomatis diarahkan ke `login.php`.
10. Coba logout dan login lagi.
11. Coba Remember Me.

## Catatan

File `data/users.json` akan berisi data user. Password tidak disimpan sebagai plain text karena menggunakan `password_hash()`.

Contoh bentuk data:

```json
[
    {
        "id": "USR_xxxxxxxxx",
        "name": "Nama Mahasiswa",
        "email": "mahasiswa@email.com",
        "password": "$2y$10$...",
        "created_at": "2026-09-15 22:00:00"
    }
]
```

Jangan mengganti hasil hash password menjadi password biasa.

## Pengujian untuk demo

- Register dengan data valid -> berhasil.
- Register email sama dua kali -> ditolak.
- Register email tidak valid -> ditolak.
- Register password kurang dari 6 karakter -> ditolak.
- Konfirmasi password berbeda -> ditolak.
- Login email/password benar -> masuk dashboard.
- Login password salah -> muncul error.
- Buka dashboard tanpa login -> diarahkan ke login.
- Logout -> session dihapus dan kembali ke login.
- Output nama dengan karakter HTML -> ditampilkan aman karena disanitasi.
- Remember Me -> cookie dibuat dan dapat membantu login otomatis pada kunjungan berikutnya.

## Requirement vs file

- Registrasi: `register.php`
- Login: `login.php`
- Dashboard/protected page: `dashboard.php`
- Logout: `logout.php`
- Bonus edit profile: `profile.php`
- Fungsi/helper: `functions.php`
- Konfigurasi session + lokasi JSON: `config.php`
- Penyimpanan data: `data/users.json`
- Tampilan: `assets/style.css`

## Tema tampilan
Versi ini menggunakan tema **iOS-inspired Glassmorphism Space**:
- Efek kaca/transparan dengan `backdrop-filter`.
- Gradasi biru dan ungu.
- Background bertema luar angkasa.
- Bintang kecil pada background.
- Efek bintang jatuh.
- Tombol dan input bergaya glass UI.
- Tetap responsif untuk laptop dan HP.

Perubahan tema visual tidak mengubah alur PHP, validasi, session, password hashing, JSON, login, logout, maupun fitur Remember Me.
