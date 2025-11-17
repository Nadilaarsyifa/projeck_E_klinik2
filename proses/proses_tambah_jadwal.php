<?php
include "connect.php";

if (isset($_POST['tambah_jadwal'])) {
    $id_petugas = mysqli_real_escape_string($conn, $_POST['id_petugas']);
    $hari = mysqli_real_escape_string($conn, $_POST['hari']);
    $jam_mulai = mysqli_real_escape_string($conn, $_POST['jam_mulai']);
    $jam_selesai = mysqli_real_escape_string($conn, $_POST['jam_selesai']);
    
    // Validasi jam mulai harus lebih awal dari jam selesai
    if ($jam_mulai >= $jam_selesai) {
        header("Location: ../jadwal_petugas?notif=waktu_invalid");
        exit;
    }
    
    // Cek apakah ada jadwal bentrok (jam yang tumpang tindih di hari yang sama)
    // Contoh bentrok: 
    // - Jadwal lama: Senin 08:00-12:00
    // - Jadwal baru: Senin 10:00-14:00 (BENTROK karena jam 10:00-12:00 overlap)
    $cek_bentrok = mysqli_query($conn, "
        SELECT jp.*, pk.nama 
        FROM jadwal_petugas_klinik jp
        JOIN petugas_klinik pk ON jp.id_petugas = pk.id_petugas
        WHERE jp.id_petugas = '$id_petugas' 
        AND jp.hari = '$hari'
        AND NOT (
            '$jam_selesai' <= jp.jam_mulai OR '$jam_mulai' >= jp.jam_selesai
        )
    ");
    
    if (mysqli_num_rows($cek_bentrok) > 0) {
        // Ambil info jadwal yang bentrok untuk ditampilkan
        $jadwal_bentrok = mysqli_fetch_assoc($cek_bentrok);
        $pesan = "Petugas " . $jadwal_bentrok['nama'] . " sudah ada jadwal di hari $hari jam " . 
                 substr($jadwal_bentrok['jam_mulai'], 0, 5) . " - " . 
                 substr($jadwal_bentrok['jam_selesai'], 0, 5);
        
        header("Location: ../jadwal_petugas?notif=bentrok&pesan=" . urlencode($pesan));
        exit;
    }
    
    // Insert jadwal baru
    $query = mysqli_query($conn, "
        INSERT INTO jadwal_petugas_klinik (id_petugas, hari, jam_mulai, jam_selesai) 
        VALUES ('$id_petugas', '$hari', '$jam_mulai', '$jam_selesai')
    ");
    
    if ($query) {
        header("Location: ../jadwal_petugas?notif=tambah_berhasil");
    } else {
        header("Location: ../jadwal_petugas?notif=error");
    }
} else {
    header("Location: ../jadwal_petugas");
}
?>