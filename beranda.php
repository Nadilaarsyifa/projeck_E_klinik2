<div class="col-lg-9 mt-2">
    <!-- Carousel -->
    <div id="carouselExampleCaptions" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner rounded">
            <div class="carousel-item active">
                <img src="assets/img/gambar klinik.jpg" class="d-block w-100" style="height: 300px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Selamat Datang di E-Klinik</h5>
                    <p>Layanan kesehatan mahasiswa dan civitas akademika.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/img/fasilitas.jpg" class="d-block w-100" style="height: 300px; object-fit: cover;" alt="Fasilitas Lengkap">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                    <h5>Fasilitas Klinik Lengkap</h5>
                    <p>Tersedia ruang periksa, layanan psikologi, dan ruang tunggu yang nyaman.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/img/tenaga medis.jpg" class="d-block w-100" style="height: 300px; object-fit: cover;" alt="Tenaga Medis Profesional">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Tenaga Medis Profesional</h5>
                    <p>Dokter dan perawat terlatih siap melayani Anda.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Sebelumnya</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Selanjutnya</span>
        </button>
    </div>
    <!-- Akhir Carousel -->

    <!-- Informasi Klinik (Desain Baru) -->
    <div class="card shadow-sm mb-4" style="background: linear-gradient(135deg, #14532d 0%, #1e7e34 100%); border-radius: 12px; color: white;">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <i class="bi bi-heart-pulse-fill" style="font-size: 4rem; opacity: 0.8;"></i>
                </div>
                <div class="col-md-9">
                    <h4 class="mb-3 fw-bold">Tentang <span style="color:#d4edda;">E-Klinik</span></h4>
                    <p style="font-size: 1.1rem;">
                        <strong>E-Klinik Kampus</strong> adalah sistem pelayanan kesehatan terintegrasi yang dikhususkan bagi mahasiswa dan staf kampus.
                        Dengan layanan ini, Anda bisa mendapatkan kemudahan dalam <span class="fw-semibold">pendaftaran</span>, <span class="fw-semibold">konsultasi</span>, dan <span class="fw-semibold">pemantauan kesehatan</span> tanpa harus antre lama.
                    </p>
                    <p style="font-size: 1.1rem;">
                        Klinik kami didukung oleh tenaga medis profesional dengan <span class="fw-semibold">fasilitas lengkap</span> yang siap melayani kebutuhan kesehatan Anda secara optimal.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Dokter (Versi dengan Foto) -->
    <div class="card shadow-sm mb-4">
        <div class="card-header text-white" style="background-color: #14532d;">
            <i class="bi bi-calendar-check me-2"></i> Jadwal Dokter
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php
                $jadwal = [
                    [
                        'foto' => 'assets/img/dokter1.jpeg',
                        'nama' => 'Dr. Andi Wijaya',
                        'spesialis' => 'Dokter Umum',
                        'hari' => 'Senin, Rabu, Jumat',
                        'jam' => '08:00 - 12:00'
                    ],
                    [
                        'foto' => 'assets/img/dokter2.png',
                        'nama' => 'Dr. Nilam Cahaya Safitri',
                        'spesialis' => 'Psikologi',
                        'hari' => 'Selasa & Kamis',
                        'jam' => '09:00 - 14:00'
                    ]
                ];

                foreach ($jadwal as $dokter) {
                    echo '
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="'.$dokter['foto'].'" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="'.$dokter['nama'].'">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title mb-1">'.$dokter['nama'].'</h5>
                                        <p class="card-text mb-1 text-muted">'.$dokter['spesialis'].'</p>
                                        <p class="card-text mb-1"><i class="bi bi-calendar-event me-1"></i>'.$dokter['hari'].'</p>
                                        <p class="card-text"><i class="bi bi-clock me-1"></i>'.$dokter['jam'].'</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>

<!-- FAQ (Pertanyaan Umum) -->
<div class="card shadow-sm mt-4 mb-4">
  <div class="card-header text-white" style="background-color: #14532d;">
    <i class="bi bi-question-circle-fill me-2"></i> FAQ - Pertanyaan Umum
  </div>
  <div class="card-body">
    <div class="accordion" id="faqAccordion">
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
        </h2>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Apakah konsultasi online dikenakan biaya?
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Konsultasi online disediakan gratis untuk seluruh mahasiswa dan staf kampus.
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Jam operasional klinik?
          </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Klinik buka Senin sampai Jumat, pukul 08:00 sampai 16:00.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Layanan Unggulan -->
<div class="card shadow-sm mt-4 mb-4">
  <div class="card-header text-white" style="background-color: #14532d;">
    <i class="bi bi-stars me-2"></i> Layanan Unggulan
  </div>
  <div class="card-body">
    <div class="row text-center">
      <div class="col-md-4 mb-3">
        <i class="bi bi-phone-vibrate" style="font-size: 2rem; color:#14532d;"></i>
        <h6 class="mt-2">Konsultasi Online</h6>
        <p class="text-muted">Konsultasi dari mana saja, kapan saja.</p>
      </div>
      <div class="col-md-4 mb-3">
        <i class="bi bi-people-fill" style="font-size: 2rem; color:#14532d;"></i>
        <h6 class="mt-2">Tenaga Medis Profesional</h6>
        <p class="text-muted">Dokter dan perawat berpengalaman.</p>
      </div>
    </div>
  </div>
</div>


