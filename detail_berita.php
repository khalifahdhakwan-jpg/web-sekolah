<?php 
require 'config/koneksi.php'; 

// Tangkap ID dari URL, amankan dengan fungsi bawaan
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data spesifik berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM berita WHERE id = '$id'");

// Jika berita tidak ditemukan, lempar kembali ke halaman berita
if (mysqli_num_rows($query) == 0) {
    header("Location: berita.php");
    exit;
}

$data = mysqli_fetch_array($query);

include 'includes/header.php'; 
?>

<div class="container mt-5">
    <div class="row">
        <!-- Kolom konten utama -->
        <div class="col-lg-8 mx-auto">
            
            <!-- Breadcrumb (Navigasi) -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="berita.php" class="text-decoration-none">Berita</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Baca</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                <!-- Cover Gambar Besar -->
                <?php if ($data['foto'] != ""): ?>
                    <img src="uploads/<?= htmlspecialchars($data['foto']); ?>" class="card-img-top" alt="Cover" style="max-height: 400px; object-fit: cover;">
                <?php else: ?>
                    <img src="https://images.unsplash.com/photo-1546410531-ea4cea477149?q=80&w=1200&auto=format&fit=crop" class="card-img-top" alt="Cover Default" style="max-height: 400px; object-fit: cover;">
                <?php endif; ?>

                <div class="card-body p-4 p-md-5">
                    <!-- Judul & Tanggal -->
                    <h2 class="fw-bold text-dark mb-3"><?= htmlspecialchars($data['judul']); ?></h2>
                    <p class="text-muted mb-4 border-bottom pb-3">
                        <i class="bi bi-calendar-event text-primary"></i> Dipublikasikan pada: <b><?= date('d F Y', strtotime($data['tanggal'])); ?></b>
                        <span class="mx-2">|</span>
                        <i class="bi bi-person text-primary"></i> Oleh: <b>Admin SMAN 1 Coding</b>
                    </p>

                    <!-- Isi Berita -->
                    <div class="content-berita" style="font-size: 1.1rem; line-height: 1.8; color: #444;">
                        <!-- fungsi nl2br mengubah Enter di database menjadi <br> di HTML -->
                        <?= nl2br(htmlspecialchars($data['isi'])); ?>
                    </div>
                </div>
                
                <div class="card-footer bg-light p-4 text-center border-0">
                    <a href="berita.php" class="btn btn-outline-secondary px-4 fw-bold">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Berita
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>