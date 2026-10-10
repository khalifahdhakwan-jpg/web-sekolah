<?php
// Panggil header yang sudah berisi pengecekan session dan koneksi DB
include 'includes/header.php';

// Ambil jumlah data dari database (tambahkan error handler (@) sementara jika tabel belum ada agar tidak fatal error)
$total_guru = @mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM guru")) ?? 0;
$total_siswa = @mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM siswa")) ?? 0;
$total_kelas = @mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM kelas")) ?? 0;
$total_berita = @mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM berita")) ?? 0;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark">Dashboard Utama</h3>
</div>

<!-- Menampilkan widget statistik -->
<div class="row g-4 mb-4">
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card bg-primary text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-building fs-1"></i>
                </div>
                <div>
                    <h6 class="card-title text-white-50 mb-1">Total Kelas</h6>
                    <h2 class="mb-0 fw-bold"><?= $total_kelas; ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card bg-success text-white border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-person-badge fs-1"></i>
                </div>
                <div>
                    <h6 class="card-title text-white-50 mb-1">Total Guru</h6>
                    <h2 class="mb-0 fw-bold"><?= $total_guru; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card bg-warning text-dark border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-people fs-1 text-dark-50"></i>
                </div>
                <div>
                    <h6 class="card-title text-dark-50 mb-1">Total Siswa</h6>
                    <h2 class="mb-0 fw-bold"><?= $total_siswa; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card bg-info text-dark border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-newspaper fs-1 text-dark-50"></i>
                </div>
                <div>
                    <h6 class="card-title text-dark-50 mb-1">Total Berita</h6>
                    <h2 class="mb-0 fw-bold"><?= $total_berita; ?></h2>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Pesan Welcome -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h5 class="card-title fw-bold">Selamat datang di Ruang Kendali! 🚀</h5>
        <p class="card-text text-muted mb-0">
            Sistem Informasi Sekolah ini siap digunakan. Gunakan menu di sebelah kiri untuk mulai mengelola data website. 
            <br>
            <b>Saran urutan pengisian data:</b> Input <i>Data Kelas</i> terlebih dahulu, lalu lanjutkan dengan <i>Data Guru</i>, baru kemudian <i>Data Siswa</i> dan <i>Jadwal Pelajaran</i> agar relasi database berjalan lancar.
        </p>
    </div>
</div>

<?php 
// Panggil footer penutup html
include 'includes/footer.php'; 
?>