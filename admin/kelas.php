<?php
// Panggil header (sekaligus koneksi database dan proteksi session)
include 'includes/header.php';

$pesan = ''; // Variabel untuk nampung notifikasi sukses/gagal

// ==========================================
// 1. PROSES TAMBAH DATA
// ==========================================
if (isset($_POST['tambah'])) {
    $nama_kelas = mysqli_real_escape_string($koneksi, $_POST['nama_kelas']);
    $wali_kelas = mysqli_real_escape_string($koneksi, $_POST['wali_kelas']);

    $query = mysqli_query($koneksi, "INSERT INTO kelas (nama_kelas, wali_kelas) VALUES ('$nama_kelas', '$wali_kelas')");
    if ($query) {
        $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Data kelas berhasil ditambahkan!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal menambah data: " . mysqli_error($koneksi) . "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}

// ==========================================
// 2. PROSES EDIT DATA
// ==========================================
if (isset($_POST['edit'])) {
    $id = $_POST['id_kelas'];
    $nama_kelas = mysqli_real_escape_string($koneksi, $_POST['nama_kelas']);
    $wali_kelas = mysqli_real_escape_string($koneksi, $_POST['wali_kelas']);

    $query = mysqli_query($koneksi, "UPDATE kelas SET nama_kelas = '$nama_kelas', wali_kelas = '$wali_kelas' WHERE id = '$id'");
    if ($query) {
        $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Data kelas berhasil diperbarui!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal mengedit data!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}

// ==========================================
// 3. PROSES HAPUS DATA
// ==========================================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = mysqli_query($koneksi, "DELETE FROM kelas WHERE id = '$id'");
    if ($query) {
        $pesan = "<div class='alert alert-success alert-dismissible fade show'>✅ Data kelas berhasil dihapus!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $pesan = "<div class='alert alert-danger alert-dismissible fade show'>❌ Gagal menghapus data!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark"><i class="bi bi-building"></i> Manajemen Data Kelas</h3>
    <!-- Tombol untuk memunculkan Modal Tambah -->
    <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Kelas
    </button>
</div>

<?= $pesan; ?> <!-- Tampilkan pesan sukses/error di sini -->

<!-- Tabel Data Kelas -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Nama Kelas</th>
                        <th>Wali Kelas</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query_tampil = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
                    
                    if (mysqli_num_rows($query_tampil) > 0) {
                        while ($data = mysqli_fetch_array($query_tampil)) {
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="fw-bold text-primary"><?= htmlspecialchars($data['nama_kelas']); ?></td>
                        <td><?= htmlspecialchars($data['wali_kelas']); ?></td>
                        <td class="text-center">
                            <!-- Tombol Edit -->
                            <button href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $data['id']; ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <!-- Tombol Hapus -->
                            <a href="kelas.php?hapus=<?= $data['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kelas <?= $data['nama_kelas']; ?>? Data siswa yang terkait mungkin akan terpengaruh.')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- MODAL EDIT DATA (Berada di dalam perulangan while agar id-nya spesifik) -->
                    <div class="modal fade" id="modalEdit<?= $data['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-warning">
                                    <h5 class="modal-title fw-bold">Edit Data Kelas</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="" method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="id_kelas" value="<?= $data['id']; ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Nama Kelas</label>
                                            <input type="text" name="nama_kelas" class="form-control" value="<?= htmlspecialchars($data['nama_kelas']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Wali Kelas</label>
                                            <input type="text" name="wali_kelas" class="form-control" value="<?= htmlspecialchars($data['wali_kelas']); ?>" required>
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
                        } // Akhir while
                    } else { 
                    ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada data kelas yang ditambahkan.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH DATA (Berada di luar perulangan karena ini form kosong) -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Tambah Kelas Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: X IPA 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Wali Kelas</label>
                        <input type="text" name="wali_kelas" class="form-control" placeholder="Contoh: Budi Santoso, S.Pd" required>
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