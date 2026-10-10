<?php 
require 'config/koneksi.php'; 
include 'includes/header.php'; 
?>

<div class="container mt-5 mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-success">📅 Jadwal Pelajaran</h2>
        <p class="text-muted">Informasi jadwal kegiatan belajar mengajar SMAN 1 Coding</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="bg-success text-white">
                        <tr>
                            <th width="15%">Hari</th>
                            <th width="15%">Waktu</th>
                            <th width="15%">Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th width="25%">Guru Pengajar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Ambil data jadwal diurutkan berdasarkan hari dan jam mulai
                        $sql = "SELECT jadwal.*, kelas.nama_kelas, guru.nama AS nama_guru 
                                FROM jadwal 
                                LEFT JOIN kelas ON jadwal.id_kelas = kelas.id 
                                LEFT JOIN guru ON jadwal.id_guru = guru.id 
                                ORDER BY FIELD(jadwal.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jadwal.jam_mulai ASC";
                        $query = mysqli_query($koneksi, $sql);
                        
                        if (mysqli_num_rows($query) > 0) {
                            while ($data = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td class="fw-bold text-success"><?= htmlspecialchars($data['hari']); ?></td>
                            <td>
                                <span class="badge bg-dark px-2 py-1">
                                    <?= date('H:i', strtotime($data['jam_mulai'])) ?> - <?= date('H:i', strtotime($data['jam_selesai'])) ?>
                                </span>
                            </td>
                            <td class="fw-bold">
                                <?= $data['nama_kelas'] ? htmlspecialchars($data['nama_kelas']) : '-'; ?>
                            </td>
                            <td class="fw-bold text-primary"><?= htmlspecialchars($data['mapel']); ?></td>
                            <td class="text-muted">
                                <i class="bi bi-person-video3 me-1"></i> <?= $data['nama_guru'] ? htmlspecialchars($data['nama_guru']) : '-'; ?>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">Jadwal pelajaran belum tersedia.</td>
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