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
ORDER BY u.role, nama
");

// Kelompokkan data berdasarkan role
$users_by_role = [
    'mahasiswa' => [],
    'petugas klinik' => [],
    'admin' => []
];

while ($record = mysqli_fetch_assoc($query)) {
    $users_by_role[$record['role']][] = $record;
}

// Hitung jumlah user per role
$count_mahasiswa = count($users_by_role['mahasiswa']);
$count_petugas = count($users_by_role['petugas klinik']);
$count_admin = count($users_by_role['admin']);
$total_users = $count_mahasiswa + $count_petugas + $count_admin;
?>

<style>
.user-card {
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: none;
    overflow: hidden;
}
.user-card .card-header {
    background: linear-gradient(135deg, #28a745 0%, #5a9f6aff 100%);
    color: white;
    padding: 1.25rem 1.5rem;
    border: none;
}
.badge-count {
    font-size: 0.75rem;
    padding: 0.3em 0.65em;
    margin-left: 0.5rem;
}
.nav-tabs {
    border-bottom: 2px solid #e9ecef;
}
.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
    border: none;
    padding: 0.75rem 1.25rem;
    transition: all 0.3s;
}
.nav-tabs .nav-link:hover {
    color: #495057;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}
.nav-tabs .nav-link.active {
    color: #28a745;
    font-weight: 600;
    background: white;
    border-bottom: 3px solid #28a745;
}
.role-badge {
    font-size: 0.8rem;
    padding: 0.4em 0.8em;
    border-radius: 20px;
    font-weight: 500;
}
.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745 0%, #7aa784ff 100%);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    margin-right: 0.75rem;
}
.search-box {
    border-radius: 50px;
    border: 2px solid #e9ecef;
    transition: all 0.3s;
}
.search-box:focus {
    border-color: #6b6e6cff;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}
.btn-action {
    width: 32px;
    height: 32px;
    padding: 0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}
.btn-action:hover {
    transform: translateY(-2px);
}
.table-hover tbody tr {
    transition: all 0.3s;
}
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
</style>

<div class="col-lg-9 mt-2">
    <div class="card user-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><i class="bi bi-people-fill"></i> Manajemen User</h5>
                    <small style="opacity: 0.9;">Total <?php echo $total_users; ?> pengguna terdaftar</small>
                </div>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#ModalTambahUser">
                    <i class="bi bi-plus-circle"></i> Tambah User
                </button>
            </div>
        </div>
        <div class="card-body p-4">
            <!-- Search Bar -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0" style="border-radius: 50px 0 0 50px;">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control search-box border-start-0" id="searchInput" 
                               placeholder="Cari berdasarkan username, nama, prodi, spesialis..."
                               style="border-radius: 0 50px 50px 0;">
                        <button class="btn btn-link text-secondary" type="button" id="clearSearch" style="display:none; margin-left: -45px; z-index: 10;">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4 text-end mt-2 mt-md-0">
                    <small class="text-muted d-block" id="searchResults"></small>
                </div>
            </div>

            <!-- Nav Tabs -->
            <ul class="nav nav-tabs mb-4" id="userTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                        <i class="bi bi-people-fill"></i> Semua 
                        <span class="badge bg-secondary badge-count"><?php echo $total_users; ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="mahasiswa-tab" data-bs-toggle="tab" data-bs-target="#mahasiswa" type="button">
                        <i class="bi bi-mortarboard-fill"></i> Mahasiswa 
                        <span class="badge bg-primary badge-count"><?php echo $count_mahasiswa; ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="petugas-tab" data-bs-toggle="tab" data-bs-target="#petugas" type="button">
                        <i class="bi bi-hospital"></i> Petugas 
                        <span class="badge bg-success badge-count"><?php echo $count_petugas; ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button">
                        <i class="bi bi-shield-fill-check"></i> Admin 
                        <span class="badge bg-warning badge-count"><?php echo $count_admin; ?></span>
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="userTabsContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    <?php 
                    $all_users = array_merge($users_by_role['mahasiswa'], $users_by_role['petugas klinik'], $users_by_role['admin']);
                    renderUserTable($all_users, true); 
                    ?>
                </div>
                <div class="tab-pane fade" id="mahasiswa" role="tabpanel">
                    <?php renderUserTable($users_by_role['mahasiswa'], false, 'mahasiswa'); ?>
                </div>
                <div class="tab-pane fade" id="petugas" role="tabpanel">
                    <?php renderUserTable($users_by_role['petugas klinik'], false, 'petugas klinik'); ?>
                </div>
                <div class="tab-pane fade" id="admin" role="tabpanel">
                    <?php renderUserTable($users_by_role['admin'], false, 'admin'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
function renderUserTable($users, $showRole = true, $roleFilter = null) {
    if (empty($users)) {
        $roleText = $roleFilter ? ucfirst($roleFilter) : 'User';
        echo '<div class="alert alert-info border-0"><i class="bi bi-info-circle me-2"></i>Belum ada data ' . $roleText . '</div>';
        return;
    }
    
    $role_color = [
        'mahasiswa' => 'primary',
        'petugas klinik' => 'success',
        'admin' => 'warning'
    ];
    ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Username</th>
                    <th width="25%">Nama</th>
                    <?php if ($showRole): ?>
                    <th width="15%">Role</th>
                    <?php endif; ?>
                    <th width="20%">Info Tambahan</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach ($users as $row):
                    $badge_color = $role_color[$row['role']] ?? 'secondary';
                    $initial = strtoupper(substr($row['nama'], 0, 1));
                    $extra_info = '';
                    if ($row['role'] == 'mahasiswa') {
                        $extra_info = '<small class="text-muted"><i class="bi bi-book me-1"></i>' . ($row['prodi'] ?? '-') . '</small>';
                    } elseif ($row['role'] == 'petugas klinik') {
                        $extra_info = '<small class="text-muted"><i class="bi bi-star me-1"></i>' . ($row['spesialis'] ?? '-') . '</small>';
                    }
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><strong><?php echo $row['username']; ?></strong></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="user-avatar"><?php echo $initial; ?></span>
                            <span><?php echo $row['nama']; ?></span>
                        </div>
                    </td>
                    <?php if ($showRole): ?>
                    <td>
                        <span class="badge bg-<?php echo $badge_color; ?> role-badge">
                            <?php echo ucfirst($row['role']); ?>
                        </span>
                    </td>
                    <?php endif; ?>
                    <td><?php echo $extra_info; ?></td>
                    <td>
                        <button class="btn btn-outline-info btn-action me-1" data-bs-toggle="modal" 
                                data-bs-target="#ModalView_<?php echo $row['username']; ?>" title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-action" data-bs-toggle="modal" 
                                data-bs-target="#ModalDeleteUser_<?php echo $row['username']; ?>" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>

<!-- Modal Tambah User -->
<div class="modal fade" id="ModalTambahUser" tabindex="-1">
    <div class="modal-dialog modal-xl modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill"></i> Tambah User Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate action="proses/proses_input_user.php" method="POST">
                    <input type="hidden" name="password" value="password">
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="username" name="username" required>
                                <label>Username</label>
                                <div class="invalid-feedback">Masukkan username</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="nama" name="nama" required>
                                <label>Nama Lengkap</label>
                                <div class="invalid-feedback">Masukkan nama</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" id="role" name="role" required>
                            <option selected hidden>Pilih Role User</option>
                            <option value="admin">Admin</option>
                            <option value="petugas klinik">Petugas Klinik</option>
                            <option value="mahasiswa">Mahasiswa</option>
                        </select>
                        <label>Role User</label>
                        <div class="invalid-feedback">Pilih role</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option selected hidden>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                <label>Jenis Kelamin</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                                <label>Nomor HP</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="alamat" name="alamat" style="height: 100px" required></textarea>
                        <label>Alamat</label>
                    </div>

                    <!-- Mahasiswa fields -->
                    <div id="mahasiswaFields" style="display:none;">
                        <div class="alert alert-primary border-0">
                            <i class="bi bi-mortarboard-fill me-2"></i><strong>Data Mahasiswa</strong>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="jurusan" name="jurusan">
                                        <option selected hidden value="">Pilih Jurusan</option>
                                        <option value="Teknik Sipil">Teknik Sipil</option>
                                        <option value="Teknik Kimia">Teknik Kimia</option>
                                        <option value="Teknologi Informasi dan Komputer">Teknologi Informasi dan Komputer</option>
                                        <option value="Teknik Mesin">Teknik Mesin</option>
                                        <option value="Teknik Elektro">Teknik Elektro</option>
                                        <option value="Bisnis">Bisnis</option>
                                    </select>
                                    <label>Jurusan</label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="prodi" name="prodi" disabled>
                                        <option selected hidden value="">Pilih Jurusan Terlebih Dahulu</option>
                                    </select>
                                    <label>Program Studi</label>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir">
                                    <label>Tanggal Lahir</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Petugas fields -->
                    <div id="petugasFields" style="display:none;">
                        <div class="alert alert-success border-0">
                            <i class="bi bi-hospital me-2"></i><strong>Data Petugas Klinik</strong>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="spesialis" name="spesialis">
                            <label>Bagian / Spesialis</label>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" name="input_user_validate" value="1">
                            <i class="bi bi-check-circle"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
const searchInput = document.getElementById('searchInput');
const clearSearchBtn = document.getElementById('clearSearch');
const searchResults = document.getElementById('searchResults');

searchInput.addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase().trim();
    const activeTab = document.querySelector('.tab-pane.active');
    const rows = activeTab.querySelectorAll('tbody tr');
    let visibleCount = 0;
    
    clearSearchBtn.style.display = searchTerm ? 'block' : 'none';
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    if (searchTerm) {
        searchResults.innerHTML = visibleCount === 0 
            ? '<i class="bi bi-exclamation-circle"></i> Tidak ditemukan'
            : `<i class="bi bi-check-circle"></i> ${visibleCount} dari ${rows.length} data`;
        searchResults.style.color = visibleCount === 0 ? '#dc3545' : '#198754';
    } else {
        searchResults.textContent = '';
    }
});

clearSearchBtn.addEventListener('click', function() {
    searchInput.value = '';
    searchInput.focus();
    clearSearchBtn.style.display = 'none';
    searchResults.textContent = '';
    document.querySelectorAll('tbody tr').forEach(row => row.style.display = '');
});

// Reset search on tab change
document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function() {
        searchInput.value = '';
        clearSearchBtn.style.display = 'none';
        searchResults.textContent = '';
    });
});

