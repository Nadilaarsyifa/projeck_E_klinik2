<?php
// Ambil data dari database
$nim = $_SESSION['username_eklinik']; // NIM mahasiswa yang login
$query = "SELECT k.*, 
                 p.nama as nama_petugas, 
                 p.spesialis,
                 t.id_tindak_lanjut,
                 t.diagnosa,
                 t.resep_obat,
                 t.saran_perawatan,
                 t.tgl_tindak_lanjut
          FROM konsultasi k 
          LEFT JOIN petugas_klinik p ON k.id_petugas = p.id_petugas
          LEFT JOIN tindak_lanjut t ON k.id_konsultasi = t.id_konsultasi
          WHERE k.nim = '$nim' 
          ORDER BY k.tgl_konsultasi DESC";

$result = mysqli_query($conn, $query);
?>

<!-- CSS Khusus untuk Halaman Riwayat Konsultasi -->
<style>
/* Card Styling dengan Gradient - Sama seperti Data Medis */
.card-riwayat { 
    border-radius:1.5rem; 
    box-shadow:0 15px 35px rgba(0,0,0,0.12); 
    border:none; 
    margin-bottom:2rem; 
    overflow:hidden;
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from { opacity:0; transform:translateY(30px); }
    to { opacity:1; transform:translateY(0); }
}

.card-riwayat .card-header { 
    background: linear-gradient(135deg, #0b9d4f 0%, #198754 100%);
    color:#fff; 
    font-weight:700; 
    font-size:1.4rem; 
    padding:1.5rem 2rem;
    border:none;
    box-shadow: 0 4px 15px rgba(25,135,84,0.3);
}

.card-riwayat .card-header i {
    font-size:1.6rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.card-riwayat .card-body {
    padding:2rem 2.5rem;
    background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);
}

/* Table Styling dengan Efek Modern */
.table-riwayat {
    border-radius:1rem;
    overflow:hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from { opacity:0; }
    to { opacity:1; }
}

.table-riwayat thead {
    background: linear-gradient(135deg, #0b9d4f 0%, #198754 100%);
    color:#fff;
}

.table-riwayat thead th {
    padding:1.2rem 1rem;
    font-weight:600;
    font-size:0.95rem;
    border:none;
    text-transform:uppercase;
    letter-spacing:0.5px;
}

.table-riwayat tbody tr {
    transition: all 0.3s ease;
    border-bottom:1px solid #e9ecef;
}

.table-riwayat tbody tr:hover {
    background: linear-gradient(to right, rgba(25,135,84,0.05) 0%, rgba(25,135,84,0.02) 100%);
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(25,135,84,0.1);
}

.table-riwayat tbody td {
    padding:1rem;
    vertical-align:middle;
    font-size:0.95rem;
}

/* Badge Status dengan Gradient */
.badge-custom {
    padding:0.5rem 1rem;
    border-radius:50rem;
    font-weight:600;
    font-size:0.85rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    animation: bounceIn 0.6s ease-out;
}

@keyframes bounceIn {
    0% { transform:scale(0); }
    50% { transform:scale(1.1); }
    100% { transform:scale(1); }
}

.badge-success-custom {
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
    color:#fff;
}

.badge-warning-custom {
    background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%);
    color:#000;
}

/* Tombol View dengan Efek Hover */
.btn-view {
    background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%);
    border:none;
    color:#fff;
    padding:0.5rem 1.2rem;
    border-radius:50rem;
    font-weight:600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(13,110,253,0.3);
    position:relative;
    overflow:hidden;
}

.btn-view::before {
    content:'';
    position:absolute;
    top:50%;
    left:50%;
    width:0;
    height:0;
    border-radius:50%;
    background:rgba(255,255,255,0.3);
    transform:translate(-50%, -50%);
    transition:width 0.6s, height 0.6s;
}

.btn-view:hover::before {
    width:300px;
    height:300px;
}

.btn-view:hover {
    transform:translateY(-3px);
    box-shadow: 0 6px 20px rgba(13,110,253,0.4);
    color:#fff;
}

.btn-view i {
    transition: transform 0.3s;
}

.btn-view:hover i {
    transform: scale(1.2) rotate(10deg);
}

