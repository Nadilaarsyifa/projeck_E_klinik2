<?php
include "proses/connect.php";

// Query jadwal dokter dari database
$query_jadwal = mysqli_query($conn, "
    SELECT pk.nama, pk.spesialis, 
           GROUP_CONCAT(DISTINCT j.hari ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat') SEPARATOR ', ') as hari,
           MIN(j.jam_mulai) as jam_mulai,
           MAX(j.jam_selesai) as jam_selesai
    FROM petugas_klinik pk
    INNER JOIN jadwal_petugas_klinik j ON pk.id_petugas = j.id_petugas
    GROUP BY pk.id_petugas, pk.nama, pk.spesialis
    ORDER BY pk.nama
");
?>

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
                <img src="assets/img/poli2.jpg" class="d-block w-100" style="height: 300px; object-fit: cover;">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Selamat Datang di E-Klinik</h5>
                    <p>Layanan kesehatan mahasiswa dan civitas akademika.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/img/klinik.jpg" class="d-block w-100" style="height: 300px; object-fit: cover;" alt="Fasilitas Lengkap">
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

    <!-- Jadwal Dokter (Otomatis dari Database) -->
    <div class="card shadow-sm mb-4" style="border: none; border-radius: 12px;">
        <div class="card-header text-white" style="background-color: #14532d; border-radius: 12px 12px 0 0; padding: 15px 20px;">
            <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i> Jadwal Dokter</h5>
        </div>
        <div class="card-body" style="background-color: #f8f9fa; padding: 25px;">
            <?php 
            // Query jadwal per hari
            $query_jadwal_detail = mysqli_query($conn, "
                SELECT j.hari, j.jam_mulai, j.jam_selesai, pk.nama, pk.spesialis
                FROM jadwal_petugas_klinik j
                INNER JOIN petugas_klinik pk ON j.id_petugas = pk.id_petugas
                ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'), j.jam_mulai
            ");
            
            // Kelompokkan jadwal per hari
            $jadwal_per_hari = [];
            while ($row = mysqli_fetch_assoc($query_jadwal_detail)) {
                $jadwal_per_hari[$row['hari']][] = $row;
            }
            
            if (empty($jadwal_per_hari)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Belum ada jadwal dokter tersedia</p>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php 
                    $hari_kerja = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                    foreach ($hari_kerja as $hari): 
                        if (isset($jadwal_per_hari[$hari])):
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100" style="border: 2px solid #16a34a; border-radius: 10px; overflow: hidden;">
                            <div class="card-header text-white text-center" style="background-color: #16a34a; padding: 12px; border: none;">
                                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-day me-2"></i><?= $hari ?></h6>
                            </div>
                            <div class="card-body bg-white" style="padding: 20px;">
                                <?php 
                                $count = count($jadwal_per_hari[$hari]);
                                $index = 0;
                                foreach ($jadwal_per_hari[$hari] as $jadwal): 
                                    $index++;
                                ?>
                                <div class="<?= $index < $count ? 'mb-3 pb-3 border-bottom' : '' ?>">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-clock" style="color: #6b7280; font-size: 1.1rem;"></i>
                                        <span class="ms-2 fw-bold" style="color: #1f2937; font-size: 0.95rem;">
                                            <?= substr($jadwal['jam_mulai'], 0, 5) ?> - <?= substr($jadwal['jam_selesai'], 0, 5) ?>
                                        </span>
                                    </div>
                                    <div class="ms-4">
                                        <div class="fw-bold" style="color: #0ea5e9; font-size: 1rem;">
                                            <?= htmlspecialchars($jadwal['nama']) ?>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.875rem;">
                                            <?= htmlspecialchars($jadwal['spesialis']) ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            <?php endif; ?>
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
                <div class="col-md-4 mb-3">
                    <i class="bi bi-heart-pulse" style="font-size: 2rem; color:#14532d;"></i>
                    <h6 class="mt-2">Pelayanan Prima</h6>
                    <p class="text-muted">Fasilitas lengkap dan modern.</p>
                </div>
            </div>
        </div>
    </div>
</div>