// Role based fields
document.getElementById('role').addEventListener('change', function() {
    const mhsFields = document.getElementById('mahasiswaFields');
    const petugasFields = document.getElementById('petugasFields');
    
    mhsFields.style.display = this.value === 'mahasiswa' ? 'block' : 'none';
    petugasFields.style.display = this.value === 'petugas klinik' ? 'block' : 'none';
});

// Prodi data
const prodiData = {
    "Teknik Sipil": ["D3 Teknologi Konstruksi Bangunan Air", "D3 Teknologi Konstruksi Bangunan Gedung", "D3 Teknologi Konstruksi Jalan dan Jembatan", "D4 Teknologi Rekayasa Konstruksi Jalan dan Jembatan"],
    "Teknik Kimia": ["D4 Teknologi Rekayasa Kimia Industri", "D4 Teknologi Pengolahan Minyak Dan Gas", "D3 Teknologi Kimia"],
    "Teknologi Informasi dan Komputer": ["D4 Teknik Informatika", "D4 Teknologi Rekayasa Komputer Jaringan", "D4 Teknologi Rekayasa Multimedia"],
    "Teknik Mesin": ["D3 Teknologi Mesin", "D4 Teknologi Rekayasa Manufaktur", "D4 Teknologi Rekayasa Pengelasan Dan Fabrikasi", "D3 Teknologi Industri"],
    "Teknik Elektro": ["D3 Teknologi Listrik", "D3 Teknologi Telekomunikasi", "D3 Teknologi Elektronika", "D4 Teknologi Rekayasa Pembangkit Energi", "D4 Teknologi Rekayasa Jaringan Telekomunikasi", "D4 Teknologi Rekayasa Instrumentasi Dan Kontrol", "D4 Teknologi Rekayasa Mekatronika"],
    "Bisnis": ["S2 Magister Keuangan", "D3 Akuntansi Lembaga Keuangan Syariah", "D4 Manajemen Keuangan Sektor Publik", "D3 Akuntansi", "D4 Administrasi Bisnis", "D4 Akuntansi Sektor Publik"]
};