/* Modal Styling */
.modal-content {
    border-radius:1.5rem;
    border:none;
    box-shadow: 0 15px 50px rgba(0,0,0,0.3);
    animation: modalSlideIn 0.4s ease-out;
}

@keyframes modalSlideIn {
    from { 
        opacity:0; 
        transform:translateY(-50px) scale(0.9);
    }
    to { 
        opacity:1; 
        transform:translateY(0) scale(1);
    }
}

.modal-header {
    background: linear-gradient(135deg, #0b9d4f 0%, #198754 100%);
    color:#fff;
    border:none;
    padding:1.5rem 2rem;
    border-radius:1.5rem 1.5rem 0 0;
}

.modal-header .modal-title {
    font-weight:700;
    font-size:1.3rem;
}

.modal-header .modal-title i {
    animation: pulse 2s infinite;
}

.modal-body {
    padding:2rem;
    max-height:70vh;
    overflow-y:auto;
}

/* Card dalam Modal */
.card-info {
    border-radius:1rem;
    border:none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    margin-bottom:1.5rem;
    overflow:hidden;
    transition: all 0.3s ease;
}

.card-info:hover {
    transform:translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

.card-info .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color:#2c3e50;
    font-weight:600;
    padding:1rem 1.5rem;
    border:none;
}

.card-info .card-header i {
    color:#198754;
    margin-right:0.5rem;
}

.card-info .card-body {
    padding:1.5rem;
    background:#fff;
}

.card-success-header .card-header {
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
    color:#fff;
}

.card-success-header .card-header i {
    color:#fff;
}

/* Info Row Styling */
.info-row {
    padding:0.5rem 0;
    border-bottom:1px solid #f1f1f1;
    transition: all 0.3s ease;
}

.info-row:last-child {
    border-bottom:none;
}

.info-row:hover {
    background: rgba(25,135,84,0.03);
    padding-left:0.5rem;
}

.info-row strong {
    color:#2c3e50;
    font-weight:600;
}

/* Content Box dalam Modal */
.content-box {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding:1rem 1.2rem;
    border-radius:0.8rem;
    border-left:4px solid #198754;
    transition: all 0.3s ease;
}

.content-box:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    border-left-width:6px;
}

/* Alert Warning */
.alert-warning-custom {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe5a1 100%);
    border:none;
    border-radius:1rem;
    padding:1.2rem 1.5rem;
    box-shadow: 0 4px 12px rgba(255,193,7,0.2);
    animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
    from { 
        opacity:0; 
        transform:translateX(-20px);
    }
    to { 
        opacity:1; 
        transform:translateX(0);
    }
}

.alert-warning-custom i {
    color:#ff6b35;
    font-size:1.3rem;
}

/* Empty State */
.empty-state {
    padding:4rem 2rem;
    text-align:center;
    animation: fadeInUp 0.8s ease-out;
}

.empty-state i {
    font-size:5rem;
    color:#cbd5e0;
    margin-bottom:1.5rem;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform:translateY(0); }
    50% { transform:translateY(-20px); }
}

.empty-state h5 {
    color:#6c757d;
    font-weight:600;
    margin-bottom:0.8rem;
}

.empty-state p {
    color:#adb5bd;
    margin-bottom:1.5rem;
}

.btn-primary-custom {
    background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
    border:none;
    padding:0.8rem 2rem;
    border-radius:50rem;
    font-weight:600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(13,110,253,0.3);
}

.btn-primary-custom:hover {
    transform:translateY(-3px);
    box-shadow: 0 6px 20px rgba(13,110,253,0.4);
}

/* Modal Footer Buttons */
.btn-modal {
    min-width:120px;
    padding:0.7rem 1.8rem;
    font-weight:600;
    border-radius:50rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    position:relative;
    overflow:hidden;
}

.btn-modal::before {
    content:'';
    position:absolute;
    top:50%;
    left:50%;
    width:0;
    height:0;
    border-radius:50%;
    background:rgba(255,255,255,0.3);
    transform:translate(-50%, -50%);
    transition:width 0.6s, height 0.6s;
}

.btn-modal:hover::before {
    width:300px;
    height:300px;
}

