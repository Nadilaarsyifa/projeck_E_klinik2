<?php
include '../proses/connect.php';

$id_konsultasi = $_POST['id_konsultasi'];
$nim = $_POST['nim'];
$id_petugas = $_POST['id_petugas'];
$diagnosa = $_POST['diagnosa'];
$resep_obat = $_POST['resep_obat'];
$saran_perawatan = $_POST['saran_perawatan'];
$tgl_tindak_lanjut = date('Y-m-d H:i:s');

// Insert ke tabel tindak_lanjut
$sql = "INSERT INTO tindak_lanjut
        (tgl_tindak_lanjut, diagnosa, resep_obat, saran_perawatan, id_konsultasi, nim, id_petugas)
        VALUES
        ('$tgl_tindak_lanjut', '$diagnosa', '$resep_obat', '$saran_perawatan', '$id_konsultasi', '$nim', '$id_petugas')";

if(mysqli_query($conn, $sql)){
    mysqli_query($conn, "UPDATE konsultasi SET status='SUDAH DIBALAS' WHERE id_konsultasi='$id_konsultasi'");
    
    // Redirect ke halaman konsultasi masuk menggunakan routing sistem
    header("Location: ../index.php?x=konsultasimasuk");
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
    exit;
}
?>