<?php 
// Panggil koneksi database
require 'config/koneksi.php'; 

// Panggil layout header
include 'includes/header.php'; 
?>

<!-- Konten Utama (Home) -->
<div class="container mt-4">
    <!-- Bagian Jumbotron / Hero Section -->
    <div class="p-5 mb-4 bg-white rounded-3 shadow-sm border">
        <div class="container-fluid py-4 text-center">
            <h1 class="display-5 fw-bold text-primary">Selamat Datang di SMAN 1 Coding</h1>
            <p class="col-md-10 mx-auto fs-5 mt-3 text-muted">
                Membangun generasi cerdas, kreatif, dan inovatif di era digital. Jelajahi informasi terbaru mengenai sekolah kami, data pengajar, siswa, serta berbagai berita terkini.
            </p>
            <a href="profil.php" class="btn btn-primary btn-lg mt-3 px-4">Kenali Kami Lebih Dekat</a>
        </div>
    </div>

    <!-- Bagian Info Singkat (Card) -->
    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <h3 class="card-title text-success">📰 Berita</h3>
                    <p class="card-text">Dapatkan informasi dan pengumuman terbaru seputar kegiatan belajar mengajar.</p>
                    <a href="berita.php" class="btn btn-outline-success mt-auto">Lihat Berita</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <h3 class="card-title text-warning">👨‍🏫 Data Guru</h3>
                    <p class="card-text">Kenali lebih dekat tenaga pendidik profesional yang mengajar di sekolah kami.</p>
                    <a href="guru.php" class="btn btn-outline-warning text-dark mt-auto">Lihat Guru</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <h3 class="card-title text-info">🖼️ Galeri</h3>
                    <p class="card-text">Dokumentasi momen-momen seru dan prestasi siswa-siswi SMAN 1 Coding.</p>
                    <a href="galeri.php" class="btn btn-outline-info text-dark mt-auto">Lihat Galeri</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Panggil layout footer
include 'includes/footer.php'; 
?>