.btn-modal:hover {
    transform:translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.btn-secondary-modal {
    background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
    border:none;
    color:#fff;
}

.btn-primary-modal {
    background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
    border:none;
    color:#fff;
}

/* Scroll Bar Custom untuk Modal */
.modal-body::-webkit-scrollbar {
    width:8px;
}

.modal-body::-webkit-scrollbar-track {
    background:#f1f1f1;
    border-radius:10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
    border-radius:10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0b9d4f 0%, #198754 100%);
}

/* Responsive */
@media (max-width: 768px) {
    .card-riwayat .card-body {
        padding:1.5rem;
    }
    
    .table-riwayat {
        font-size:0.85rem;
    }
    
    .table-riwayat thead th,
    .table-riwayat tbody td {
        padding:0.8rem 0.5rem;
    }
    
    .modal-body {
        padding:1.5rem;
    }
}
</style>

<div class="col-lg-9 mt-2">
    <div class="card card-riwayat">
        <div class="card-header">
            <i class="bi bi-clock-history me-2"></i>Riwayat Konsultasi
        </div>
        <div class="card-body">
            <?php if(mysqli_num_rows($result) > 0): ?>
            
            <div class="table-responsive">
                <table class="table table-hover table-riwayat">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Tanggal Konsultasi</th>
                            <th width="15%">Status</th>
                            <th width="20%">Petugas/Dokter</th>
                            <th width="25%">Keluhan Utama</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while($row = mysqli_fetch_assoc($result)): 
                            // Tentukan status
                            $status = '';
                            $badge_class = '';
                            if($row['id_tindak_lanjut']) {
                                $status = 'Selesai';
                                $badge_class = 'badge-success-custom';
                            } else {
                                $status = 'Menunggu';
                                $badge_class = 'badge-warning-custom';
                            }
                        ?>
                        <tr>
                            <td><strong><?= $no++; ?></strong></td>
                            <td>
                                <i class="bi bi-calendar3 text-success me-1"></i>
                                <?= date('d-m-Y', strtotime($row['tgl_konsultasi'])); ?><br>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i><?= date('H:i', strtotime($row['tgl_konsultasi'])); ?> WIB
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-custom <?= $badge_class; ?>">
                                    <i class="bi bi-<?= $row['id_tindak_lanjut'] ? 'check-circle' : 'hourglass-split'; ?> me-1"></i>
                                    <?= $status; ?>
                                </span>
                            </td>
                            <td>
                                <strong class="text-primary"><?= htmlspecialchars($row['nama_petugas']); ?></strong><br>
                                <small class="text-muted">
                                    <i class="bi bi-award me-1"></i><?= htmlspecialchars($row['spesialis']); ?>
                                </small>
                            </td>
                            <td>
                                <span class="text-secondary">
                                    <?= substr(htmlspecialchars($row['keluhan_utama']), 0, 50) . '...'; ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-view btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalView<?= $row['id_konsultasi']; ?>">
                                    <i class="bi bi-eye me-1"></i> View
                                </button>
                            </td>
                        </tr>

                        <!-- Modal View Detail -->
                        <div class="modal fade" id="modalView<?= $row['id_konsultasi']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="bi bi-file-medical-fill me-2"></i> Detail Konsultasi
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Informasi Konsultasi -->
                                        <div class="card card-info">
                                            <div class="card-header">
                                                <i class="bi bi-info-circle-fill"></i> Informasi Konsultasi
                                            </div>
                                            <div class="card-body">
                                                <div class="info-row row">
                                                    <div class="col-md-4"><strong>Tanggal Konsultasi:</strong></div>
                                                    <div class="col-md-8">
                                                        <i class="bi bi-calendar-check text-success me-1"></i>
                                                        <?= date('d F Y, H:i', strtotime($row['tgl_konsultasi'])); ?> WIB
                                                    </div>
                                                </div>
                                                <div class="info-row row">
                                                    <div class="col-md-4"><strong>Petugas/Dokter:</strong></div>
                                                    <div class="col-md-8">
                                                        <i class="bi bi-person-badge text-primary me-1"></i>
                                                        <?= htmlspecialchars($row['nama_petugas']); ?>
                                                    </div>
                                                </div>
                                                <div class="info-row row">
                                                    <div class="col-md-4"><strong>Spesialis:</strong></div>
                                                    <div class="col-md-8">
                                                        <i class="bi bi-award text-warning me-1"></i>
                                                        <?= htmlspecialchars($row['spesialis']); ?>
                                                    </div>
                                                </div>
                                                <div class="info-row row">
                                                    <div class="col-md-4"><strong>Status:</strong></div>
                                                    <div class="col-md-8">
                                                        <span class="badge badge-custom <?= $badge_class; ?>">
                                                            <i class="bi bi-<?= $row['id_tindak_lanjut'] ? 'check-circle' : 'hourglass-split'; ?> me-1"></i>
                                                            <?= $status; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Data Keluhan -->
                                        <div class="card card-info">
                                            <div class="card-header">
                                                <i class="bi bi-clipboard2-pulse-fill"></i> Data Keluhan
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <strong><i class="bi bi-exclamation-circle text-danger me-1"></i> Keluhan Utama:</strong>
                                                    <div class="content-box mt-2">
                                                        <?= nl2br(htmlspecialchars($row['keluhan_utama'])); ?>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <strong><i class="bi bi-clock-history text-info me-1"></i> Lama Keluhan:</strong>
                                                    <div class="content-box mt-2">
                                                        <?= htmlspecialchars($row['lama_keluhan']); ?>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <strong><i class="bi bi-capsule text-primary me-1"></i> Riwayat Pengobatan Sendiri:</strong>
                                                    <div class="content-box mt-2">
                                                        <?= nl2br(htmlspecialchars($row['riwayat_pengobatan_sendiri'])); ?>
                                                    </div>
                                                </div>
                                                <?php if($row['catatan_mahasiswa']): ?>
                                                <div class="mb-0">
                                                    <strong><i class="bi bi-journal-text text-secondary me-1"></i> Catatan Tambahan:</strong>
                                                    <div class="content-box mt-2">
                                                        <?= nl2br(htmlspecialchars($row['catatan_mahasiswa'])); ?>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Hasil Tindak Lanjut -->
                                        <?php if($row['id_tindak_lanjut']): ?>
                                        <div class="card card-info card-success-header">
                                            <div class="card-header">
                                                <i class="bi bi-prescription2 me-1"></i> Hasil Diagnosa & Tindak Lanjut
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar-check me-1"></i>
                                                        Tanggal: <?= date('d F Y, H:i', strtotime($row['tgl_tindak_lanjut'])); ?> WIB
                                                    </small>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <strong><i class="bi bi-clipboard-check text-success me-1"></i> Diagnosa:</strong>
                                                    <div class="content-box mt-2">
                                                        <?= nl2br(htmlspecialchars($row['diagnosa'])); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <strong><i class="bi bi-prescription text-primary me-1"></i> Resep Obat:</strong>
                                                    <div class="content-box mt-2">
                                                        <?= nl2br(htmlspecialchars($row['resep_obat'])); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-0">
                                                    <strong><i class="bi bi-heart-pulse text-danger me-1"></i> Saran Perawatan:</strong>
                                                    <div class="content-box mt-2">
                                                        <?= nl2br(htmlspecialchars($row['saran_perawatan'])); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <div class="alert alert-warning-custom">
                                            <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                                            <strong>Informasi:</strong> Konsultasi ini masih menunggu tindak lanjut dari petugas klinik.
                                        </div>
                                        <?php endif; ?>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-modal btn-secondary-modal" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle me-1"></i> Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php else: ?>
            <!-- Jika belum ada riwayat -->
            <div class="empty-state">
                <i class="bi bi-folder2-open"></i>
                <h5>Belum Ada Riwayat Konsultasi</h5>
                <p>Anda belum pernah melakukan konsultasi kesehatan</p>
                <a href="?page=konsultasi_baru" class="btn btn-primary-custom">
                    <i class="bi bi-plus-circle me-2"></i> Buat Konsultasi Baru
                </a>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
function printKonsultasi(id) {
    // Fungsi untuk print konsultasi
    window.open('cetak_konsultasi.php?id=' + id, '_blank');
}
</script>