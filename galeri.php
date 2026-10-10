<?php 
require 'config/koneksi.php'; 
include 'includes/header.php'; 
?>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-info">🖼️ Galeri Sekolah</h2>
        <p class="text-muted">Dokumentasi kegiatan dan momen seru di SMAN 1 Coding</p>
    </div>

    <div class="row g-4 mb-5">
        <?php
        $query = mysqli_query($koneksi, "SELECT * FROM galeri ORDER BY id DESC");
        
        if (mysqli_num_rows($query) > 0) {
            while ($data = mysqli_fetch_array($query)) {
        ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                    <img src="uploads/<?= htmlspecialchars($data['file_foto']); ?>" class="card-img-top" alt="<?= htmlspecialchars($data['judul']); ?>" style="height: 250px; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body text-center">
                        <h6 class="card-title fw-bold text-dark mb-1"><?= htmlspecialchars($data['judul']); ?></h6>
                        <small class="text-muted"><?= date('d F Y', strtotime($data['tanggal'])); ?></small>
                    </div>
                </div>
            </div>
        <?php 
            }
        } else { 
        ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-light shadow-sm d-inline-block">
                    <h5 class="text-muted mb-0"><i class="bi bi-camera"></i> Belum ada foto di galeri.</h5>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>