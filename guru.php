<?php 
require 'config/koneksi.php'; 
include 'includes/header.php'; 
?>

<div class="container mt-4">
    <div class="text-center mb-5">
        <h2 class="text-primary fw-bold">Data Guru Pengajar</h2>
        <p class="text-muted">Berikut adalah daftar tenaga pendidik profesional di SMAN 1 Coding.</p>
    </div>

    <div class="row g-4">
        <?php
        // Query ambil data guru dari database, diurutkan berdasarkan nama (Alphabet)
        $query = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY nama ASC");
        
        // Cek apakah ada datanya
        if (mysqli_num_rows($query) > 0) {
            while ($data = mysqli_fetch_array($query)) {
        ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-0 text-center">
                    <?php 
                    // Logika untuk menampilkan foto, kalau foto kosong di database, pakai gambar default
                    $foto = (!empty($data['foto'])) ? "uploads/".$data['foto'] : "https://ui-avatars.com/api/?name=".urlencode($data['nama'])."&background=0D8ABC&color=fff&size=250"; 
                    ?>
                    <img src="<?= $foto ?>" class="card-img-top" alt="<?= htmlspecialchars($data['nama']) ?>" style="height: 250px; object-fit: cover;">
                    
                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-1"><?= htmlspecialchars($data['nama']) ?></h6>
                        <p class="card-text text-muted small mb-2">NIP: <?= htmlspecialchars($data['nip']) ?></p>
                        <span class="badge bg-primary text-wrap"><?= htmlspecialchars($data['mapel']) ?></span>
                    </div>
                </div>
            </div>
        <?php
            }
        } else {
        ?>
            <!-- Tampilan jika database masih kosong -->
            <div class="col-12 text-center my-5">
                <div class="alert alert-warning d-inline-block shadow-sm">
                    <h5 class="mb-0">👨‍🏫 Data guru saat ini belum tersedia.</h5>
                    <small class="text-muted">Data akan muncul di sini setelah ditambahkan oleh Admin.</small>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>