<?php
// Panggil header
include 'includes/header.php';

$pesan = '';

// ==========================================
// MENGAMBIL DATA KELAS UNTUK DROPDOWN
// ==========================================
// Kita ambil data kelas sekali di sini, simpan ke array biar bisa dipakai berulang di form Tambah & Edit
$query_kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
$list_kelas = [];
while ($row = mysqli_fetch_assoc($query_kelas)) {
    $list_kelas[] = $row;
}

// ==========================================
// 1. PROSES TAMBAH DATA SISWA
// ==========================================
if (isset($_POST['tambah'])) {
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $id_kelas = $_POST['id_kelas']; // Berasal dari dropdown
    
    $foto = ""; 
    
    if ($_FILES['foto']['name'] != '') {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $nama_file = $_FILES['foto']['name'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['foto']['size'];
        $file_tmp = $_FILES['foto']['tmp_name'];
        
        $nama_file_baru = 'siswa_' . time() . '-' . $nama_file;
        
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 2048000) {
                move_uploaded_file($file_tmp, '../uploads/' . $nama_file_baru);
                $foto = $nama_file_baru;
            } else {
                $pesan = "<div class='alert alert-danger'>❌ Ukuran file terlalu besar! Maksimal 2MB.</div>";
            }
        } else {
            $pesan = "<div class='alert alert-danger'>❌ Ekstensi file tidak diperbolehkan!</div>";
        }
    }

    if (empty($pesan)) {
        $query = mysqli_query($koneksi, "INSERT INTO siswa (nis, nama, id_kelas, foto) VALUES ('$nis', '$nama', '$id_kelas', '$foto')");
        if ($query) {
            $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Data siswa berhasil ditambahkan!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        } else {
            $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal menambah data: " . mysqli_error($koneksi) . "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        }
    }
}

// ==========================================
// 2. PROSES EDIT DATA SISWA
// ==========================================
if (isset($_POST['edit'])) {
    $id = $_POST['id_siswa'];
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $id_kelas = $_POST['id_kelas'];
    $foto_lama = $_POST['foto_lama'];
    
    $foto_baru = $foto_lama; 
    
    if ($_FILES['foto']['name'] != '') {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
        $nama_file = $_FILES['foto']['name'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['foto']['size'];
        $file_tmp = $_FILES['foto']['tmp_name'];
        
        $nama_file_baru = 'siswa_' . time() . '-' . $nama_file;
        
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 2048000) {
                if ($foto_lama != "" && file_exists('../uploads/' . $foto_lama)) {
                    unlink('../uploads/' . $foto_lama);
                }
                move_uploaded_file($file_tmp, '../uploads/' . $nama_file_baru);
                $foto_baru = $nama_file_baru;
            } else {
                $pesan = "<div class='alert alert-danger'>❌ Ukuran file foto baru terlalu besar! Maksimal 2MB.</div>";
            }
        } else {
            $pesan = "<div class='alert alert-danger'>❌ Ekstensi file tidak diperbolehkan!</div>";
        }
    }

    if (empty($pesan) || !strpos($pesan, '❌')) { 
        $query = mysqli_query($koneksi, "UPDATE siswa SET nis = '$nis', nama = '$nama', id_kelas = '$id_kelas', foto = '$foto_baru' WHERE id = '$id'");
        if ($query) {
            $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Data siswa berhasil diperbarui!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        } else {
            $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal mengedit data!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        }
    }
}

// ==========================================
// 3. PROSES HAPUS DATA SISWA
// ==========================================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    $cek_foto = mysqli_query($koneksi, "SELECT foto FROM siswa WHERE id = '$id'");
    $data_foto = mysqli_fetch_assoc($cek_foto);
    $foto_hapus = $data_foto['foto'];
    
    $query = mysqli_query($koneksi, "DELETE FROM siswa WHERE id = '$id'");
    
    if ($query) {
        if ($foto_hapus != "" && file_exists('../uploads/' . $foto_hapus)) {
            unlink('../uploads/' . $foto_hapus);
        }
        $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Data siswa berhasil dihapus!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal menghapus data!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark"><i class="bi bi-people"></i> Manajemen Data Siswa</h3>
    <!-- Jika data kelas masih kosong, matikan tombol tambah -->
    <?php if (count($list_kelas) > 0): ?>
        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg"></i> Tambah Siswa
        </button>
    <?php else: ?>
        <button type="button" class="btn btn-secondary fw-bold" disabled title="Buat data kelas terlebih dahulu!">
            <i class="bi bi-plus-lg"></i> Tambah Siswa
        </button>
    <?php endif; ?>
</div>

<?= $pesan; ?>

<?php if (count($list_kelas) == 0): ?>
    <div class="alert alert-warning shadow-sm">
        <i class="bi bi-exclamation-triangle-fill"></i> <b>Perhatian:</b> Anda belum memiliki Data Kelas. Silakan tambahkan kelas terlebih dahulu di menu <b>Data Kelas</b> sebelum menambahkan siswa.
    </div>
<?php endif; ?>

<!-- Tabel Data Siswa -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="10%" class="text-center">Foto</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Query pakai LEFT JOIN biar nama_kelas tampil dari tabel kelas
                    $query_tampil = mysqli_query($koneksi, "SELECT siswa.*, kelas.nama_kelas FROM siswa LEFT JOIN kelas ON siswa.id_kelas = kelas.id ORDER BY siswa.nama ASC");
                    
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
                        <td><?= htmlspecialchars($data['nis']); ?></td>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($data['nama']); ?></td>
                        <td>
                            <!-- Jika kelasnya dihapus, tampilkan peringatan -->
                            <?php if ($data['nama_kelas']): ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($data['nama_kelas']); ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger">Kelas Tidak Ditemukan</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['id']; ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <a href="siswa.php?hapus=<?= $data['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus siswa <?= $data['nama']; ?>?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- MODAL EDIT DATA -->
                    <div class="modal fade" id="modalEdit<?= $data['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title fw-bold">Edit Data Siswa</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input type="hidden" name="id_siswa" value="<?= $data['id']; ?>">
                                        <input type="hidden" name="foto_lama" value="<?= $data['foto']; ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">NIS</label>
                                            <input type="text" name="nis" class="form-control" value="<?= htmlspecialchars($data['nis']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Pilih Kelas</label>
                                            <select name="id_kelas" class="form-select" required>
                                                <option value="">-- Pilih Kelas --</option>
                                                <?php foreach ($list_kelas as $kelas): ?>
                                                    <!-- Menandai kelas yang saat ini dipilih siswa -->
                                                    <option value="<?= $kelas['id']; ?>" <?= ($data['id_kelas'] == $kelas['id']) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($kelas['nama_kelas']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Ganti Foto (Opsional)</label>
                                            <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg">
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
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data siswa yang ditambahkan.</td>
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
                <h5 class="modal-title fw-bold">Tambah Siswa Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">NIS (Nomor Induk Siswa)</label>
                        <input type="text" name="nis" class="form-control" placeholder="Contoh: 1029384" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Andi Permana" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Kelas</label>
                        <select name="id_kelas" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($list_kelas as $kelas): ?>
                                <option value="<?= $kelas['id']; ?>"><?= htmlspecialchars($kelas['nama_kelas']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Siswa (Opsional)</label>
                        <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg">
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