document.getElementById('jurusan').addEventListener('change', function() {
    const prodiSelect = document.getElementById('prodi');
    prodiSelect.innerHTML = '<option selected hidden value="">Pilih Program Studi</option>';
    
    if (this.value && prodiData[this.value]) {
        prodiSelect.disabled = false;
        prodiData[this.value].forEach(prodi => {
            prodiSelect.add(new Option(prodi, prodi));
        });
    } else {
        prodiSelect.disabled = true;
    }
});

// Form validation
(() => {
    'use strict'
    document.querySelectorAll('.needs-validation').forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php
// Modal View dan Delete
$all_users = array_merge($users_by_role['mahasiswa'], $users_by_role['petugas klinik'], $users_by_role['admin']);
foreach ($all_users as $row):
    $jenis_kelamin = ($row['role'] == 'mahasiswa') ? $row['m_jenis_kelamin'] : (($row['role'] == 'petugas klinik') ? $row['pk_jenis_kelamin'] : '');
    $no_hp = ($row['role'] == 'mahasiswa') ? $row['m_no_hp'] : (($row['role'] == 'petugas klinik') ? $row['pk_no_hp'] : '');
    $alamat = ($row['role'] == 'mahasiswa') ? $row['m_alamat'] : (($row['role'] == 'petugas klinik') ? $row['pk_alamat'] : '');
?>
    <!-- Modal View -->
    <div class="modal fade" id="ModalView_<?php echo $row['username']; ?>" tabindex="-1">
        <div class="modal-dialog modal-xl modal-fullscreen-md-down">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="bi bi-person-circle"></i> Detail User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input disabled type="text" class="form-control" value="<?php echo $row['username'] ?>">
                                <label>Username</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input disabled type="text" class="form-control" value="<?php echo $row['nama'] ?>">
                                <label>Nama Lengkap</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input disabled type="text" class="form-control" value="<?php echo ucfirst($row['role']) ?>">
                        <label>Role User</label>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input disabled type="text" class="form-control" value="<?php echo $jenis_kelamin ?>">
                                <label>Jenis Kelamin</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input disabled type="text" class="form-control" value="<?php echo $no_hp ?>">
                                <label>Nomor HP</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input disabled type="text" class="form-control" value="<?php echo $alamat ?>">
                        <label>Alamat</label>
                    </div>

                    <?php if ($row['role'] == 'mahasiswa'): ?>
                    <div class="alert alert-primary border-0">
                        <strong><i class="bi bi-mortarboard-fill me-2"></i>Data Mahasiswa</strong>
                    </div>
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
                    <?php endif; ?>

                    <?php if ($row['role'] == 'petugas klinik'): ?>
                    <div class="alert alert-success border-0">
                        <strong><i class="bi bi-hospital me-2"></i>Data Petugas Klinik</strong>
                    </div>
                    <div class="form-floating mb-3">
                        <input disabled type="text" class="form-control" value="<?php echo $row['spesialis'] ?>">
                        <label>Bagian / Spesialis</label>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="ModalDeleteUser_<?php echo $row['username']; ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Hapus User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus user berikut?</p>
                    <div class="alert alert-warning border-0">
                        <p class="mb-1"><strong>Username:</strong> <?php echo $row['username']; ?></p>
                        <p class="mb-0"><strong>Nama:</strong> <?php echo $row['nama']; ?></p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="proses/proses_delete_user.php?username=<?php echo $row['username']; ?>" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>