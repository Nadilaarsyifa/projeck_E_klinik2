<?php
include "connect.php";

if (isset($_GET['id'])) {
    $id_jadwal = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Hapus jadwal
    $query = mysqli_query($conn, "DELETE FROM jadwal_petugas_klinik WHERE id_jadwal = '$id_jadwal'");
    
    if ($query) {
        header("Location: ../jadwal_petugas?notif=hapus_berhasil");
    } else {
        header("Location: ../jadwal_petugas?notif=error");
    }
} else {
    header("Location: ../jadwal_petugas");
}
?>