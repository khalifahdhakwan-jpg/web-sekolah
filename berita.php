<?php 
require 'config/koneksi.php'; 
include 'includes/header.php'; 
?>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">📰 Berita & Pengumuman Sekolah</h2>
        <p class="text-muted">Informasi terbaru seputar kegiatan dan pengumuman SMAN 1 Coding</p>
    </div>

    <div class="row g-4">
        <?php
        // Ambil data berita, urutkan dari yang paling baru
        $query = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY tanggal DESC, id DESC");
        
        if (mysqli_num_rows($query) > 0) {
            while ($data = mysqli_fetch_array($query)) {
        ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                    <!-- Tampilkan foto cover jika ada, jika tidak pakai gambar default -->
                    <?php if ($data['foto'] != ""): ?>
                        <img src="uploads/<?= htmlspecialchars($data['foto']); ?>" class="card-img-top" alt="Cover" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <img src="https://images.unsplash.com/photo-1546410531-ea4cea477149?q=80&w=800&auto=format&fit=crop" class="card-img-top" alt="Cover Default" style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <small class="text-muted mb-2 d-block">
                            <i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($data['tanggal'])); ?>
                        </small>
                        <h5 class="card-title fw-bold text-dark">
                            <?= htmlspecialchars($data['judul']); ?>
                        </h5>
                        <p class="card-text text-muted">
                            <!-- Potong isi teks maksimal 100 karakter untuk preview -->
                            <?= htmlspecialchars(substr($data['isi'], 0, 100)); ?>...
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3 pt-0">
                        <!-- Tombol arahkan ke halaman detail menggunakan parameter ID -->
                        <a href="detail_berita.php?id=<?= $data['id']; ?>" class="btn btn-outline-primary w-100 fw-bold">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
        <?php 
            }
        } else { 
        ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-light shadow-sm d-inline-block">
                    <h5 class="text-muted mb-0"><i class="bi bi-info-circle"></i> Belum ada berita yang dipublikasikan.</h5>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>