<?php
//session_start();
if (!empty($_SESSION['username_eklinik'])) {
    header('location:home');
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>E-klinik - Aplikasi klinik online PNL</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/login.css" rel="stylesheet" />
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100 bg-body-tertiary">

    <main class="form-signin w-100" style="max-width: 330px;">
        <form class="needs-validation" novalidate action="proses/proses_login.php" method="POST">

            <!-- Logo -->
            <img class="mx-auto d-block"
                src="assets/img/logo.png"
                alt="Logo E-Klinik"
                style="width: 50%; max-width: 300px; margin-bottom: 15px;" />

            <!-- Judul -->
            <h1 class="h3 fw-normal text-center mb-3">Please sign in</h1>

            <!-- Username -->
            <div class="form-floating mb-2">
                <input name="username" type="text" class="form-control" id="floatingusername"
                    placeholder="username Mahasiswa" pattern="\d{13}" required />
                <label for="floatingusername">Username</label>
                <div class="invalid-feedback">Masukkan Username yang valid</div>
            </div>

            <!-- Password -->
            <div class="form-floating mb-2">
                <input name="password" type="password" class="form-control" id="floatingPassword"
                    placeholder="Password" required />
                <label for="floatingPassword">Password</label>
                <div class="invalid-feedback">Masukkan password</div>
            </div>

            <!-- Remember me -->
            <div class="form-check text-start my-3">
                <input class="form-check-input" type="checkbox" value="remember-me" id="checkDefault" />
                <label class="form-check-label" for="checkDefault">Remember me</label>
            </div>

            <button class="btn btn-success w-100 py-2" type="submit" name="submit_validate" value="abc">
                Login
            </button>


            <!-- Footer -->
            <p class="mt-5 mb-3 text-body-secondary text-center">&copy; 2025–2030</p>
        </form>
    </main>

    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>

</html>