<?php 
require 'config/koneksi.php'; 
include 'includes/header.php'; 
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <h2 class="text-center text-primary mb-4 fw-bold">Profil Sekolah</h2>
                    
                    <!-- Gambar placeholder gedung sekolah -->
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1200&auto=format&fit=crop" class="img-fluid rounded mb-4 shadow-sm" alt="Gedung Sekolah" style="height: 400px; width: 100%; object-fit: cover;">
                    
                    <h4 class="fw-bold border-bottom pb-2 mt-5">Sejarah Singkat</h4>
                    <p class="text-muted" style="line-height: 1.8;">
                        SMA Negeri 1 Coding didirikan pada tahun 2026 dengan tujuan mencetak generasi penerus bangsa yang tidak hanya cerdas secara akademik, tetapi juga melek teknologi. Berawal dari sebuah komunitas belajar kecil, sekolah ini kini telah berkembang menjadi institusi pendidikan modern yang dilengkapi dengan fasilitas digital memadai.
                    </p>
                    
                    <h4 class="fw-bold border-bottom pb-2 mt-5">Visi</h4>
                    <p class="text-muted fs-5 fst-italic">
                        "Menjadi institusi pendidikan terdepan dalam inovasi, karakter, dan teknologi di tingkat nasional pada tahun 2030."
                    </p>
                    
                    <h4 class="fw-bold border-bottom pb-2 mt-5">Misi</h4>
                    <ul class="text-muted" style="line-height: 1.8;">
                        <li>Menyelenggarakan proses pembelajaran yang interaktif berbasis teknologi informasi.</li>
                        <li>Membentuk karakter siswa yang berintegritas, disiplin, dan memiliki jiwa kepemimpinan.</li>
                        <li>Mengembangkan potensi, minat, dan bakat siswa khususnya di bidang literasi digital.</li>
                        <li>Membangun kemitraan dengan berbagai industri teknologi untuk membekali siswa dengan keahlian praktis.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>