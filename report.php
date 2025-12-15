<?php
// Report untuk Admin - Menampilkan semua data konsultasi

// 1. Data Kunjungan per Bulan (12 bulan terakhir) - SEMUA MAHASISWA
$bulan_labels = [];
$bulan_data = [];
for($i = 11; $i >= 0; $i--) {
    $bulan = date('Y-m', strtotime("-$i month"));
    $bulan_labels[] = date('M Y', strtotime("-$i month"));
    
    $query_bulan = "SELECT COUNT(*) as total FROM konsultasi 
                    WHERE DATE_FORMAT(tgl_konsultasi, '%Y-%m') = '$bulan'";
    $result_bulan = mysqli_query($conn, $query_bulan);
    $row_bulan = mysqli_fetch_assoc($result_bulan);
    $bulan_data[] = (int)$row_bulan['total'];
}

// Debug - cek total konsultasi SEMUA
$debug_total = "SELECT COUNT(*) as total FROM konsultasi";
$debug_result = mysqli_query($conn, $debug_total);
$debug_row = mysqli_fetch_assoc($debug_result);
$total_konsultasi = $debug_row['total'];

// 2. Data Diagnosa Penyakit Terbanyak - SEMUA MAHASISWA
$query_diagnosa = "SELECT t.diagnosa, COUNT(*) as jumlah 
                   FROM tindak_lanjut t
                   WHERE t.diagnosa IS NOT NULL 
                   AND TRIM(t.diagnosa) != ''
                   GROUP BY t.diagnosa
                   ORDER BY jumlah DESC
                   LIMIT 5";
$result_diagnosa = mysqli_query($conn, $query_diagnosa);
$diagnosa_labels = [];
$diagnosa_data = [];
while($row = mysqli_fetch_assoc($result_diagnosa)) {
    $diagnosa_labels[] = $row['diagnosa'];
    $diagnosa_data[] = (int)$row['jumlah'];
}

// 3. Data Status Konsultasi (Selesai vs Menunggu) - SEMUA MAHASISWA
$query_selesai = "SELECT COUNT(*) as total FROM konsultasi k
                  INNER JOIN tindak_lanjut t ON k.id_konsultasi = t.id_konsultasi";
$result_selesai = mysqli_query($conn, $query_selesai);
$row_selesai = mysqli_fetch_assoc($result_selesai);
$total_selesai = (int)$row_selesai['total'];

$query_menunggu = "SELECT COUNT(*) as total FROM konsultasi k
                   WHERE k.id_konsultasi NOT IN (SELECT id_konsultasi FROM tindak_lanjut WHERE id_konsultasi IS NOT NULL)";
$result_menunggu = mysqli_query($conn, $query_menunggu);
$row_menunggu = mysqli_fetch_assoc($result_menunggu);
$total_menunggu = (int)$row_menunggu['total'];

// Data tambahan untuk statistik
$query_total_mahasiswa = "SELECT COUNT(DISTINCT nim) as total FROM konsultasi";
$result_total_mahasiswa = mysqli_query($conn, $query_total_mahasiswa);
$row_total_mahasiswa = mysqli_fetch_assoc($result_total_mahasiswa);
$total_mahasiswa_konsultasi = $row_total_mahasiswa['total'];

$query_total_petugas = "SELECT COUNT(DISTINCT id_petugas) as total FROM konsultasi WHERE id_petugas IS NOT NULL";
$result_total_petugas = mysqli_query($conn, $query_total_petugas);
$row_total_petugas = mysqli_fetch_assoc($result_total_petugas);
$total_petugas_aktif = $row_total_petugas['total'];
?>

<!-- CSS Khusus untuk Halaman Report -->
<style>
/* Card Report Styling */
.card-report { 
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

.card-report .card-header { 
    background: linear-gradient(135deg, #0b9d4f 0%, #198754 100%);
    color:#fff; 
    font-weight:700; 
    font-size:1.4rem; 
    padding:1.5rem 2rem;
    border:none;
    box-shadow: 0 4px 15px rgba(25,135,84,0.3);
}

.card-report .card-header i {
    font-size:1.6rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.card-report .card-body {
    padding:2rem 2.5rem;
    background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);
}

/* Summary Cards */
.summary-cards {
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));
    gap:1.5rem;
    margin-bottom:2rem;
}

.summary-card {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    padding:1.5rem;
    border-radius:1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border-left:4px solid;
}

