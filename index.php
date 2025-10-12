<?php
 session_start();
if (isset($_GET['x']) && $_GET['x'] == 'beranda') {
    $page = "beranda.php";
    include "main.php";
} elseif (isset($_GET['x']) && $_GET['x'] == 'konsultasi') {
    if($_SESSION['role_eklinik']=='mahasiswa'){
    $page = "konsultasi.php";
    include "main.php";
    }else{
        $page = "beranda.php";
    include "main.php";
    }

} elseif (isset($_GET['x']) && $_GET['x'] == 'riwayatkonsultasi') {
    if($_SESSION['role_eklinik']=='admin' || $_SESSION['role_eklinik']=='mahasiswa'){
    $page = "riwayatkonsultasi.php";
    include "main.php";
    }else{
      $page = "beranda.php";
    include "main.php";   
    }
} elseif (isset($_GET['x']) && $_GET['x'] == 'datamedis') {
    $page = "datamedis.php";
    include "main.php";
} elseif (isset($_GET['x']) && $_GET['x'] == 'konsultasimasuk') {
    if($_SESSION['role_eklinik']=='petugas klinik' || $_SESSION['role_eklinik']=='mahasiswa'){
      $page = "konsultasimasuk.php";
      include "main.php";
    }else{
      $page = "beranda.php";
    include "main.php"; 
    }
} elseif (isset($_GET['x']) && $_GET['x'] == 'user') {
    if($_SESSION['role_eklinik']=='admin'){
        $page = "user.php";
        include "main.php";
    }else{
        $page = "beranda.php";
        include "main.php";  
    }
} elseif (isset($_GET['x']) && $_GET['x'] == 'report') {
    if($_SESSION['role_eklinik']=='admin'){
       $page = "report.php";
       include "main.php";
    }else{
        $page = "beranda.php";
        include "main.php";
    }
} elseif (isset($_GET['x']) && $_GET['x'] == 'login') {
    include "login.php";
} elseif (isset($_GET['x']) && $_GET['x'] == 'logout') {
    include "proses/proses_logout.php";
} else {
    $page = "beranda.php";
    include "main.php";
}
?>

           