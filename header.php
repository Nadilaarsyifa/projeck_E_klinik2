<nav class="navbar navbar-expand sticky-top" style="background-color: #0b9d4fff; padding-top: 0.3rem; padding-bottom: 0.3rem;">
  <div class="container-lg">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="."
      style="font-family: 'Times New Roman', Times, serif; font-size: 1.8rem; color: #fff; letter-spacing: 0.5px;">
      <img src="assets/img/logo2.png" alt="Logo E-Klinik"
        style="width: 50px; height: 40px; object-fit: contain; margin-right: 10px;">
      <span style="line-height: 1;">E-Klinik</span>
    </a>


    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo $_SESSION['nama_eklinik'] ?? 'User'; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end mt-2">
            <li><a class="dropdown-item" href="#"><i class="bi bi-person-circle me-2"></i> Profil</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Setting</a></li>
            <li><a class="dropdown-item" href="logout"><i class="bi bi-box-arrow-left me-2"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

