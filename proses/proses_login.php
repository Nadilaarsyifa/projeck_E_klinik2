<?php
session_start();
include "connect.php";

$username = (isset($_POST['username'])) ? htmlentities($_POST['username']) : ""; 
$password = (isset($_POST['password'])) ? md5(htmlentities($_POST['password'])) : ""; 

if(!empty($_POST['submit_validate'])) {
    // Ambil user dari tabel user
    $query = mysqli_query($conn, "
        SELECT u.username, u.role,
               CASE 
                   WHEN u.role = 2 THEN pk.nama
                   WHEN u.role = 3 THEN m.nama
                   ELSE 'Admin'
               END AS nama
        FROM user u
        LEFT JOIN petugas_klinik pk ON u.username = pk.id_petugas AND u.role = 2
        LEFT JOIN mahasiswa m ON u.username = m.nim AND u.role = 3
        WHERE u.username = '$username' AND u.password = '$password'
    ");

    if(!$query) {
        die("Query gagal: " . mysqli_error($conn));
    }

    $hasil = mysqli_fetch_assoc($query);

    if($hasil) {
        // Simpan session lengkap
        $_SESSION['username_eklinik'] = $hasil['username'];
        $_SESSION['role_eklinik'] = $hasil['role'];
        $_SESSION['nama_eklinik'] = $hasil['nama']; // <--- ini penting

        header('location:../beranda');
    } else {
        echo "<script>
            alert('Username atau password salah');
            window.location = '../login';
        </script>";
    }
}
?>


















<!-- <?php
session_start();
include "connect.php";
    $username = (isset($_POST['username'])) ? htmlentities($_POST['username']) : ""; 
    $password = (isset($_POST['password'])) ? md5(htmlentities($_POST['password'])) : ""; 
    if(!empty($_POST['submit_validate'])){
        $query = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' && password = '$password' ");
        $hasil = mysqli_fetch_array($query);
        if($hasil){
            $_SESSION['username_eklinik'] = $username;
            $_SESSION['role_eklinik'] = $hasil['role'];
            header('location:../beranda');
        }else{?>
<script>
alert('username atau password salah');
window.location = '../login'
</script>
<?php 

        }

    }
?> -->