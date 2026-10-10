<?php
// Panggil header
include 'includes/header.php';

$pesan = '';

// ==========================================
// 1. PROSES TAMBAH FOTO GALERI
// ==========================================
if (isset($_POST['tambah'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $tanggal = date('Y-m-d'); // Tanggal otomatis hari ini
    
    // Wajib ada file foto untuk galeri
    if ($_FILES['foto']['name'] != '') {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $nama_file = $_FILES['foto']['name'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['foto']['size'];
        $file_tmp = $_FILES['foto']['tmp_name'];
        
        $nama_file_baru = 'galeri_' . time() . '-' . preg_replace('/[^A-Za-z0-9.\-]/', '', $nama_file);
        
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 3048000) { // Maks 3MB khusus galeri
                move_uploaded_file($file_tmp, '../uploads/' . $nama_file_baru);
                
                $query = mysqli_query($koneksi, "INSERT INTO galeri (judul, file_foto, tanggal) VALUES ('$judul', '$nama_file_baru', '$tanggal')");
                if ($query) {
                    $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Foto berhasil ditambahkan ke galeri!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
                } else {
                    $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal menyimpan data ke database!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
                }
            } else {
                $pesan = "<div class='alert alert-danger'>❌ Ukuran foto terlalu besar! Maksimal 3MB.</div>";
            }
        } else {
            $pesan = "<div class='alert alert-danger'>❌ Format file tidak didukung! Hanya JPG/PNG.</div>";
        }
    } else {
        $pesan = "<div class='alert alert-danger'>❌ Anda belum memilih file foto untuk diunggah!</div>";
    }
}

// ==========================================
// 2. PROSES EDIT JUDUL/FOTO GALERI
// ==========================================
if (isset($_POST['edit'])) {
    $id = $_POST['id_galeri'];
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $foto_lama = $_POST['foto_lama'];
    
    $foto_baru = $foto_lama; 
    
    if ($_FILES['foto']['name'] != '') {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $nama_file = $_FILES['foto']['name'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['foto']['size'];
        $file_tmp = $_FILES['foto']['tmp_name'];
        
        $nama_file_baru = 'galeri_' . time() . '-' . preg_replace('/[^A-Za-z0-9.\-]/', '', $nama_file);
        
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 3048000) {
                if ($foto_lama != "" && file_exists('../uploads/' . $foto_lama)) {
                    unlink('../uploads/' . $foto_lama);
                }
                move_uploaded_file($file_tmp, '../uploads/' . $nama_file_baru);
                $foto_baru = $nama_file_baru;
            } else {
                $pesan = "<div class='alert alert-danger'>❌ Ukuran foto pengganti terlalu besar! Maksimal 3MB.</div>";
            }
        } else {
            $pesan = "<div class='alert alert-danger'>❌ Format file pengganti tidak didukung!</div>";
        }
    }

    if (empty($pesan) || !strpos($pesan, '❌')) { 
        $query = mysqli_query($koneksi, "UPDATE galeri SET judul = '$judul', file_foto = '$foto_baru' WHERE id = '$id'");
        if ($query) {
            $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Data galeri berhasil diperbarui!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        } else {
            $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal memperbarui data galeri!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        }
    }
}

// ==========================================
// 3. PROSES HAPUS FOTO GALERI
// ==========================================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    $cek_foto = mysqli_query($koneksi, "SELECT file_foto FROM galeri WHERE id = '$id'");
    $data_foto = mysqli_fetch_assoc($cek_foto);
    $foto_hapus = $data_foto['file_foto'];
    
    $query = mysqli_query($koneksi, "DELETE FROM galeri WHERE id = '$id'");
    
    if ($query) {
        if ($foto_hapus != "" && file_exists('../uploads/' . $foto_hapus)) {
            unlink('../uploads/' . $foto_hapus);
        }
        $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Foto berhasil dihapus dari galeri!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal menghapus foto!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark"><i class="bi bi-images"></i> Manajemen Galeri</h3>
    <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-cloud-arrow-up-fill"></i> Upload Foto
    </button>
</div>

<?= $pesan; ?>

<!-- TAMPILAN GRID GALERI -->
<div class="row g-4">
    <?php
    $query_tampil = mysqli_query($koneksi, "SELECT * FROM galeri ORDER BY id DESC");
    
    if (mysqli_num_rows($query_tampil) > 0) {
        while ($data = mysqli_fetch_array($query_tampil)) {
    ?>
    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
        <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
            <!-- Menampilkan Foto -->
            <img src="../uploads/<?= htmlspecialchars($data['file_foto']); ?>" class="card-img-top" alt="<?= htmlspecialchars($data['judul']); ?>" style="height: 220px; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            
            <div class="card-body text-center pb-2">
                <h6 class="card-title fw-bold text-dark mb-1 text-truncate" title="<?= htmlspecialchars($data['judul']); ?>">
                    <?= htmlspecialchars($data['judul']); ?>
                </h6>
                <small class="text-muted"><i class="bi bi-calendar-event"></i> <?= date('d M Y', strtotime($data['tanggal'])); ?></small>
            </div>
            
            <div class="card-footer bg-white border-0 text-center pb-3 pt-0">
                <hr class="mt-0 mb-3 text-muted">
                <!-- Tombol Edit -->
                <button class="btn btn-outline-warning btn-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['id']; ?>">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>
                <!-- Tombol Hapus -->
                <a href="galeri.php?hapus=<?= $data['id']; ?>" class="btn btn-outline-danger btn-sm fw-bold px-3 ms-1" onclick="return confirm('Yakin ingin menghapus foto ini secara permanen?')">
                    <i class="bi bi-trash"></i> Hapus
                </a>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT DATA GALERI -->
    <div class="modal fade" id="modalEdit<?= $data['id']; ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold">Edit Foto Galeri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-body text-start">
                        <input type="hidden" name="id_galeri" value="<?= $data['id']; ?>">
                        <input type="hidden" name="foto_lama" value="<?= $data['file_foto']; ?>">
                        
                        <div class="text-center mb-3">
                            <img src="../uploads/<?= $data['file_foto']; ?>" class="img-thumbnail" style="height: 150px; object-fit: cover;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Keterangan / Judul Foto</label>
                            <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ganti Foto (Opsional)</label>
                            <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php 
        }
    } else { 
    ?>
        <div class="col-12 text-center py-5">
            <div class="text-muted">
                <i class="bi bi-camera text-secondary mb-3 d-block" style="font-size: 3rem;"></i>
                <h5>Album galeri masih kosong.</h5>
                <p>Klik tombol <b>Upload Foto</b> di atas untuk mulai menambahkan dokumentasi.</p>
            </div>
        </div>
    <?php } ?>
</div>

<!-- MODAL TAMBAH DATA GALERI -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Upload Foto Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan / Judul Foto</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Upacara Kemerdekaan 2026" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih File Foto <span class="text-danger">*</span></label>
                        <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg" required>
                        <small class="text-muted">Format: JPG/PNG, Maksimal 3MB. Disarankan mode landscape.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Upload ke Galeri</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
// Panggil footer
include 'includes/footer.php'; 
?>