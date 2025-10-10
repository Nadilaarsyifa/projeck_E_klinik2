
            <?php
            if (isset($_GET['x']) && $_GET['x'] == 'beranda') {
                $page = "beranda.php";
                include "main.php";
            }elseif (isset($_GET['x']) && $_GET['x'] == 'konsultasi') {
                $page = "konsultasi.php";
                include "main.php";
            } elseif (isset($_GET['x']) && $_GET['x'] == 'riwayatkonsultasi') {
                $page = "riwayatkonsultasi.php";
                include "main.php";
            }elseif (isset($_GET['x']) && $_GET['x'] == 'datamedis') {
                $page = "datamedis.php";
                include "main.php";
            }else if (isset($_GET['x']) && $_GET['x'] == 'konsultasimasuk') {
                $page = "konsultasimasuk.php";
                include "main.php";
            }elseif (isset($_GET['x']) && $_GET['x'] == 'user') {
                $page = "user.php";
                include "main.php";
            }elseif (isset($_GET['x']) && $_GET['x'] == 'report') {
                $page = "report.php";
                include "main.php";
            } elseif (isset($_GET['x']) && $_GET['x'] == 'login') {
                include "login.php";
            }else {
                include "main.php";
            }
            ?>
           