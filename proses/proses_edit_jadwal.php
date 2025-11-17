<?php
include "connect.php";

if (isset($_POST['edit_jadwal'])) {
    $id_jadwal = mysqli_real_escape_string($conn, $_POST['id_jadwal']);
    $id_petugas = mysqli_real_escape_string($conn, $_POST['id_petugas']);
    $hari = mysqli_real_escape_string($conn, $_POST['hari']);
    $jam_mulai = mysqli_real_escape_string($conn, $_POST['jam_mulai']);
    $jam_selesai = mysqli_real_escape_string($conn, $_POST['jam_selesai']);
    
    // Validasi jam mulai harus lebih awal dari jam selesai
    if ($jam_mulai >= $jam_selesai) {
        header("Location: ../jadwal_petugas?notif=waktu_invalid");
        exit;
    }
    
    // Cek apakah ada jadwal bentrok (kecuali jadwal yang sedang diedit)
    $cek_bentrok = mysqli_query($conn, "
        SELECT jp.*, pk.nama 
        FROM jadwal_petugas_klinik jp
        JOIN petugas_klinik pk ON jp.id_petugas = pk.id_petugas
        WHERE jp.id_petugas = '$id_petugas' 
        AND jp.hari = '$hari'
        AND jp.id_jadwal != '$id_jadwal'
        AND NOT (
            '$jam_selesai' <= jp.jam_mulai OR '$jam_mulai' >= jp.jam_selesai
        )
    ");
    
    if (mysqli_num_rows($cek_bentrok) > 0) {
        $jadwal_bentrok = mysqli_fetch_assoc($cek_bentrok);
        $pesan = "Petugas " . $jadwal_bentrok['nama'] . " sudah ada jadwal di hari $hari jam " . 
                 substr($jadwal_bentrok['jam_mulai'], 0, 5) . " - " . 
                 substr($jadwal_bentrok['jam_selesai'], 0, 5);
        
        header("Location: ../jadwal_petugas?notif=bentrok&pesan=" . urlencode($pesan));
        exit;
    }
    
    // Update jadwal
    $query = mysqli_query($conn, "
        UPDATE jadwal_petugas_klinik 
        SET id_petugas = '$id_petugas',
            hari = '$hari',
            jam_mulai = '$jam_mulai',
            jam_selesai = '$jam_selesai'
        WHERE id_jadwal = '$id_jadwal'
    ");
    
    if ($query) {
        header("Location: ../jadwal_petugas?notif=edit_berhasil");
    } else {
        header("Location: ../jadwal_petugas?notif=error");
    }
} else {
    header("Location: ../jadwal_petugas");
}
?>