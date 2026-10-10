<?php
// Panggil file koneksi yang tadi kita buat
require 'config/koneksi.php';

echo "<h3>✅ Koneksi Database Berhasil!</h3>";

// Cek apakah admin sudah ada di database
$cek_admin = mysqli_query($koneksi, "SELECT * FROM users WHERE username = 'admin'");

if (mysqli_num_rows($cek_admin) == 0) {
    // Kalau admin belum ada, kita buatkan
    $username = 'admin';
    // Gunakan password_hash untuk keamanan standar industri, JANGAN pakai MD5
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $nama_lengkap = 'Administrator Web';

    $insert = mysqli_query($koneksi, "INSERT INTO users (username, password, nama_lengkap) VALUES ('$username', '$password', '$nama_lengkap')");

    if ($insert) {
        echo "<p>🚀 Akun Admin berhasil dibuat!</p>";
        echo "<p>Username: <b>admin</b></p>";
        echo "<p>Password: <b>admin123</b></p>";
    } else {
        echo "<p>❌ Gagal membuat akun admin: " . mysqli_error($koneksi) . "</p>";
    }
} else {
    echo "<p>👍 Akun Admin sudah tersedia di database.</p>";
}
?>