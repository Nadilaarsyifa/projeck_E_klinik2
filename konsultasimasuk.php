<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'proses/connect.php';

if (!isset($_SESSION['username_eklinik'])) {
    echo "<b>Anda belum login!</b>";
    exit;
}

$id_petugas = $_SESSION['username_eklinik'];

$sql = "SELECT k.*, tl.tgl_tindak_lanjut, tl.diagnosa, tl.resep_obat, tl.saran_perawatan
        FROM konsultasi k
        LEFT JOIN tindak_lanjut tl ON k.id_konsultasi = tl.id_konsultasi
        WHERE k.id_petugas = '$id_petugas'
        ORDER BY k.tgl_konsultasi DESC";
$result = mysqli_query($conn, $sql);
?>

<style>
/* Card Header */
.card-header {
    font-size: 1.5rem;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(90deg, #28a745, #218838);
    text-align: center;
    border-bottom: 2px solid #1e7e34;
}

/* Table Header */
.table thead th {
    background-color: #28a745;
    color: #fff;
    text-align: center;
    vertical-align: middle;
}

/* Table Body */
.table tbody td { vertical-align: middle; }

/* Row hover */
.table tbody tr:hover { background-color: #e6f2e6; }

/* Badge */
.badge.text-bg-warning { background-color: #ffc107 !important; color: #212529; font-weight: 500; }
.badge.text-bg-success { background-color: #28a745 !important; font-weight: 500; }

/* Buttons */
.btn-primary { background-color: #0d6efd; border: none; }
.btn-primary:hover { background-color: #0b5ed7; }
.btn-info { background-color: #17a2b8; border: none; }
.btn-info:hover { background-color: #138496; }

/* Modal Headers */
.modal-header.bg-success, .modal-header.bg-info { color: #fff; font-weight: 600; }

/* Scrollable card body */
.card-body { max-height: 75vh; overflow-y: auto; }

/* Modal textarea */
.modal-body textarea { resize: none; }

/* Buttons spacing */
.btn + .btn { margin-left: 5px; }
</style>

<div class="col-lg-9 mt-2">
    <div class="card shadow">
        <div class="card-header">
            <i class="bi bi-chat-dots me-2"></i> KONSULTASI MASUK
        </div>

    <div class="card-body">

        <table class="table table-bordered table-striped text-center">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>NIM</th>
                    <th>Keluhan Utama</th>
                    <th>Lama Keluhan</th>
                    <th>Riwayat Pengobatan</th>
                    <th>Catatan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['tgl_konsultasi'] ?></td>
                    <td><?= $row['nim'] ?></td>
                    <td><?= $row['keluhan_utama'] ?></td>
                    <td><?= $row['lama_keluhan'] ?></td>
                    <td><?= $row['riwayat_pengobatan_sendiri'] ?></td>
                    <td><?= $row['catatan_mahasiswa'] ?></td>

                    <td>
                        <?php if (empty($row['tgl_tindak_lanjut'])): ?>
                            <span class="badge text-bg-warning px-3 py-2">Masuk</span>
                        <?php else: ?>
                            <span class="badge text-bg-success px-3 py-2">Selesai</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if (empty($row['tgl_tindak_lanjut'])): ?>
                            <button class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#balasModal"
                                    data-id="<?= $row['id_konsultasi'] ?>"
                                    data-nim="<?= $row['nim'] ?>">
                                <i class="bi bi-reply-fill"></i> Balas
                            </button>
                        <?php else: ?>
                            <button class="btn btn-info btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailModal"
                                    data-nim="<?= $row['nim'] ?>"
                                    data-keluhan="<?= $row['keluhan_utama'] ?>"
                                    data-lama="<?= $row['lama_keluhan'] ?>"
                                    data-riwayat="<?= $row['riwayat_pengobatan_sendiri'] ?>"
                                    data-catatan="<?= $row['catatan_mahasiswa'] ?>"
                                    data-tgl="<?= $row['tgl_tindak_lanjut'] ?>"
                                    data-diagnosa="<?= $row['diagnosa'] ?>"
                                    data-resep="<?= $row['resep_obat'] ?>"
                                    data-saran="<?= $row['saran_perawatan'] ?>">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
</div>

</div>

<!-- MODAL BALAS -->

<div class="modal fade" id="balasModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="proses/proses_balas.php" method="POST">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Form Balasan Konsultasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_konsultasi" id="modal_id_konsultasi">
                    <input type="hidden" name="nim" id="modal_nim">
                    <input type="hidden" name="id_petugas" value="<?= $_SESSION['username_eklinik'] ?>">

                <div class="mb-3">
                    <label>Diagnosa</label>
                    <textarea class="form-control" name="diagnosa" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label>Resep Obat</label>
                    <textarea class="form-control" name="resep_obat" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label>Saran Perawatan</label>
                    <textarea class="form-control" name="saran_perawatan" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Kirim Balasan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

</div>

<!-- MODAL DETAIL -->

<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Detail Konsultasi & Balasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr><th>NIM</th><td id="d_nim"></td></tr>
                    <tr><th>Keluhan Utama</th><td id="d_keluhan"></td></tr>
                    <tr><th>Lama Keluhan</th><td id="d_lama"></td></tr>
                    <tr><th>Riwayat Pengobatan</th><td id="d_riwayat"></td></tr>
                    <tr><th>Catatan Mahasiswa</th><td id="d_catatan"></td></tr>
                    <tr class="table-success"><th>Tanggal Tindak Lanjut</th><td id="d_tgl"></td></tr>
                    <tr class="table-success"><th>Diagnosa</th><td id="d_diagnosa"></td></tr>
                    <tr class="table-success"><th>Resep Obat</th><td id="d_resep"></td></tr>
                    <tr class="table-success"><th>Saran Perawatan</th><td id="d_saran"></td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
// Modal Balas
var balasModal = document.getElementById('balasModal');
balasModal.addEventListener('show.bs.modal', function(event) {
    var btn = event.relatedTarget;
    document.getElementById('modal_id_konsultasi').value = btn.getAttribute('data-id');
    document.getElementById('modal_nim').value = btn.getAttribute('data-nim');
});

// Modal Detail
var detailModal = document.getElementById('detailModal');
detailModal.addEventListener('show.bs.modal', function(event) {
    var b = event.relatedTarget;
    document.getElementById('d_nim').innerText = b.getAttribute('data-nim');
    document.getElementById('d_keluhan').innerText = b.getAttribute('data-keluhan');
    document.getElementById('d_lama').innerText = b.getAttribute('data-lama');
    document.getElementById('d_riwayat').innerText = b.getAttribute('data-riwayat');
    document.getElementById('d_catatan').innerText = b.getAttribute('data-catatan');
    document.getElementById('d_tgl').innerText = b.getAttribute('data-tgl');
    document.getElementById('d_diagnosa').innerText = b.getAttribute('data-diagnosa');
    document.getElementById('d_resep').innerText = b.getAttribute('data-resep');
    document.getElementById('d_saran').innerText = b.getAttribute('data-saran');
});
</script>
