<?php
//session_start();
if (empty($_SESSION['username_eklinik'])) {
    header('location:login');
}
include "proses/connect.php";
$query = mysqli_query($conn, "SELECT * FROM user WHERE username = '$_SESSION[username_eklinik]'");
$hasil = mysqli_fetch_array($query);


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    <!-- Header-->
    <?php include "header.php" ?>
    <!-- End Header--->
    <div class="container-lg">
        <div class="row">
            <!--- Sidebar-->
            <?php include "sidebar.php" ?>
            <!-- End Sidebar -->

            <!-- Content -->
            <?php
            include $page;
            ?>
            <!--End Content  -->
        </div>
        <div class="fixed-bottom text-center  py-2" style="background-color: #8e959bff;">
            Copyright 2025 E-klinik by SkyWhisper
        </div>


    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>