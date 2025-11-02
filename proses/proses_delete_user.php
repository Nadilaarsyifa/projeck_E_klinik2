<?php
include "connect.php";

// Ambil username dari parameter GET
$username = isset($_GET['username']) ? htmlentities($_GET['username']) : "";

// Pastikan ada username
if ($username == "") {
    echo "<script>
        alert('Username tidak ditemukan!');
        window.location='../user';
    </script>";
    exit;
}

// Ambil role user untuk tahu hapus di tabel mana
$query_role = mysqli_query($conn, "SELECT role FROM user WHERE username = '$username'");
$data = mysqli_fetch_assoc($query_role);

if ($data) {
    $role = $data['role'];

    // Hapus data dari tabel detail dulu
    if ($role == 'mahasiswa') {
        mysqli_query($conn, "DELETE FROM mahasiswa WHERE nim = '$username'");
    } elseif ($role == 'petugas klinik') {
        mysqli_query($conn, "DELETE FROM petugas_klinik WHERE id_petugas = '$username'");
    }

    // Setelah itu hapus dari tabel user
    $query_delete_user = mysqli_query($conn, "DELETE FROM user WHERE username = '$username'");

    if ($query_delete_user) {
        echo "<script>
            alert('Data user berhasil dihapus!');
            window.location='../user';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus data user: " . mysqli_error($conn) . "');
            window.location='../user';
        </script>";
    }
} else {
    echo "<script>
        alert('Data user tidak ditemukan!');
        window.location='../user';
    </script>";
}
?>
