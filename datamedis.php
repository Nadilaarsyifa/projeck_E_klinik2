<?php
// File ini di-include dari main.php, jadi tidak perlu struktur HTML lengkap

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['username_eklinik'])) {
    header('Location: login');
    exit;
}
$nim_login = $_SESSION['username_eklinik'];

// Ambil data mahasiswa
$q_mhs = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE nim = '$nim_login'");
$mhs = mysqli_fetch_assoc($q_mhs);
if (!$mhs) die("<div class='alert alert-danger m-3'>Data mahasiswa tidak ditemukan.</div>");

// Ambil data medis
$q_medis = mysqli_query($conn, "SELECT * FROM data_medis_mahasiswa WHERE nim = '$nim_login'");
$data_medis = mysqli_fetch_assoc($q_medis);

// Proses Simpan / Update
$notif = '';
if (isset($_POST['simpan_medis'])) {
    $alergi = mysqli_real_escape_string($conn, $_POST['alergi'] ?? '');
    $gol_dar = mysqli_real_escape_string($conn, $_POST['gol_dar'] ?? '');
    $riwayat = mysqli_real_escape_string($conn, $_POST['riwayat_penyakit'] ?? '');
    $tinggi = !empty($_POST['tinggi_badan']) ? (float)$_POST['tinggi_badan'] : null;
    $berat = !empty($_POST['berat_badan']) ? (float)$_POST['berat_badan'] : null;
    $alat = mysqli_real_escape_string($conn, $_POST['alat_bantu'] ?? '');
    $kontak = mysqli_real_escape_string($conn, $_POST['kontak_darurat'] ?? '');

    if ($data_medis) {
        $sql = "UPDATE data_medis_mahasiswa SET 
                alergi='$alergi', gol_dar='$gol_dar', riwayat_penyakit='$riwayat',
                tinggi_badan='$tinggi', berat_badan='$berat', alat_bantu='$alat', kontak_darurat='$kontak'
                WHERE nim='$nim_login'";
    } else {
        $sql = "INSERT INTO data_medis_mahasiswa 
                (nim, alergi, gol_dar, riwayat_penyakit, tinggi_badan, berat_badan, alat_bantu, kontak_darurat)
                VALUES ('$nim_login', '$alergi', '$gol_dar', '$riwayat', '$tinggi', '$berat', '$alat', '$kontak')";
    }

    if (mysqli_query($conn, $sql)) {
        $notif = 'success';
        $q_medis = mysqli_query($conn, "SELECT * FROM data_medis_mahasiswa WHERE nim = '$nim_login'");
        $data_medis = mysqli_fetch_assoc($q_medis);
    } else {
        $notif = 'error';
    }
}
?>

<!-- CSS Khusus untuk Halaman Data Medis -->
<style>
/* Card Styling dengan Gradient */
.card { 
    border-radius:1.5rem; 
    box-shadow:0 15px 35px rgba(0,0,0,0.12); 
    border:none; 
    margin-bottom:2rem; 
    overflow:hidden;
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from { opacity:0; transform:translateY(30px); }
    to { opacity:1; transform:translateY(0); }
}

