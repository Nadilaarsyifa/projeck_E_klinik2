<?php
include '../proses/connect.php';

// Validasi input
if (!isset($_POST['id_konsultasi']) || !isset($_POST['nim']) || !isset($_POST['id_petugas']) || 
    !isset($_POST['diagnosa']) || !isset($_POST['resep_obat']) || !isset($_POST['saran_perawatan'])) {
    header("Location: ../index.php?x=konsultasimasuk&error=invalid");
    exit;
}

$id_konsultasi = $_POST['id_konsultasi'];
$nim = $_POST['nim'];
$id_petugas = $_POST['id_petugas'];
$diagnosa = trim($_POST['diagnosa']);
$resep_obat = trim($_POST['resep_obat']);
$saran_perawatan = trim($_POST['saran_perawatan']);
$tgl_tindak_lanjut = date('Y-m-d H:i:s');

// Validasi data tidak kosong
if (empty($diagnosa) || empty($resep_obat) || empty($saran_perawatan)) {
    header("Location: ../index.php?x=konsultasimasuk&error=empty");
    exit;
}

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
    // Insert ke tabel tindak_lanjut menggunakan prepared statement
    $stmt = mysqli_prepare($conn, "INSERT INTO tindak_lanjut 
            (tgl_tindak_lanjut, diagnosa, resep_obat, saran_perawatan, id_konsultasi, nim, id_petugas)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    mysqli_stmt_bind_param($stmt, "sssssss", 
        $tgl_tindak_lanjut, 
        $diagnosa, 
        $resep_obat, 
        $saran_perawatan, 
        $id_konsultasi, 
        $nim, 
        $id_petugas
    );
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Gagal menyimpan tindak lanjut");
    }
    mysqli_stmt_close($stmt);
    
    // Update status konsultasi menggunakan prepared statement
    $stmt2 = mysqli_prepare($conn, "UPDATE konsultasi SET status='SUDAH DIBALAS' WHERE id_konsultasi=?");
    mysqli_stmt_bind_param($stmt2, "s", $id_konsultasi);
    
    if (!mysqli_stmt_execute($stmt2)) {
        throw new Exception("Gagal update status konsultasi");
    }
    mysqli_stmt_close($stmt2);
    
    // Commit transaksi
    mysqli_commit($conn);
    
    // Redirect dengan pesan sukses
    header("Location: ../index.php?x=konsultasimasuk&success=1");
    exit;
    
} catch (Exception $e) {
    // Rollback jika ada error
    mysqli_rollback($conn);
    
    // Log error (opsional)
    error_log("Error proses_balas.php: " . $e->getMessage());
    
    // Redirect dengan pesan error
    header("Location: ../index.php?x=konsultasimasuk&error=failed");
    exit;
}
?>