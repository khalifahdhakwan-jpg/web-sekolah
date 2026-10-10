<?php
// Panggil header
include 'includes/header.php';

$pesan = '';

// Fungsi untuk membuat slug URL (mengubah teks menjadi format-url-yang-aman)
function buatSlug($string) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    return $slug;
}

// ==========================================
// 1. PROSES TAMBAH DATA BERITA
// ==========================================
if (isset($_POST['tambah'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $tanggal = date('Y-m-d'); // Otomatis ambil tanggal hari ini
    $slug = buatSlug($judul); // Otomatis bikin slug dari judul
    
    $foto = ""; 
    
    // Cek upload foto
    if ($_FILES['foto']['name'] != '') {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $nama_file = $_FILES['foto']['name'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['foto']['size'];
        $file_tmp = $_FILES['foto']['tmp_name'];
        
        $nama_file_baru = 'berita_' . time() . '-' . $nama_file;
        
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 2048000) { // Maks 2MB
                move_uploaded_file($file_tmp, '../uploads/' . $nama_file_baru);
                $foto = $nama_file_baru;
            } else {
                $pesan = "<div class='alert alert-danger'>❌ Ukuran file foto terlalu besar! Maksimal 2MB.</div>";
            }
        } else {
            $pesan = "<div class='alert alert-danger'>❌ Ekstensi file foto tidak diperbolehkan!</div>";
        }
    }

    if (empty($pesan)) {
        $query = mysqli_query($koneksi, "INSERT INTO berita (judul, slug, isi, tanggal, foto) VALUES ('$judul', '$slug', '$isi', '$tanggal', '$foto')");
        if ($query) {
            $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Berita berhasil dipublikasikan!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        } else {
            $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal publikasi berita: " . mysqli_error($koneksi) . "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        }
    }
}

// ==========================================
// 2. PROSES EDIT DATA BERITA
// ==========================================
if (isset($_POST['edit'])) {
    $id = $_POST['id_berita'];
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $slug = buatSlug($judul); // Update slug jika judul berubah
    $foto_lama = $_POST['foto_lama'];
    
    $foto_baru = $foto_lama; 
    
    if ($_FILES['foto']['name'] != '') {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $nama_file = $_FILES['foto']['name'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['foto']['size'];
        $file_tmp = $_FILES['foto']['tmp_name'];
        
        $nama_file_baru = 'berita_' . time() . '-' . $nama_file;
        
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 2048000) {
                if ($foto_lama != "" && file_exists('../uploads/' . $foto_lama)) {
                    unlink('../uploads/' . $foto_lama);
                }
                move_uploaded_file($file_tmp, '../uploads/' . $nama_file_baru);
                $foto_baru = $nama_file_baru;
            } else {
                $pesan = "<div class='alert alert-danger'>❌ Ukuran file foto baru terlalu besar! Maks 2MB.</div>";
            }
        } else {
            $pesan = "<div class='alert alert-danger'>❌ Ekstensi file foto tidak diperbolehkan!</div>";
        }
    }

    if (empty($pesan) || !strpos($pesan, '❌')) { 
        $query = mysqli_query($koneksi, "UPDATE berita SET judul = '$judul', slug = '$slug', isi = '$isi', foto = '$foto_baru' WHERE id = '$id'");
        if ($query) {
            $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Berita berhasil diperbarui!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        } else {
            $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal memperbarui berita!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        }
    }
}

// ==========================================
// 3. PROSES HAPUS DATA BERITA
// ==========================================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    $cek_foto = mysqli_query($koneksi, "SELECT foto FROM berita WHERE id = '$id'");
    $data_foto = mysqli_fetch_assoc($cek_foto);
    $foto_hapus = $data_foto['foto'];
    
    $query = mysqli_query($koneksi, "DELETE FROM berita WHERE id = '$id'");
    
    if ($query) {
        if ($foto_hapus != "" && file_exists('../uploads/' . $foto_hapus)) {
            unlink('../uploads/' . $foto_hapus);
        }
        $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Berita berhasil dihapus!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal menghapus berita!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark"><i class="bi bi-newspaper"></i> Manajemen Berita Sekolah</h3>
    <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tulis Berita
    </button>
</div>

<?= $pesan; ?>

<!-- Tabel Data Berita -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="15%" class="text-center">Cover</th>
                        <th>Judul Berita</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query_tampil = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY id DESC");
                    
                    if (mysqli_num_rows($query_tampil) > 0) {
                        while ($data = mysqli_fetch_array($query_tampil)) {
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center">
                            <?php if ($data['foto'] != ""): ?>
                                <img src="../uploads/<?= $data['foto']; ?>" alt="Cover" class="img-thumbnail" style="width: 100px; height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <span class="badge bg-secondary">Tidak ada cover</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold text-primary mb-1"><?= htmlspecialchars($data['judul']); ?></div>
                            <small class="text-muted text-truncate d-inline-block" style="max-width: 300px;">
                                <?= htmlspecialchars(substr($data['isi'], 0, 80)); ?>...
                            </small>
                        </td>
                        <td>
                            <!-- Format Tanggal Indonesia (Misal: 15 Aug 2026) -->
                            <?= date('d M Y', strtotime($data['tanggal'])); ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['id']; ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <a href="berita.php?hapus=<?= $data['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus berita ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- MODAL EDIT DATA -->
                    <div class="modal fade" id="modalEdit<?= $data['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg"> <!-- modal-lg agar pop up lebih lebar -->
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title fw-bold">Edit Berita</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input type="hidden" name="id_berita" value="<?= $data['id']; ?>">
                                        <input type="hidden" name="foto_lama" value="<?= $data['foto']; ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Judul Berita</label>
                                            <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Isi Berita</label>
                                            <textarea name="isi" class="form-control" rows="8" required><?= htmlspecialchars($data['isi']); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Ganti Foto Cover (Opsional)</label>
                                            <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg">
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah cover.</small>
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
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada berita yang diterbitkan.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH DATA -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg"> <!-- modal-lg agar area ngetik lebih lega -->
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Tulis Berita Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Berita</label>
                        <input type="text" name="judul" class="form-control" placeholder="Masukkan judul..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Isi Berita</label>
                        <textarea name="isi" class="form-control" rows="8" placeholder="Ketik isi berita di sini..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Foto Cover (Opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <small class="text-muted">Format: JPG/PNG, Maksimal 2MB. Akan tampil sebagai cover berita.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Publikasikan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
// Panggil footer
include 'includes/footer.php'; 
?>