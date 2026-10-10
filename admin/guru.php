<?php
// Panggil header (sekaligus koneksi database dan proteksi session)
include 'includes/header.php';

$pesan = ''; // Variabel untuk nampung notifikasi sukses/gagal

// ==========================================
// 1. PROSES TAMBAH DATA GURU
// ==========================================
if (isset($_POST['tambah'])) {
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $mapel = mysqli_real_escape_string($koneksi, $_POST['mapel']);
    
    $foto = ""; // Default kosong
    
    // Cek apakah ada file foto yang diupload
    if ($_FILES['foto']['name'] != '') {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $nama_file = $_FILES['foto']['name'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['foto']['size'];
        $file_tmp = $_FILES['foto']['tmp_name'];
        
        // Bikin nama file baru biar gak bentrok (pakai timestamp)
        $nama_file_baru = time() . '-' . $nama_file;
        
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 2048000) { // Maksimal 2MB
                // Pindahkan file ke folder uploads
                move_uploaded_file($file_tmp, '../uploads/' . $nama_file_baru);
                $foto = $nama_file_baru;
            } else {
                $pesan = "<div class='alert alert-danger'>❌ Ukuran file terlalu besar! Maksimal 2MB.</div>";
            }
        } else {
            $pesan = "<div class='alert alert-danger'>❌ Ekstensi file tidak diperbolehkan! Hanya JPG, JPEG, PNG.</div>";
        }
    }

    if ($pesan == '') { // Jika tidak ada error upload
        $query = mysqli_query($koneksi, "INSERT INTO guru (nip, nama, mapel, foto) VALUES ('$nip', '$nama', '$mapel', '$foto')");
        if ($query) {
            $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Data guru berhasil ditambahkan!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        } else {
            $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal menambah data: " . mysqli_error($koneksi) . "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        }
    }
}

// ==========================================
// 2. PROSES EDIT DATA GURU
// ==========================================
if (isset($_POST['edit'])) {
    $id = $_POST['id_guru'];
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $mapel = mysqli_real_escape_string($koneksi, $_POST['mapel']);
    $foto_lama = $_POST['foto_lama'];
    
    $foto_baru = $foto_lama; // Default pakai foto lama
    
    // Jika user mengupload foto baru
    if ($_FILES['foto']['name'] != '') {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $nama_file = $_FILES['foto']['name'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['foto']['size'];
        $file_tmp = $_FILES['foto']['tmp_name'];
        
        $nama_file_baru = time() . '-' . $nama_file;
        
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 2048000) {
                // Hapus foto lama jika ada
                if ($foto_lama != "" && file_exists('../uploads/' . $foto_lama)) {
                    unlink('../uploads/' . $foto_lama);
                }
                // Upload foto baru
                move_uploaded_file($file_tmp, '../uploads/' . $nama_file_baru);
                $foto_baru = $nama_file_baru;
            } else {
                $pesan = "<div class='alert alert-danger'>❌ Ukuran file foto baru terlalu besar! Maksimal 2MB.</div>";
            }
        } else {
            $pesan = "<div class='alert alert-danger'>❌ Ekstensi file tidak diperbolehkan! Hanya JPG, JPEG, PNG.</div>";
        }
    }

    if (!strpos($pesan, '❌')) { // Pastikan tidak ada error upload sebelumnya
        $query = mysqli_query($koneksi, "UPDATE guru SET nip = '$nip', nama = '$nama', mapel = '$mapel', foto = '$foto_baru' WHERE id = '$id'");
        if ($query) {
            $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Data guru berhasil diperbarui!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        } else {
            $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal mengedit data!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        }
    }
}

// ==========================================
// 3. PROSES HAPUS DATA GURU
// ==========================================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Ambil nama foto dari database untuk dihapus fisiknya
    $cek_foto = mysqli_query($koneksi, "SELECT foto FROM guru WHERE id = '$id'");
    $data_foto = mysqli_fetch_assoc($cek_foto);
    $foto_hapus = $data_foto['foto'];
    
    $query = mysqli_query($koneksi, "DELETE FROM guru WHERE id = '$id'");
    
    if ($query) {
        // Hapus file fisik dari folder uploads
        if ($foto_hapus != "" && file_exists('../uploads/' . $foto_hapus)) {
            unlink('../uploads/' . $foto_hapus);
        }
        $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Data guru berhasil dihapus!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal menghapus data!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark"><i class="bi bi-person-badge"></i> Manajemen Data Guru</h3>
    <!-- Tombol untuk memunculkan Modal Tambah -->
    <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Guru
    </button>
</div>

<?= $pesan; ?>

<!-- Tabel Data Guru -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="10%" class="text-center">Foto</th>
                        <th>NIP</th>
                        <th>Nama Guru</th>
                        <th>Mata Pelajaran</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query_tampil = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY nama ASC");
                    
                    if (mysqli_num_rows($query_tampil) > 0) {
                        while ($data = mysqli_fetch_array($query_tampil)) {
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center">
                            <?php if ($data['foto'] != ""): ?>
                                <img src="../uploads/<?= $data['foto']; ?>" alt="Foto" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($data['nama']); ?>&background=random" class="img-thumbnail" style="width: 60px; height: 60px;">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($data['nip']); ?></td>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($data['nama']); ?></td>
                        <td><?= htmlspecialchars($data['mapel']); ?></td>
                        <td class="text-center">
                            <!-- Tombol Edit -->
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['id']; ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <!-- Tombol Hapus -->
                            <a href="guru.php?hapus=<?= $data['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus Bapak/Ibu <?= $data['nama']; ?>?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- MODAL EDIT DATA -->
                    <div class="modal fade" id="modalEdit<?= $data['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title fw-bold">Edit Data Guru</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <!-- WAJIB pakai enctype multipart/form-data kalau ada form upload file -->
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input type="hidden" name="id_guru" value="<?= $data['id']; ?>">
                                        <input type="hidden" name="foto_lama" value="<?= $data['foto']; ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">NIP (Opsional)</label>
                                            <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($data['nip']); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap & Gelar</label>
                                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mata Pelajaran</label>
                                            <input type="text" name="mapel" class="form-control" value="<?= htmlspecialchars($data['mapel']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Ganti Foto (Opsional)</label>
                                            <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg">
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto. Maks 2MB.</small>
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
                    <!-- AKHIR MODAL EDIT -->

                    <?php 
                        }
                    } else { 
                    ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data guru yang ditambahkan.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH DATA -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Tambah Guru Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <!-- WAJIB pakai enctype multipart/form-data kalau ada form upload file -->
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">NIP (Opsional)</label>
                        <input type="text" name="nip" class="form-control" placeholder="Masukkan NIP jika ada">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap & Gelar</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Siti Aminah, S.Kom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <input type="text" name="mapel" class="form-control" placeholder="Contoh: Matematika" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Guru (Opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <small class="text-muted">Format: JPG/PNG, Maksimal 2MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
// Panggil footer
include 'includes/footer.php'; 
?>