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
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalUbahPassword"><i class="bi bi-key"></i> Ubah Password</a></li>
            <li><a class="dropdown-item" href="logout"><i class="bi bi-box-arrow-left me-2"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>











<!-- Modal Tambah User Baru -->
            <div class="modal fade" id="ModalUbahPassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Password</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="proses/proses_ubah_password.php" method="POST">
                                <input type="hidden" name="password" value="password">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input disabled type="text" class="form-control" id="username" placeholder="username" name="username" 
                                            required value="<?php echo $_SESSION['username_eklinik']?>">
                                            <label for="username">Username</label>
                                            <div class="invalid-feedback">Masukkan username</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" id="floatingPassword" name="passwordlama" 
                                            required>
                                            <label for="username">Password Lama</label>
                                            <div class="invalid-feedback">Masukkan Password Lama</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" id="username" name="passwordbaru"
                                            required>
                                            <label for="username">Password Baru</label>
                                            <div class="invalid-feedback">Masukkan Password Baru</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" id="floatingPassword" name="repasswordbaru" 
                                            required>
                                            <label for="username">Ulangi Password Baru</label>
                                            <div class="invalid-feedback">Masukkan Ulangi Password Baru</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" name="ubah_password_validate" value="1">Save Changes</button>
                                </div>
                                
                              </form>
                            </div>
                         </div>
                       </div>
                      </div>
