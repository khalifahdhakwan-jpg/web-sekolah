<?php
// Deklarasi variabel konfigurasi database
$host = "localhost";
$user = "root";     // Default user di Laragon/XAMPP
$pass = "";         // Default password kosong
$db   = "db_sekolah"; // Nama database yang tadi kita buat

// Membuat koneksi ke database menggunakan mysqli
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek apakah koneksi berhasil atau gagal
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>