.card-header { 
    background: linear-gradient(135deg, #0b9d4f 0%, #198754 100%);
    color:#fff; 
    font-weight:700; 
    font-size:1.4rem; 
    padding:1.5rem 2rem;
    border:none;
    box-shadow: 0 4px 15px rgba(25,135,84,0.3);
}

.card-header i {
    font-size:1.6rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.card-body {
    padding:2rem 2.5rem;
    background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);
}

/* Form Group dengan Hover Effect */
.mb-3.row {
    padding:0.8rem 0;
    border-radius:1rem;
    transition: all 0.3s ease;
    position: relative;
}

.mb-3.row:hover {
    background: rgba(25,135,84,0.03);
    transform: translateX(5px);
}

/* Label Styling */
.form-label { 
    font-weight:600; 
    color:#2c3e50; 
    font-size:1rem;
    display:flex;
    align-items:center;
    transition: color 0.3s;
}

.mb-3.row:hover .form-label {
    color:#198754;
}

/* Input Group Modern */
.input-group {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border-radius:1rem;
    overflow:hidden;
    transition: all 0.3s ease;
}

.input-group:hover {
    box-shadow: 0 6px 20px rgba(25,135,84,0.15);
}

.input-group-text { 
    background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
    border:none;
    color:#198754;
    font-size:1.2rem;
    padding:0.75rem 1rem;
    min-width:50px;
    display:flex;
    justify-content:center;
    align-items:center;
}

.form-control, .form-select { 
    border:2px solid transparent;
    padding:0.75rem 1.2rem; 
    background:#fff; 
    transition: all 0.3s ease;
    font-size:1rem;
}

.form-control:focus, .form-select:focus { 
    border-color:#198754;
    box-shadow:0 0 0 0.3rem rgba(25,135,84,0.15);
    background:#f8fff8;
}

.form-control[readonly] {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color:#6c757d;
    font-weight:500;
}

/* Tombol dengan Animasi */
.tombol-bawah { 
    display:flex; 
    justify-content:flex-end; 
    gap:1rem; 
    margin-top:2rem;
    padding-top:1.5rem;
    border-top:2px solid #e9ecef;
}

.tombol-ukuran { 
    min-width:120px; 
    padding:0.7rem 1.8rem; 
    font-weight:600;
    font-size:1rem;
    border-radius:50rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    position:relative;
    overflow:hidden;
}

.tombol-ukuran::before {
    content:'';
    position:absolute;
    top:50%;
    left:50%;
    width:0;
    height:0;
    border-radius:50%;
    background:rgba(255,255,255,0.3);
    transform:translate(-50%, -50%);
    transition:width 0.6s, height 0.6s;
}

.tombol-ukuran:hover::before {
    width:300px;
    height:300px;
}

.tombol-ukuran:hover {
    transform:translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.tombol-ukuran:active {
    transform:translateY(-1px);
}

.btn-success {
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
    border:none;
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
    border:none;
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #adb5bd 100%);
    border:none;
}

.tombol-ukuran i {
    transition: transform 0.3s;
}

.tombol-ukuran:hover i {
    transform: scale(1.2);
}

/* Alert dengan Animasi Lebih Smooth */
.alert-custom { 
    border-radius:1rem; 
    padding:1.2rem 1.8rem; 
    max-width:550px;
    animation:slideDownBounce 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    box-shadow:0 8px 25px rgba(0,0,0,0.15);
    border:none;
    font-weight:500;
}

@keyframes slideDownBounce { 
    0% { transform:translateY(-100px); opacity:0; }
    60% { transform:translateY(10px); opacity:1; }
    80% { transform:translateY(-5px); }
    100% { transform:translateY(0); }
}

.alert-success {
    background: linear-gradient(135deg, #d1e7dd 0%, #badbcc 100%);
    color:#0f5132;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f1aeb5 100%);
    color:#842029;
}

/* Scroll Bar Custom */
.card-body::-webkit-scrollbar {
    width:8px;
}

.card-body::-webkit-scrollbar-track {
    background:#f1f1f1;
    border-radius:10px;
}

.card-body::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #198754 0%, #20c997 100%);
    border-radius:10px;
}

.card-body::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0b9d4f 0%, #198754 100%);
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding:1.5rem;
    }
    
    .form-label {
        font-size:0.9rem;
        margin-bottom:0.5rem;
    }
    
    .tombol-bawah {
        flex-direction:column;
    }
    
    .tombol-ukuran {
        width:100%;
    }
}
</style>

<!-- Alert Notifikasi -->
<?php if ($notif==='success'): ?>
<div class="alert alert-success alert-dismissible fade show alert-custom position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index:9999;" role="alert">
    <i class="bi bi-check-circle me-2"></i><strong>Berhasil!</strong> Data medis telah disimpan.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php elseif($notif==='error'): ?>
