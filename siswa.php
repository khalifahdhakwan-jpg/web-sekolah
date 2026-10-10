<?php 
require 'config/koneksi.php'; 
include 'includes/header.php'; 
?>

<div class="container mt-5 mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-warning text-dark">👨‍🎓 Direktori Siswa</h2>
        <p class="text-muted">Daftar siswa-siswi yang menempuh pendidikan di SMAN 1 Coding</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="10%" class="text-center">Foto</th>
                            <th width="15%">NIS</th>
                            <th>Nama Siswa</th>
                            <th width="20%">Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($koneksi, "SELECT siswa.*, kelas.nama_kelas FROM siswa LEFT JOIN kelas ON siswa.id_kelas = kelas.id ORDER BY kelas.nama_kelas ASC, siswa.nama ASC");
                        
                        if (mysqli_num_rows($query) > 0) {
                            while ($data = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td class="text-center fw-bold text-muted"><?= $no++; ?></td>
                            <td class="text-center">
                                <?php if ($data['foto'] != ""): ?>
                                    <img src="uploads/<?= $data['foto']; ?>" alt="Foto" class="rounded-circle border" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($data['nama']); ?>&background=random" class="rounded-circle border" style="width: 50px; height: 50px;">
                                <?php endif; ?>
                            </td>
                            <td class="font-monospace"><?= htmlspecialchars($data['nis']); ?></td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($data['nama']); ?></td>
                            <td>
                                <span class="badge bg-primary px-3 py-2 rounded-pill">
                                    <?= $data['nama_kelas'] ? htmlspecialchars($data['nama_kelas']) : 'Belum Ada Kelas'; ?>
                                </span>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada data siswa.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>