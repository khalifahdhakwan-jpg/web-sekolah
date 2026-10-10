<?php
session_start();

// PROTEKSI HALAMAN: Cek session, jika belum login tendang ke login.php
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login.php");
    exit;
}

// Panggil database
require '../config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SMAN 1 Coding</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; background-color: #f4f6f9; }
        
        /* Sidebar Styling */
        .sidebar { min-height: 100vh; width: 250px; background-color: #343a40; color: white; transition: all 0.3s; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 12px 20px; display: block; font-weight: 500; }
        .sidebar a:hover, .sidebar a.active { color: white; background-color: #495057; border-left: 4px solid #0d6efd; }
        
        /* Wrapper Styling */
        .content-wrapper { flex: 1; margin-left: 250px; padding: 20px; transition: all 0.3s; }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar { margin-left: -250px; position: fixed; z-index: 999; }
            .sidebar.show { margin-left: 0; }
            .content-wrapper { margin-left: 0; }
        }
    </style>
</head>
<body>

<div class="d-flex">
    
    <!-- SIDEBAR -->
    <div class="sidebar d-flex flex-column shadow" id="sidebar">
        <div class="p-3 text-center border-bottom border-secondary mb-2">
            <h5 class="mb-0 fw-bold text-white">🎓 Admin Panel</h5>
        </div>
        <div class="flex-grow-1">
            <a href="index.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
            <a href="kelas.php"><i class="bi bi-building me-2"></i> Data Kelas</a>
            <a href="guru.php"><i class="bi bi-person-badge me-2"></i> Data Guru</a>
            <a href="siswa.php"><i class="bi bi-people me-2"></i> Data Siswa</a>
            <a href="jadwal.php"><i class="bi bi-calendar3 me-2"></i> Jadwal Pelajaran</a>
            <a href="berita.php"><i class="bi bi-newspaper me-2"></i> Berita Sekolah</a>
            <a href="galeri.php"><i class="bi bi-images me-2"></i> Galeri</a>
        </div>
    </div>
    
    <!-- MAIN CONTENT WRAPPER -->
    <div class="content-wrapper">
        
        <!-- TOPBAR -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded mb-4 p-3">
            <div class="container-fluid px-0">
                <!-- Tombol toggle sidebar untuk mobile -->
                <button class="btn btn-outline-secondary d-md-none" id="toggleSidebar">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-3 fw-bold text-secondary">
                        <i class="bi bi-person-circle me-1"></i> Halo, <?= htmlspecialchars($_SESSION['nama_lengkap']); ?>
                    </span>
                    <a href="logout.php" class="btn btn-danger btn-sm fw-bold" onclick="return confirm('Yakin ingin keluar dari ruang kendali?')">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </nav>
        
        <!-- KONTEN HALAMAN DIMULAI DI SINI (Ditutup di footer) -->