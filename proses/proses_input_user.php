<?php
include "connect.php";

// Ambil data dari form
$username       = isset($_POST['username']) ? htmlentities($_POST['username']) : "";
$nama           = isset($_POST['nama']) ? htmlentities($_POST['nama']) : "";
$role           = isset($_POST['role']) ? htmlentities($_POST['role']) : "";
$jenis_kelamin  = isset($_POST['jenis_kelamin']) ? htmlentities($_POST['jenis_kelamin']) : "";
$no_hp          = isset($_POST['no_hp']) ? htmlentities($_POST['no_hp']) : "";
$alamat         = isset($_POST['alamat']) ? htmlentities($_POST['alamat']) : "";
$password       = isset($_POST['password']) ? md5(htmlentities($_POST['password'])) : "";

// Field tambahan
$jurusan        = isset($_POST['jurusan']) ? htmlentities($_POST['jurusan']) : "";
$prodi          = isset($_POST['prodi']) ? htmlentities($_POST['prodi']) : "";
$tgl_lahir      = isset($_POST['tgl_lahir']) ? htmlentities($_POST['tgl_lahir']) : "";
$spesialis      = isset($_POST['spesialis']) ? htmlentities($_POST['spesialis']) : "";

// Validasi tombol submit
if (!empty($_POST['input_user_validate'])) {

    // 1️⃣ Insert ke tabel user
    $query_user = mysqli_query($conn, "
        INSERT INTO user (username, password, role)
        VALUES ('$username', '$password', '$role')
    ");

    if ($query_user) {
        // 2️⃣ Insert ke tabel detail sesuai role
        if ($role == 'petugas klinik') {
            $query_detail = mysqli_query($conn, "
                INSERT INTO petugas_klinik (id_petugas, nama, jenis_kelamin, no_hp, alamat, spesialis)
                VALUES ('$username', '$nama', '$jenis_kelamin', '$no_hp', '$alamat', '$spesialis')
            ");
        } elseif ($role == 'mahasiswa') {
            $query_detail = mysqli_query($conn, "
                INSERT INTO mahasiswa (nim, nama, jenis_kelamin, no_hp, alamat, jurusan, prodi, tgl_lahir)
                VALUES ('$username', '$nama', '$jenis_kelamin', '$no_hp', '$alamat', '$jurusan', '$prodi', '$tgl_lahir')
            ");
        } else {
            $query_detail = true; // admin tidak punya detail tabel tambahan
        }

        // 3️⃣ Cek hasil insert detail
        if ($query_detail) {
            echo "<script>
                alert('Data berhasil dimasukkan!');
                window.location='../user';
            </script>";
        } else {
            echo "<script>
                alert('Gagal memasukkan data ke tabel detail: " . mysqli_error($conn) . "');
                window.location='../user';
            </script>";
        }

    } else {
        echo "<script>
            alert('Gagal memasukkan data ke tabel user: " . mysqli_error($conn) . "');
            window.location='../user';
        </script>";
    }
}
?>
