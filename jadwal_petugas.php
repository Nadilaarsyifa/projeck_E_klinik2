<?php
include "proses/connect.php";

// Notifikasi
$notif = '';
if (isset($_GET['notif'])) {
    $messages = [
        'tambah_berhasil' => ['Berhasil!', 'Jadwal berhasil ditambahkan!', 'success'],
        'edit_berhasil' => ['Berhasil!', 'Jadwal berhasil diupdate!', 'success'],
        'hapus_berhasil' => ['Berhasil!', 'Jadwal berhasil dihapus!', 'success'],
        'bentrok' => ['Perhatian!', 'Jadwal bentrok! Petugas sudah ada jadwal di waktu tersebut.', 'danger'],
        'waktu_invalid' => ['Perhatian!', 'Jam mulai harus lebih awal dari jam selesai!', 'danger']
    ];
    
    $msg = $messages[$_GET['notif']] ?? ['Error!', 'Terjadi kesalahan!', 'danger'];
    $notif = "<div class='alert alert-{$msg[2]} alert-dismissible fade show' role='alert'>
                <strong>{$msg[0]}</strong> {$msg[1]}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
}

// Query jadwal
$query = mysqli_query($conn, "
    SELECT j.*, pk.nama as nama_petugas, pk.spesialis
    FROM jadwal_petugas_klinik j
    INNER JOIN petugas_klinik pk ON j.id_petugas = pk.id_petugas
    ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'), j.jam_mulai
");

// Query petugas
$query_petugas = mysqli_query($conn, "SELECT id_petugas, nama, spesialis FROM petugas_klinik ORDER BY nama");
$petugas_list = mysqli_fetch_all($query_petugas, MYSQLI_ASSOC);

$hari_options = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
$day_colors = [
    'Senin' => 'primary', 'Selasa' => 'success', 'Rabu' => 'warning',
    'Kamis' => 'info', 'Jumat' => 'danger', 'Sabtu' => 'secondary', 'Minggu' => 'dark'
];
?>

<style>
.card { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.card-header { background: linear-gradient(135deg, #28a745 0%, #7aa784ff 100%); color: white; }
.table td, .table th { vertical-align: middle; }
.day-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.875rem; }
.action-btn { width: 35px; height: 35px; border-radius: 50%; padding: 0; }
.search-box { max-width: 400px; }
</style>

<div class="col-lg-9 mt-2">
    <?= $notif ?>
    
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Jadwal Petugas Klinik</h5>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#ModalTambah">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Search -->
            <div class="mb-3">
                <input type="text" class="form-control search-box" id="searchJadwal" 
                       placeholder="Cari petugas atau hari...">
            </div>

            <?php if (mysqli_num_rows($query) == 0): ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Belum ada jadwal</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="30%">Petugas</th>
                                <th width="15%">Hari</th>
                                <th width="15%">Jam Mulai</th>
                                <th width="15%">Jam Selesai</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($query)): 
                                $badge_color = $day_colors[$row['hari']] ?? 'secondary';
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($row['nama_petugas']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($row['spesialis']) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $badge_color ?> day-badge">
                                        <?= $row['hari'] ?>
                                    </span>
                                </td>
                                <td><?= substr($row['jam_mulai'], 0, 5) ?></td>
                                <td><?= substr($row['jam_selesai'], 0, 5) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm action-btn me-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#ModalEdit<?= $row['id_jadwal'] ?>"
                                            title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm action-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#ModalHapus<?= $row['id_jadwal'] ?>"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="ModalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="proses/proses_tambah_jadwal.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Petugas</label>
                        <select class="form-select" name="id_petugas" required>
                            <option value="">-- Pilih Petugas --</option>
                            <?php foreach ($petugas_list as $p): ?>
                                <option value="<?= $p['id_petugas'] ?>">
                                    <?= htmlspecialchars($p['nama']) ?> - <?= htmlspecialchars($p['spesialis']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hari</label>
                        <select class="form-select" name="hari" required>
                            <option value="">-- Pilih Hari --</option>
                            <?php foreach ($hari_options as $hari): ?>
                                <option value="<?= $hari ?>"><?= $hari ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" name="jam_mulai" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" name="jam_selesai" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" name="tambah_jadwal">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
mysqli_data_seek($query, 0);
while ($row = mysqli_fetch_assoc($query)):
?>
<!-- Modal Edit -->
<div class="modal fade" id="ModalEdit<?= $row['id_jadwal'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="proses/proses_edit_jadwal.php" method="POST">
                <input type="hidden" name="id_jadwal" value="<?= $row['id_jadwal'] ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Petugas</label>
                        <select class="form-select" name="id_petugas" required>
                            <?php foreach ($petugas_list as $p): ?>
                                <option value="<?= $p['id_petugas'] ?>" 
                                        <?= $p['id_petugas'] == $row['id_petugas'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['nama']) ?> - <?= htmlspecialchars($p['spesialis']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hari</label>
                        <select class="form-select" name="hari" required>
                            <?php foreach ($hari_options as $hari): ?>
                                <option value="<?= $hari ?>" <?= $hari == $row['hari'] ? 'selected' : '' ?>>
                                    <?= $hari ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" class="form-control" name="jam_mulai" 
                                   value="<?= substr($row['jam_mulai'], 0, 5) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" class="form-control" name="jam_selesai" 
                                   value="<?= substr($row['jam_selesai'], 0, 5) ?>" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning" name="edit_jadwal">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="ModalHapus<?= $row['id_jadwal'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus jadwal ini?</p>
                <div class="alert alert-warning">
                    <strong>Petugas:</strong> <?= htmlspecialchars($row['nama_petugas']) ?><br>
                    <strong>Hari:</strong> <?= $row['hari'] ?><br>
                    <strong>Waktu:</strong> <?= substr($row['jam_mulai'], 0, 5) ?> - <?= substr($row['jam_selesai'], 0, 5) ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="proses/proses_hapus_jadwal.php?id=<?= $row['id_jadwal'] ?>" 
                   class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>

<script>
// Search functionality
document.getElementById('searchJadwal')?.addEventListener('keyup', function() {
    const term = this.value.toLowerCase();
    document.querySelectorAll('tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
    });
});

// Auto-hide alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>