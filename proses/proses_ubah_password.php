<?php
include "connect.php";
session_start();

// JANGAN gunakan htmlentities untuk password!
$passwordlama   = isset($_POST['passwordlama']) ? $_POST['passwordlama'] : "";
$passwordbaru   = isset($_POST['passwordbaru']) ? $_POST['passwordbaru'] : "";
$repasswordbaru = isset($_POST['repasswordbaru']) ? $_POST['repasswordbaru'] : "";

if(!empty($_POST['ubah_password_validate'])) {
    
    // Validasi password baru dan konfirmasi
    if($passwordbaru !== $repasswordbaru) {
        echo "<script>
            alert('Password baru dan konfirmasi password tidak cocok!');
            window.history.back();
        </script>";
        exit;
    }
    
    // Validasi panjang password baru
    if(strlen($passwordbaru) < 6) {
        echo "<script>
            alert('Password baru minimal 6 karakter!');
            window.history.back();
        </script>";
        exit;
    }
    
    // Validasi password lama tidak boleh kosong
    if(empty($passwordlama)) {
        echo "<script>
            alert('Password lama harus diisi!');
            window.history.back();
        </script>";
        exit;
    }
    
    // HASH password lama dengan MD5 (sesuai dengan yang ada di database)
    $passwordlama_hash = md5($passwordlama);
    
    // Cek username dan password lama
    $username = $_SESSION['username_eklinik'];
    $stmt = mysqli_prepare($conn, "SELECT username FROM user WHERE username = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $passwordlama_hash);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0) {
        // Password lama benar, hash password baru dan update
        $passwordbaru_hash = md5($passwordbaru);
        
        $stmt_update = mysqli_prepare($conn, "UPDATE user SET password = ? WHERE username = ?");
        mysqli_stmt_bind_param($stmt_update, "ss", $passwordbaru_hash, $username);
        
        if(mysqli_stmt_execute($stmt_update)) {
            echo "<script>
                alert('Password berhasil diubah! Silakan login kembali.');
                window.location = '../login';
            </script>";
            session_destroy();
            exit;
        } else {
            echo "<script>
                alert('Gagal mengubah password!');
                window.history.back();
            </script>";
            exit;
        }
    } else {
        echo "<script>
            alert('Password lama tidak sesuai!');
            window.history.back();
        </script>";
        exit;
    }
}
?>