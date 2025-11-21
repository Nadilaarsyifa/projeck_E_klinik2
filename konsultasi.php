<?php
if(session_status()===PHP_SESSION_NONE) session_start();
if(empty($_SESSION['username_eklinik'])){
    header('Location: login');
    exit;
}
$nim_login = $_SESSION['username_eklinik'];

include "proses/connect.php";

// Ambil data mahasiswa
$q_mhs = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE nim='$nim_login'");
$mhs = mysqli_fetch_assoc($q_mhs);
if(!$mhs) die("<div class='alert alert-danger m-3'>Data mahasiswa tidak ditemukan.</div>");

// Ambil jumlah konsultasi hari ini
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM konsultasi WHERE nim=? AND DATE(tgl_konsultasi)=?");
$stmt->bind_param("ss", $nim_login, $today);
$stmt->execute();
$konsultasi_hari_ini = $stmt->get_result()->fetch_assoc()['total'];

// Cek petugas yang bertugas sekarang
date_default_timezone_set('Asia/Jakarta');
$hariMap = [
    'Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa',
    'Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'
];
$hari_ini = date('l');
$jam_sekarang = date('H:i:s');
$hari = $hariMap[$hari_ini];

$stmt_petugas = $conn->prepare("
    SELECT j.id_petugas, p.nama 
    FROM jadwal_petugas_klinik j 
    JOIN petugas_klinik p ON j.id_petugas=p.id_petugas
    WHERE j.hari=? AND ? BETWEEN j.jam_mulai AND j.jam_selesai
    LIMIT 1
");
$stmt_petugas->bind_param("ss", $hari, $jam_sekarang);
$stmt_petugas->execute();
$res_petugas = $stmt_petugas->get_result()->fetch_assoc();

$id_petugas_now = $res_petugas['id_petugas'] ?? '';
$nama_petugas_now = $res_petugas['nama'] ?? '';

// Notifikasi
$notif = '';
$notif_type = '';
if(isset($_GET['notif'])){
    $messages = [
        'berhasil'=>['Konsultasi berhasil dikirim!', 'success'],
        'limit'=>['Anda sudah mencapai batas konsultasi hari ini (maksimal 2x).','warning'],
        'no_doctor'=>['Tidak ada petugas yang tersedia pada waktu tersebut.','danger'],
        'error'=>['Terjadi kesalahan, silakan coba lagi.','danger']
    ];
    $msg = $messages[$_GET['notif']] ?? ['Terjadi kesalahan!','danger'];
    $notif = $msg[0];
    $notif_type = $msg[1];
}
?>

<style>
.card { border-radius:1.5rem; box-shadow:0 10px 25px rgba(0,0,0,0.1); border:none; margin-bottom:2rem; overflow:hidden; }
.card-header { background: linear-gradient(135deg, #0b9d4f 0%, #198754 100%); color:#fff; font-weight:700; font-size:1.4rem; padding:1.2rem 2rem; border:none; }
.card-body { padding:2rem 2.5rem; background: #f8f9fa; max-height:75vh; overflow-y:auto; }
.mb-3.row { margin-bottom:1rem; }
.form-label { font-weight:600; color:#2c3e50; }
.form-control, .form-select, textarea { border-radius:0.5rem; border:1px solid #ced4da; padding:0.65rem 1rem; transition: all 0.3s; }
.form-control:focus, .form-select:focus, textarea:focus { border-color:#198754; box-shadow:0 0 0 0.2rem rgba(25,135,84,0.15); }
.btn-success { background: linear-gradient(135deg,#198754 0%,#20c997 100%); border:none; color:#fff; border-radius:50px; padding:0.7rem 1.8rem; font-weight:600; }
.btn-success:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,0.15); }
.alert-custom { border-radius:1rem; padding:1rem 1.5rem; max-width:550px; font-weight:500; }
</style>

<div class="col-lg-9 mt-3">
    <div class="card">
        <div class="card-header">
            <i class="bi bi-clipboard-pulse me-2"></i>Form Konsultasi Medis
        </div>
        <div class="card-body">

            <?php if($notif): ?>
                <div class="alert alert-<?= $notif_type ?> text-center py-4"><?= htmlspecialchars($notif) ?></div>
            <?php endif; ?>

            <?php if(!$id_petugas_now): ?>
                <!-- Tidak ada petugas bertugas saat ini -->
                <div class="alert alert-danger text-center py-4">
                    Tidak ada petugas yang tersedia pada waktu ini.<br>
                    Silakan coba di jam kerja petugas.
                </div>

            <?php elseif($konsultasi_hari_ini >= 2): ?>
                <div class="alert alert-warning text-center py-4">Anda sudah mencapai batas konsultasi hari ini.</div>

            <?php elseif(isset($_GET['notif']) && $_GET['notif']=='berhasil'): ?>
                <!-- Form disembunyikan setelah berhasil -->
                <div class="text-center py-4">🎉 Konsultasi berhasil dikirim! Terima kasih.</div>

            <?php else: ?>
                <!-- Form tampil -->
                <form action="proses/proses_tambah_konsultasi.php" method="POST">
                    <input type="hidden" name="nim" value="<?= htmlspecialchars($nim_login) ?>">
                    <input type="hidden" name="id_petugas" value="<?= htmlspecialchars($id_petugas_now) ?>">

                    <div class="mb-3 row">
                        <label class="col-sm-4 form-label">NIM</label>
                        <div class="col-sm-8"><input type="text" class="form-control" value="<?= htmlspecialchars($mhs['nim']) ?>" readonly></div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 form-label">Nama</label>
                        <div class="col-sm-8"><input type="text" class="form-control" value="<?= htmlspecialchars($mhs['nama']) ?>" readonly></div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 form-label">Jurusan</label>
                        <div class="col-sm-8"><input type="text" class="form-control" value="<?= htmlspecialchars($mhs['jurusan']) ?>" readonly></div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 form-label">Tanggal Konsultasi</label>
                        <div class="col-sm-8"><input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 form-label">Jam Konsultasi</label>
                        <div class="col-sm-8"><input type="time" name="jam" class="form-control" value="<?= date('H:i') ?>" required></div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 form-label">Petugas Penanggung Jawab</label>
                        <div class="col-sm-8"><input type="text" class="form-control" value="<?= htmlspecialchars($nama_petugas_now) ?>" readonly></div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 form-label">Keluhan Utama</label>
                        <div class="col-sm-8"><textarea name="keluhan_utama" class="form-control" required></textarea></div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 form-label">Lama Keluhan</label>
                        <div class="col-sm-8"><input type="text" name="lama_keluhan" class="form-control" required></div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 form-label">Riwayat Pengobatan Sendiri</label>
                        <div class="col-sm-8"><textarea name="riwayat_pengobatan_sendiri" class="form-control"></textarea></div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 form-label">Catatan Mahasiswa</label>
                        <div class="col-sm-8"><textarea name="catatan_mahasiswa" class="form-control"></textarea></div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success"><i class="bi bi-send me-1"></i>Kirim Konsultasi</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
