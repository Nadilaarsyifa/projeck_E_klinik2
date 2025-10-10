<?php
session_start();
include "connect.php";

$username = isset($_POST['username']) ? htmlentities($_POST['username']) : "";
$password = isset($_POST['password']) ? md5(htmlentities($_POST['password'])) : "";

if (!empty($_POST['submit_validate'])) {

    $query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username' AND password='$password'");

    if (!$query) {
        die("Query error: " . mysqli_error($conn)); // biar kelihatan kalau SQL salah
    }

    $hasil = mysqli_fetch_array($query);

    if ($hasil) {
        $_SESSION['username_eklinik'] = $username;
        header('Location: ../beranda');
        exit();
    } else {
        echo "<script>
            alert('Username atau password yang Anda masukkan salah');
            window.location = '../login';
        </script>";
    }
}
?>