<div class="alert alert-danger alert-dismissible fade show alert-custom position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index:9999;" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i><strong>Gagal!</strong> Terjadi kesalahan.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Content -->
<div class="col-lg-9 mt-2">
    <div class="card">
        <div class="card-header">
            <i class="bi bi-clipboard-plus me-2"></i>Data Medis
        </div>
        <div class="card-body" style="max-height:75vh; overflow-y:auto;">

            <form method="POST" id="formMedis">

                <?php
                $fields = [
                    'nim' => ['label'=>'NIM','icon'=>'bi-card-text','readonly'=>true,'value'=>$mhs['nim']],
                    'nama' => ['label'=>'Nama','icon'=>'bi-person','readonly'=>true,'value'=>$mhs['nama']],
                    'alergi' => ['label'=>'Alergi','icon'=>'bi-exclamation-triangle','value'=>$data_medis['alergi']??''],
                    'gol_dar' => ['label'=>'Golongan Darah','icon'=>'bi-droplet','select'=>['A','B','AB','O'],'value'=>$data_medis['gol_dar']??''],
                    'riwayat_penyakit' => ['label'=>'Riwayat Penyakit','icon'=>'bi-file-medical','value'=>$data_medis['riwayat_penyakit']??''],
                    'tinggi_badan' => ['label'=>'Tinggi Badan (cm)','icon'=>'bi-arrows-expand','value'=>$data_medis['tinggi_badan']??'','type'=>'number','step'=>'0.1'],
                    'berat_badan' => ['label'=>'Berat Badan (kg)','icon'=>'bi-speedometer','value'=>$data_medis['berat_badan']??'','type'=>'number','step'=>'0.1'],
                    'alat_bantu' => ['label'=>'Alat Bantu','icon'=>'bi-bandaid','value'=>$data_medis['alat_bantu']??''],
                    'kontak_darurat' => ['label'=>'Kontak Darurat','icon'=>'bi-telephone','value'=>$data_medis['kontak_darurat']??'']
                ];

                foreach($fields as $name=>$f){
                    echo '<div class="mb-3 row align-items-center">';
                    echo '<label class="col-sm-4 form-label">'.$f['label'].'</label>';
                    echo '<div class="col-sm-8"><div class="input-group">';
                    echo '<span class="input-group-text"><i class="'.$f['icon'].'"></i></span>';

                    if(isset($f['select'])){
                        echo '<select name="'.$name.'" class="form-select" '.($f['readonly']??false?'disabled':'').'>';
                        echo '<option value="">-- Pilih --</option>';
                        foreach($f['select'] as $opt){
                            $sel = ($f['value']==$opt)?'selected':'';
                            echo "<option value='$opt' $sel>$opt</option>";
                        }
                        echo '</select>';
                    }else{
                        $type = $f['type']??'text';
                        $step = $f['step']??'';
                        echo '<input type="'.$type.'" '.($step?'step="'.$step.'"':'').' name="'.$name.'" class="form-control" value="'.htmlspecialchars($f['value']).'" '.($f['readonly']??false?'readonly':'').'>';
                    }

                    echo '</div></div></div>';
                }
                ?>

                <!-- Tombol -->
                <div class="tombol-bawah d-flex gap-2">
                    <button type="button" class="btn btn-success tombol-ukuran" id="btnEdit">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </button>
                    <div id="tombolSimpan" class="d-flex gap-2" style="display:none;">
                        <button type="submit" name="simpan_medis" class="btn btn-primary tombol-ukuran">
                            <i class="bi bi-save me-1"></i>Simpan
                        </button>
                        <button type="button" class="btn btn-secondary tombol-ukuran" onclick="location.reload()">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- JavaScript Khusus untuk Halaman Data Medis -->
<script>
// Fungsi Edit - hanya jalankan jika elemen ada
if(document.getElementById('btnEdit')) {
    document.getElementById('btnEdit').addEventListener('click', function(){
        document.querySelectorAll('input[readonly]:not([name="nim"]):not([name="nama"]), select[disabled]').forEach(el=>{
            el.removeAttribute('readonly');
            el.removeAttribute('disabled');
            el.classList.add('border-primary','shadow-sm');
        });
        this.style.display='none';
        document.getElementById('tombolSimpan').style.display='flex';
    });
}

// Auto close alert
document.querySelectorAll('.alert-custom').forEach(alert=>{
    setTimeout(()=>{
        const bsAlert = bootstrap.Alert.getInstance(alert) || new bootstrap.Alert(alert);
        bsAlert.close();
    }, 4000);
});
</script>