<?php
include "proses/connect.php";

// Cek koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Query untuk mengambil data medis mahasiswa beserta data mahasiswa
$query = mysqli_query($conn, "SELECT dm.*, m.nama, m.jurusan, m.prodi, m.tgl_lahir, m.jenis_kelamin, m.alamat, m.no_hp 
                               FROM data_medis_mahasiswa dm 
                               LEFT JOIN mahasiswa m ON dm.nim = m.nim 
                               ORDER BY dm.id_data_medis DESC");

// Cek apakah query berhasil
if (!$query) {
    die("Error Query: " . mysqli_error($conn) . "<br>SQL: SELECT dm.*, m.nama, m.jurusan, m.prodi, m.jenis_kelamin, m.no_hp FROM data_medis_mahasiswa dm LEFT JOIN mahasiswa m ON dm.nim = m.nim ORDER BY dm.id_data_medis DESC");
}

$jumlah_data = mysqli_num_rows($query);
?>

<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            <i class="bi bi-clipboard-pulse"></i> Data Medis Mahasiswa
        </div>
        <div class="card-body">
            <!-- Search Box -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari NIM, Nama, atau Prodi...">
                </div>
                <div class="col-md-6 text-end">
                    <span class="badge bg-primary">Total Data: <?php echo $jumlah_data; ?></span>
                </div>
            </div>

            <?php if ($jumlah_data == 0) { ?>
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle"></i> Belum ada data medis mahasiswa.
                </div>
            <?php } else { ?>
                <!-- Tabel Data Medis -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="dataTable">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Golongan Darah</th>
                                <th>Tinggi (cm)</th>
                                <th>Berat (kg)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_array($query)) {
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nim']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($row['prodi'] ?? '-'); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger"><?php echo htmlspecialchars($row['gol_dar'] ?? '-'); ?></span>
                                    </td>
                                    <td class="text-center"><?php echo $row['tinggi_badan'] ?? '-'; ?></td>
                                    <td class="text-center"><?php echo $row['berat_badan'] ?? '-'; ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail<?php echo $row['id_data_medis']; ?>" title="Lihat Detail">
                                            <i class="bi bi-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal Detail - Dipindah ke luar loop -->
                <?php
                mysqli_data_seek($query, 0); // Reset pointer ke awal
                while ($row = mysqli_fetch_array($query)) {
                ?>

                                <!-- Modal Detail -->
                                <div class="modal fade" id="modalDetail<?php echo $row['id_data_medis']; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $row['id_data_medis']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title" id="modalLabel<?php echo $row['id_data_medis']; ?>">
                                                    <i class="bi bi-file-medical"></i> Detail Data Medis - <?php echo htmlspecialchars($row['nama'] ?? $row['nim']); ?>
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6 class="text-primary mb-3"><i class="bi bi-person-fill"></i> Data Pribadi</h6>
                                                <table class="table table-borderless table-sm">
                                                    <tr>
                                                        <td width="200"><strong>NIM</strong></td>
                                                        <td>: <?php echo htmlspecialchars($row['nim']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Nama Lengkap</strong></td>
                                                        <td>: <?php echo htmlspecialchars($row['nama'] ?? '-'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tanggal Lahir</strong></td>
                                                        <td>: <?php echo $row['tgl_lahir'] ? date('d-m-Y', strtotime($row['tgl_lahir'])) : '-'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Jenis Kelamin</strong></td>
                                                        <td>: <?php echo htmlspecialchars($row['jenis_kelamin'] ?? '-'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Jurusan</strong></td>
                                                        <td>: <?php echo htmlspecialchars($row['jurusan'] ?? '-'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Program Studi</strong></td>
                                                        <td>: <?php echo htmlspecialchars($row['prodi'] ?? '-'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>No. HP</strong></td>
                                                        <td>: <?php echo htmlspecialchars($row['no_hp'] ?? '-'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Alamat</strong></td>
                                                        <td>: <?php echo nl2br(htmlspecialchars($row['alamat'] ?? '-')); ?></td>
                                                    </tr>
                                                </table>

                                                <hr>

                                                <h6 class="text-success mb-3"><i class="bi bi-heart-pulse-fill"></i> Data Medis</h6>
                                                <table class="table table-borderless table-sm">
                                                    <tr>
                                                        <td width="200"><strong>Golongan Darah</strong></td>
                                                        <td>: <span class="badge bg-danger"><?php echo htmlspecialchars($row['gol_dar'] ?? '-'); ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tinggi Badan</strong></td>
                                                        <td>: <?php echo $row['tinggi_badan'] ?? '-'; ?> cm</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Berat Badan</strong></td>
                                                        <td>: <?php echo $row['berat_badan'] ?? '-'; ?> kg</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Alergi</strong></td>
                                                        <td>: <?php echo nl2br(htmlspecialchars($row['alergi'] ?? 'Tidak ada')); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Riwayat Penyakit</strong></td>
                                                        <td>: <?php echo nl2br(htmlspecialchars($row['riwayat_penyakit'] ?? 'Tidak ada')); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Alat Bantu</strong></td>
                                                        <td>: <?php echo htmlspecialchars($row['alat_bantu'] ?? 'Tidak ada'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Kontak Darurat</strong></td>
                                                        <td>: <?php echo htmlspecialchars($row['kontak_darurat'] ?? '-'); ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle"></i> Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Script Bootstrap Bundle (JS + Popper) -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

                <!-- JavaScript untuk Search -->
                <script>
                document.getElementById('searchInput').addEventListener('keyup', function() {
                    let filter = this.value.toUpperCase();
                    let table = document.getElementById('dataTable');
                    let tr = table.getElementsByTagName('tr');

                    for (let i = 1; i < tr.length; i++) {
                        let td = tr[i].getElementsByTagName('td');
                        let found = false;
                        
                        for (let j = 0; j < td.length; j++) {
                            if (td[j]) {
                                let txtValue = td[j].textContent || td[j].innerText;
                                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                    found = true;
                                    break;
                                }
                            }
                        }
                        
                        if (found) {
                            tr[i].style.display = '';
                        } else {
                            tr[i].style.display = 'none';
                        }
                    }
                });
                </script>