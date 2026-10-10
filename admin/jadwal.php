<?php
// Panggil header
include 'includes/header.php';

$pesan = '';

// ==========================================
// MENGAMBIL DATA KELAS & GURU UNTUK DROPDOWN
// ==========================================
// Ambil data kelas
$query_kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
$list_kelas = [];
while ($row = mysqli_fetch_assoc($query_kelas)) {
    $list_kelas[] = $row;
}

// Ambil data guru
$query_guru = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY nama ASC");
$list_guru = [];
while ($row = mysqli_fetch_assoc($query_guru)) {
    $list_guru[] = $row;
}

// ==========================================
// 1. PROSES TAMBAH DATA JADWAL
// ==========================================
if (isset($_POST['tambah'])) {
    $hari = mysqli_real_escape_string($koneksi, $_POST['hari']);
    $jam_mulai = mysqli_real_escape_string($koneksi, $_POST['jam_mulai']);
    $jam_selesai = mysqli_real_escape_string($koneksi, $_POST['jam_selesai']);
    $id_kelas = $_POST['id_kelas'];
    $id_guru = $_POST['id_guru'];
    $mapel = mysqli_real_escape_string($koneksi, $_POST['mapel']);

    $query = mysqli_query($koneksi, "INSERT INTO jadwal (hari, jam_mulai, jam_selesai, id_kelas, id_guru, mapel) 
                                     VALUES ('$hari', '$jam_mulai', '$jam_selesai', '$id_kelas', '$id_guru', '$mapel')");
    if ($query) {
        $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Jadwal berhasil ditambahkan!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal menambah jadwal: " . mysqli_error($koneksi) . "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}

// ==========================================
// 2. PROSES EDIT DATA JADWAL
// ==========================================
if (isset($_POST['edit'])) {
    $id = $_POST['id_jadwal'];
    $hari = mysqli_real_escape_string($koneksi, $_POST['hari']);
    $jam_mulai = mysqli_real_escape_string($koneksi, $_POST['jam_mulai']);
    $jam_selesai = mysqli_real_escape_string($koneksi, $_POST['jam_selesai']);
    $id_kelas = $_POST['id_kelas'];
    $id_guru = $_POST['id_guru'];
    $mapel = mysqli_real_escape_string($koneksi, $_POST['mapel']);

    $query = mysqli_query($koneksi, "UPDATE jadwal SET 
                                     hari = '$hari', 
                                     jam_mulai = '$jam_mulai', 
                                     jam_selesai = '$jam_selesai', 
                                     id_kelas = '$id_kelas', 
                                     id_guru = '$id_guru', 
                                     mapel = '$mapel' 
                                     WHERE id = '$id'");
    if ($query) {
        $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Jadwal berhasil diperbarui!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal mengedit jadwal!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}

// ==========================================
// 3. PROSES HAPUS DATA JADWAL
// ==========================================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = mysqli_query($koneksi, "DELETE FROM jadwal WHERE id = '$id'");
    
    if ($query) {
        $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Jadwal berhasil dihapus!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal menghapus jadwal!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark"><i class="bi bi-calendar3"></i> Manajemen Jadwal Pelajaran</h3>
    
    <!-- Cek apakah data kelas dan guru sudah ada -->
    <?php if (count($list_kelas) > 0 && count($list_guru) > 0): ?>
        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg"></i> Tambah Jadwal
        </button>
    <?php else: ?>
        <button type="button" class="btn btn-secondary fw-bold" disabled title="Pastikan Data Kelas & Guru sudah diisi!">
            <i class="bi bi-plus-lg"></i> Tambah Jadwal
        </button>
    <?php endif; ?>
</div>

<?= $pesan; ?>

<?php if (count($list_kelas) == 0 || count($list_guru) == 0): ?>
    <div class="alert alert-warning shadow-sm">
        <i class="bi bi-exclamation-triangle-fill"></i> <b>Perhatian:</b> Anda harus memiliki minimal 1 <b>Data Kelas</b> dan 1 <b>Data Guru</b> sebelum dapat membuat Jadwal Pelajaran.
    </div>
<?php endif; ?>

<!-- Tabel Data Jadwal -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Hari</th>
                        <th>Waktu</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengajar</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Query JOIN 3 Tabel & Mengurutkan berdasarkan Hari (Senin-Jumat) dan Jam Mulai
                    $sql_tampil = "SELECT jadwal.*, kelas.nama_kelas, guru.nama AS nama_guru 
                                   FROM jadwal 
                                   LEFT JOIN kelas ON jadwal.id_kelas = kelas.id 
                                   LEFT JOIN guru ON jadwal.id_guru = guru.id 
                                   ORDER BY FIELD(jadwal.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jadwal.jam_mulai ASC";
                    $query_tampil = mysqli_query($koneksi, $sql_tampil);
                    
                    if (mysqli_num_rows($query_tampil) > 0) {
                        while ($data = mysqli_fetch_array($query_tampil)) {
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td class="fw-bold text-success"><?= htmlspecialchars($data['hari']); ?></td>
                        <td>
                            <!-- Format waktu membuang detik yang gak perlu -->
                            <span class="badge bg-dark">
                                <?= date('H:i', strtotime($data['jam_mulai'])) ?> - <?= date('H:i', strtotime($data['jam_selesai'])) ?>
                            </span>
                        </td>
                        <td class="fw-bold">
                            <?= $data['nama_kelas'] ? htmlspecialchars($data['nama_kelas']) : '<span class="text-danger">Kelas Dihapus</span>'; ?>
                        </td>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($data['mapel']); ?></td>
                        <td>
                            <?= $data['nama_guru'] ? htmlspecialchars($data['nama_guru']) : '<span class="text-danger">Guru Dihapus</span>'; ?>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['id']; ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <a href="jadwal.php?hapus=<?= $data['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- MODAL EDIT DATA -->
                    <div class="modal fade text-start" id="modalEdit<?= $data['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title fw-bold">Edit Jadwal Pelajaran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="" method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="id_jadwal" value="<?= $data['id']; ?>">
                                        
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Hari</label>
                                                <select name="hari" class="form-select" required>
                                                    <?php 
                                                    $hari_arr = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                                    foreach($hari_arr as $h) {
                                                        $selected = ($data['hari'] == $h) ? 'selected' : '';
                                                        echo "<option value='$h' $selected>$h</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Jam Mulai</label>
                                                <input type="time" name="jam_mulai" class="form-control" value="<?= $data['jam_mulai']; ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Jam Selesai</label>
                                                <input type="time" name="jam_selesai" class="form-control" value="<?= $data['jam_selesai']; ?>" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Pilih Kelas</label>
                                            <select name="id_kelas" class="form-select" required>
                                                <option value="">-- Pilih Kelas --</option>
                                                <?php foreach ($list_kelas as $kelas): ?>
                                                    <option value="<?= $kelas['id']; ?>" <?= ($data['id_kelas'] == $kelas['id']) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($kelas['nama_kelas']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Pilih Guru</label>
                                            <select name="id_guru" class="form-select" required>
                                                <option value="">-- Pilih Guru --</option>
                                                <?php foreach ($list_guru as $guru): ?>
                                                    <option value="<?= $guru['id']; ?>" <?= ($data['id_guru'] == $guru['id']) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($guru['nama']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mata Pelajaran</label>
                                            <input type="text" name="mapel" class="form-control" value="<?= htmlspecialchars($data['mapel']); ?>" required>
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
                            <td colspan="7" class="text-center text-muted py-4">Belum ada jadwal yang ditambahkan.</td>
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
                <h5 class="modal-title fw-bold">Tambah Jadwal Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Hari</label>
                            <select name="hari" class="form-select" required>
                                <option value="">-- Pilih Hari --</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control" required>
                        </div>
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
                        <label class="form-label">Pilih Guru</label>
                        <select name="id_guru" class="form-select" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php foreach ($list_guru as $guru): ?>
                                <option value="<?= $guru['id']; ?>"><?= htmlspecialchars($guru['nama']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <input type="text" name="mapel" class="form-control" placeholder="Contoh: Matematika Wajib" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
// Panggil footer
include 'includes/footer.php'; 
?>