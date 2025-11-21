<?php
if(session_status()===PHP_SESSION_NONE) session_start();

// Koneksi database
include "connect.php";

// Ambil data POST
$nim = $_POST['nim'] ?? '';
$id_petugas = $_POST['id_petugas'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';
$jam = $_POST['jam'] ?? '';
$keluhan_utama = $_POST['keluhan_utama'] ?? '';
$lama_keluhan = $_POST['lama_keluhan'] ?? '';
$riwayat_pengobatan_sendiri = $_POST['riwayat_pengobatan_sendiri'] ?? '';
$catatan_mahasiswa = $_POST['catatan_mahasiswa'] ?? '';

// Validasi data wajib
if(!$nim || !$id_petugas || !$tanggal || !$jam || !$keluhan_utama || !$lama_keluhan){
    header("Location: ../konsultasi.php?notif=error");
    exit;
}

// Gabungkan tanggal + jam menjadi DATETIME
$tgl_konsultasi = $tanggal . ' ' . $jam;

// Cek jumlah konsultasi hari ini
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM konsultasi WHERE nim=? AND DATE(tgl_konsultasi)=?");
$stmt->bind_param("ss", $nim, $tanggal);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];

if($total >= 2){
    header("Location: ../form_konsultasi.php?notif=limit");
    exit;
}

// Cek apakah petugas masih bertugas pada waktu itu
date_default_timezone_set('Asia/Jakarta');
$hariMap = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
$hari = $hariMap[date('l', strtotime($tanggal))];

$stmt_petugas = $conn->prepare("
    SELECT * FROM jadwal_petugas_klinik 
    WHERE id_petugas=? AND hari=? AND ? BETWEEN jam_mulai AND jam_selesai
    LIMIT 1
");
$stmt_petugas->bind_param("sss", $id_petugas, $hari, $jam);
$stmt_petugas->execute();
$petugas_available = $stmt_petugas->get_result()->fetch_assoc();

if(!$petugas_available){
    header("Location: ../form_konsultasi.php?notif=no_doctor");
    exit;
}

// Insert ke tabel konsultasi (gunakan tgl_konsultasi DATETIME)
$stmt_insert = $conn->prepare("
    INSERT INTO konsultasi 
    (nim, id_petugas, tgl_konsultasi, keluhan_utama, lama_keluhan, riwayat_pengobatan_sendiri, catatan_mahasiswa) 
    VALUES (?,?,?,?,?,?,?)
");
$stmt_insert->bind_param(
    "sisssss", 
    $nim, 
    $id_petugas, 
    $tgl_konsultasi, 
    $keluhan_utama, 
    $lama_keluhan, 
    $riwayat_pengobatan_sendiri, 
    $catatan_mahasiswa
);

if($stmt_insert->execute()){
    header("Location: ../konsultasi.php?notif=berhasil");
}else{
    header("Location: ../konsultasi.php?notif=error");
}
exit;
?>
