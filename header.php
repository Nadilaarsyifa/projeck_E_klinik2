<nav class="navbar navbar-expand bg-success sticky-top">
  <div class="container-lg">
    <a class="navbar-brand fw-bold" href="." style="
      font-family: 'Times New Roman', Times, serif;
      font-size: 1.6rem;
      color: #fff;
      letter-spacing: 0.5px;
    ">
      <i class="bi bi-hospital me-2"></i> E-Klinik
    </a>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo $hasil['username']; ?>
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