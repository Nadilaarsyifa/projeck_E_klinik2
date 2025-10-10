<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body style="height: 3000px;">
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
            if (isset($_GET['x']) && $_GET['x'] == 'beranda') {
                include "beranda.php";
            }elseif (isset($_GET['x']) && $_GET['x'] == 'konsultasi') {
                include "konsultasi.php";
            } elseif (isset($_GET['x']) && $_GET['x'] == 'riwayatkonsultasi') {
                include "riwayatkonsultasi.php";
            }elseif (isset($_GET['x']) && $_GET['x'] == 'datamedis') {
                include "datamedis.php";
            }else if (isset($_GET['x']) && $_GET['x'] == 'konsultasimasuk') {
                include "konsultasimasuk.php";
            }elseif (isset($_GET['x']) && $_GET['x'] == 'user') {
                include "user.php";
            }elseif (isset($_GET['x']) && $_GET['x'] == 'report') {
                include "report.php";
            }
            ?>
            <!--End Content  -->
        </div>
        <div class="fixed-bottom text-center mb-2">
            Copyright 2025 E-klinik by kelompok 2
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>