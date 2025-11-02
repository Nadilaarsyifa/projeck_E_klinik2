<?php
include "proses/connect.php";

// Ambil user beserta data sesuai role
$query = mysqli_query($conn, "
 SELECT 
    u.username,
    u.role,
    CASE 
        WHEN u.role = 'petugas klinik' THEN pk.nama
        WHEN u.role = 'mahasiswa' THEN m.nama
        ELSE 'Admin'
    END AS nama,
    m.jenis_kelamin AS m_jenis_kelamin,
    m.no_hp AS m_no_hp,
    m.alamat AS m_alamat,
    m.jurusan AS jurusan,
    m.prodi AS prodi,
    m.tgl_lahir AS tgl_lahir,
    pk.jenis_kelamin AS pk_jenis_kelamin,
    pk.no_hp AS pk_no_hp,
    pk.alamat AS pk_alamat,
    pk.spesialis AS spesialis
FROM user u
LEFT JOIN petugas_klinik pk ON u.username = pk.id_petugas
LEFT JOIN mahasiswa m ON u.username = m.nim
");

$result = [];
while ($record = mysqli_fetch_assoc($query)) {
    $result[] = $record;
}
?>

<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            Halaman User
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col d-flex justify-content-end">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalTambahUser">Tambah User</button>
                </div>
            </div>

            <!-- Modal Tambah User Baru -->
            <div class="modal fade" id="ModalTambahUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah User</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form class="needs-validation" novalidate action="proses/proses_input_user.php" method="POST">
                                <input type="hidden" name="password" value="password">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="username" placeholder="username" name="username" required>
                                            <label for="username">Username</label>
                                            <div class="invalid-feedback">Masukkan username</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="nama" placeholder="Nama Lengkap" name="nama" required>
                                            <label for="nama">Nama Lengkap</label>
                                            <div class="invalid-feedback">Masukkan nama</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Role -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="role" name="role" required>
                                                <option selected hidden>Pilih Role User</option>
                                                <option value="admin">Admin</option>
                                                <option value="petugas klinik">Petugas Klinik</option>
                                                <option value="mahasiswa">Mahasiswa</option>
                                            </select>
                                            <label for="role">Role User</label>
                                            <div class="invalid-feedback">Pilih role</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Jenis kelamin, HP, alamat -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                                <option selected hidden>Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                            <label for="jenis_kelamin">Jenis Kelamin</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Nomor HP" required>
                                            <label for="no_hp">Nomor HP</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea class="form-control" placeholder="alamat" id="alamat" name="alamat" style="height: 100px" required></textarea>
                                    <label for="alamat">Alamat</label>
                                </div>

                                <!-- Mahasiswa fields -->
                                <div id="mahasiswaFields" style="display:none;">
                                    <hr>
                                    <h6>Data Mahasiswa</h6>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="jurusan" name="jurusan">
                                                <label for="jurusan">Jurusan</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="prodi" name="prodi">
                                                <label for="prodi">Program Studi</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-floating mb-3">
                                                <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir">
                                                <label for="tgl_lahir">Tanggal Lahir</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Petugas Klinik fields -->
                                <div id="petugasFields" style="display:none;">
                                    <hr>
                                    <h6>Data Petugas Klinik</h6>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="spesialis" name="spesialis">
                                        <label for="spesialis">Bagian / Spesialis</label>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" name="input_user_validate" value="1">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Script untuk menampilkan field tambahan -->
            <script>
                document.getElementById('role').addEventListener('change', function() {
                    const role = this.value;
                    const mhsFields = document.getElementById('mahasiswaFields');
                    const petugasFields = document.getElementById('petugasFields');
                    mhsFields.style.display = 'none';
                    petugasFields.style.display = 'none';
                    if (role === 'mahasiswa') {
                        mhsFields.style.display = 'block';
                    } else if (role === 'petugas klinik') {
                        petugasFields.style.display = 'block';
                    }
                });
            </script>

            <?php foreach ($result as $row):
                $jenis_kelamin = ($row['role'] == 'mahasiswa') ? $row['m_jenis_kelamin'] : (($row['role'] == 'petugas klinik') ? $row['pk_jenis_kelamin'] : '');
                $no_hp = ($row['role'] == 'mahasiswa') ? $row['m_no_hp'] : (($row['role'] == 'petugas klinik') ? $row['pk_no_hp'] : '');
                $alamat = ($row['role'] == 'mahasiswa') ? $row['m_alamat'] : (($row['role'] == 'petugas klinik') ? $row['pk_alamat'] : '');
                $modal_id = "ModalView_" . $row['username'];
            ?>

                <!-- Modal View -->
                <div class="modal fade" id="<?php echo $modal_id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Data User</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="needs-validation" novalidate>
                                    <input type="hidden" name="password" value="password">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-floating mb-3">
                                                <input disabled type="number" class="form-control" id="username" value="<?php echo $row['username'] ?>">
                                                <label for="username">Username</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-floating mb-3">
                                                <input disabled type="text" class="form-control" id="nama" value="<?php echo $row['nama'] ?>">
                                                <label for="nama">Nama Lengkap</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-floating mb-3">
                                                <input disabled type="text" class="form-control" id="role" value="<?php echo $row['role'] ?>">
                                                <label for="role">Role User</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-floating mb-3">
                                                <input disabled type="text" class="form-control" id="jenis_kelamin" value="<?php echo $jenis_kelamin ?>">
                                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-floating mb-3">
                                                <input disabled type="text" class="form-control" id="no_hp" value="<?php echo $no_hp ?>">
                                                <label for="no_hp">Nomor HP</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input disabled type="text" class="form-control" id="alamat" value="<?php echo $alamat ?>">
                                        <label for="alamat">Alamat</label>
                                    </div>

                                    <!-- Mahasiswa -->
                                    <div style="<?php echo ($row['role'] == 'mahasiswa') ? '' : 'display:none;'; ?>">
                                        <hr>
                                        <h6>Data Mahasiswa</h6>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <input disabled type="text" class="form-control" value="<?php echo $row['jurusan'] ?>">
                                                    <label>Jurusan</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <input disabled type="text" class="form-control" value="<?php echo $row['prodi'] ?>">
                                                    <label>Program Studi</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-floating mb-3">
                                                    <input disabled type="date" class="form-control" value="<?php echo $row['tgl_lahir'] ?>">
                                                    <label>Tanggal Lahir</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Petugas Klinik -->
                                    <div style="<?php echo ($row['role'] == 'petugas klinik') ? '' : 'display:none;'; ?>">
                                        <hr>
                                        <h6>Data Petugas Klinik</h6>
                                        <div class="form-floating mb-3">
                                            <input disabled type="text" class="form-control" value="<?php echo $row['spesialis'] ?>">
                                            <label>Bagian / Spesialis</label>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Akhir Modal View -->
                <!-- Modal Delete -->
                <div class="modal fade" id="ModalDeleteUser_<?php echo $row['username']; ?>" tabindex="-1" aria-labelledby="deleteLabel_<?php echo $row['username']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="deleteLabel_<?php echo $row['username']; ?>">Hapus User</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p>Apakah kamu yakin ingin menghapus user berikut?</p>
                                <p class="mb-1"><strong>Username:</strong> <?php echo $row['username']; ?></p>
                                <p class="mb-0"><strong>Nama:</strong> <?php echo $row['nama']; ?></p>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <a href="proses/proses_delete_user.php?username=<?php echo $row['username']; ?>" class="btn btn-danger">Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>







            <!-- akhir Modal delete -->

            <?php if (empty($result)) {
                echo "Data user tidak ada";
            } else { ?>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($result as $row):
                                $modal_id = "ModalView_" . $row['username'];
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['nama']; ?></td>
                                    <td><?php echo $row['role']; ?></td>
                                    <td class="d-flex">
                                        <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#<?php echo $modal_id; ?>"><i class="bi bi-eye"></i></button>
                                        <button class="btn btn-danger btn-sm me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalDeleteUser_<?php echo $row['username']; ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>



                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php } ?>
        </div>
    </div>
</div>

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
    })();
</script>