.summary-card:nth-child(1) { border-left-color:#198754; }
.summary-card:nth-child(2) { border-left-color:#0dcaf0; }
.summary-card:nth-child(3) { border-left-color:#ffc107; }
.summary-card:nth-child(4) { border-left-color:#dc3545; }

.summary-card:hover {
    transform:translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.summary-card .icon {
    font-size:2.5rem;
    margin-bottom:0.8rem;
}

.summary-card:nth-child(1) .icon { color:#198754; }
.summary-card:nth-child(2) .icon { color:#0dcaf0; }
.summary-card:nth-child(3) .icon { color:#ffc107; }
.summary-card:nth-child(4) .icon { color:#dc3545; }

.summary-card .number {
    font-size:2.5rem;
    font-weight:700;
    color:#2c3e50;
    display:block;
    margin-bottom:0.3rem;
}

.summary-card .label {
    font-size:0.95rem;
    color:#6c757d;
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:0.5px;
}

/* Chart Card Styling */
.chart-card {
    background: #fff;
    border-radius:1.2rem;
    padding:1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    margin-bottom:1.5rem;
    transition: all 0.3s ease;
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from { opacity:0; }
    to { opacity:1; }
}

.chart-card:hover {
    transform:translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.chart-title {
    font-size:1.2rem;
    font-weight:700;
    color:#2c3e50;
    margin-bottom:1.5rem;
    padding-bottom:1rem;
    border-bottom:3px solid #198754;
    display:flex;
    align-items:center;
    gap:0.8rem;
}

.chart-title i {
    font-size:1.5rem;
    color:#198754;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform:translateY(0); }
    50% { transform:translateY(-5px); }
}

/* Chart Container */
.chart-container {
    position:relative;
    height:350px;
    margin:1rem 0;
}

.chart-container-pie {
    position:relative;
    height:300px;
    margin:1rem auto;
    max-width:400px;
}

/* Info Box untuk Statistik */
.info-stats {
    display:flex;
    justify-content:space-around;
    margin-top:1.5rem;
    gap:1rem;
    flex-wrap:wrap;
}

.stat-box {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding:1rem 1.5rem;
    border-radius:1rem;
    text-align:center;
    flex:1;
    min-width:150px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border-left:4px solid #198754;
}

.stat-box:hover {
    transform:translateY(-3px);
    box-shadow: 0 6px 20px rgba(25,135,84,0.15);
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}

.stat-box .stat-number {
    font-size:2rem;
    font-weight:700;
    color:#198754;
    display:block;
    margin-bottom:0.3rem;
}

.stat-box .stat-label {
    font-size:0.9rem;
    color:#6c757d;
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:0.5px;
}

/* Empty State */
.empty-chart {
    text-align:center;
    padding:3rem 1rem;
    color:#6c757d;
}

.empty-chart i {
    font-size:4rem;
    color:#cbd5e0;
    margin-bottom:1rem;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform:translateY(0); }
    50% { transform:translateY(-15px); }
}

.empty-chart p {
    font-size:1.1rem;
    font-weight:500;
}

/* Responsive */
@media (max-width: 768px) {
    .card-report .card-body {
        padding:1.5rem;
    }
    
    .summary-cards {
        grid-template-columns:1fr;
    }
    
    .chart-container {
        height:280px;
    }
    
    .chart-container-pie {
        height:250px;
    }
    
    .info-stats {
        flex-direction:column;
    }
    
    .stat-box {
        min-width:100%;
    }
}
</style>

<div class="col-lg-9 mt-2">
    <div class="card card-report">
        <div class="card-header">
            <i class="bi bi-bar-chart-line-fill me-2"></i>Report & Statistik Konsultasi Klinik
        </div>
        <div class="card-body">
            
            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card">
                    <i class="bi bi-clipboard2-pulse icon"></i>
                    <span class="number"><?= $total_konsultasi; ?></span>
                    <span class="label">Total Konsultasi</span>
                </div>
                <div class="summary-card">
                    <i class="bi bi-people icon"></i>
                    <span class="number"><?= $total_mahasiswa_konsultasi; ?></span>
                    <span class="label">Mahasiswa Konsultasi</span>
                </div>
                <div class="summary-card">
                    <i class="bi bi-person-badge icon"></i>
                    <span class="number"><?= $total_petugas_aktif; ?></span>
                    <span class="label">Petugas Aktif</span>
                </div>
                <div class="summary-card">
                    <i class="bi bi-percent icon"></i>
                    <span class="number">
                        <?= $total_konsultasi > 0 ? round(($total_selesai/$total_konsultasi)*100, 1) : 0; ?>%
                    </span>
                    <span class="label">Tingkat Selesai</span>
                </div>
            </div>

            <!-- Grafik 1: Bar Chart - Kunjungan per Bulan -->
            <div class="chart-card">
                <h5 class="chart-title">
                    <i class="bi bi-calendar-range"></i>
                    Grafik Kunjungan Konsultasi per Bulan (12 Bulan Terakhir)
                </h5>
                <?php if(array_sum($bulan_data) > 0): ?>
                <div class="chart-container">
                    <canvas id="chartKunjungan"></canvas>
                </div>
                <div class="info-stats">
                    <div class="stat-box">
                        <span class="stat-number"><?= array_sum($bulan_data); ?></span>
                        <span class="stat-label">Total Kunjungan</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?= max($bulan_data); ?></span>
                        <span class="stat-label">Puncak Kunjungan</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?= round(array_sum($bulan_data)/12, 1); ?></span>
                        <span class="stat-label">Rata-rata/Bulan</span>
                    </div>
                </div>
                <?php else: ?>
                <div class="empty-chart">
                    <i class="bi bi-inbox"></i>
                    <p>Belum ada data kunjungan dalam 12 bulan terakhir</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Grafik 2: Pie Chart - Diagnosa Penyakit Terbanyak -->
            <div class="chart-card">
                <h5 class="chart-title">
                    <i class="bi bi-clipboard2-pulse"></i>
                    Diagnosa Penyakit Terbanyak (Top 5)
                </h5>
                <?php if(count($diagnosa_labels) > 0): ?>
                <div class="chart-container-pie">
                    <canvas id="chartDiagnosa"></canvas>
                </div>
                <div class="info-stats">
                    <div class="stat-box">
                        <span class="stat-number"><?= count($diagnosa_labels); ?></span>
                        <span class="stat-label">Jenis Diagnosa</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?= array_sum($diagnosa_data); ?></span>
                        <span class="stat-label">Total Diagnosa</span>
                    </div>
                </div>
                <?php else: ?>
                <div class="empty-chart">
                    <i class="bi bi-inbox"></i>
                    <p>Belum ada data diagnosa</p>
                    <small class="text-muted">Data diagnosa akan muncul setelah konsultasi mendapat tindak lanjut</small>
                </div>
                <?php endif; ?>
            </div>

            <!-- Grafik 3: Pie Chart - Status Konsultasi -->
            <div class="chart-card">
                <h5 class="chart-title">
                    <i class="bi bi-pie-chart-fill"></i>
                    Status Konsultasi (Selesai vs Menunggu)
                </h5>
                <?php if($total_selesai > 0 || $total_menunggu > 0): ?>
                <div class="chart-container-pie">
                    <canvas id="chartStatus"></canvas>
                </div>
                <div class="info-stats">
                    <div class="stat-box">
                        <span class="stat-number" style="color:#198754;"><?= $total_selesai; ?></span>
                        <span class="stat-label">Selesai</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number" style="color:#ffc107;"><?= $total_menunggu; ?></span>
                        <span class="stat-label">Menunggu</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number" style="color:#0dcaf0;">
                            <?= $total_selesai + $total_menunggu > 0 ? round(($total_selesai/($total_selesai+$total_menunggu))*100, 1) : 0; ?>%
                        </span>
                        <span class="stat-label">Tingkat Penyelesaian</span>
                    </div>
                </div>
                <?php else: ?>
                <div class="empty-chart">
                    <i class="bi bi-inbox"></i>
                    <p>Belum ada data konsultasi</p>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Konfigurasi Warna
const primaryColor = '#198754';
const gradientColors = [
    'rgba(25, 135, 84, 0.8)',
    'rgba(13, 202, 240, 0.8)',
    'rgba(255, 193, 7, 0.8)',
    'rgba(220, 53, 69, 0.8)',
    'rgba(13, 110, 253, 0.8)'
];

// 1. Chart Kunjungan per Bulan (Bar Chart)
const ctxKunjungan = document.getElementById('chartKunjungan');
if(ctxKunjungan) {
    const bulanData = <?= json_encode($bulan_data); ?>;
    const hasData = bulanData.some(val => val > 0);
    
    if(hasData) {
        const gradientBar = ctxKunjungan.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientBar.addColorStop(0, 'rgba(25, 135, 84, 0.8)');
        gradientBar.addColorStop(1, 'rgba(25, 135, 84, 0.3)');

        new Chart(ctxKunjungan, {
            type: 'bar',
            data: {
                labels: <?= json_encode($bulan_labels); ?>,
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: bulanData,
                    backgroundColor: gradientBar,
                    borderColor: primaryColor,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { size: 12, weight: 'bold' },
                            padding: 15,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        borderColor: primaryColor,
                        borderWidth: 2,
                        callbacks: {
                            label: function(context) {
                                return 'Jumlah: ' + context.parsed.y + ' kunjungan';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 11, weight: '600' }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11, weight: '600' }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }
}

// 2. Chart Diagnosa Penyakit (Pie Chart)
const ctxDiagnosa = document.getElementById('chartDiagnosa');
if(ctxDiagnosa) {
    const diagnosaLabels = <?= json_encode($diagnosa_labels); ?>;
    const diagnosaData = <?= json_encode($diagnosa_data); ?>;
    
    if(diagnosaLabels.length > 0) {
        new Chart(ctxDiagnosa, {
            type: 'pie',
            data: {
                labels: diagnosaLabels,
                datasets: [{
                    data: diagnosaData,
                    backgroundColor: gradientColors,
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 12, weight: 'bold' },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        borderColor: primaryColor,
                        borderWidth: 2,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }
}

// 3. Chart Status Konsultasi (Doughnut Chart)
const ctxStatus = document.getElementById('chartStatus');
if(ctxStatus) {
    const totalSelesai = <?= $total_selesai; ?>;
    const totalMenunggu = <?= $total_menunggu; ?>;
    
    if(totalSelesai > 0 || totalMenunggu > 0) {
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Selesai', 'Menunggu'],
                datasets: [{
                    data: [totalSelesai, totalMenunggu],
                    backgroundColor: [
                        'rgba(25, 135, 84, 0.8)',
                        'rgba(255, 193, 7, 0.8)'
                    ],
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 13, weight: 'bold' },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        borderColor: primaryColor,
                        borderWidth: 2,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }
}
</script>