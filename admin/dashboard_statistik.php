<?php
include '../config/koneksi.php';
$user_nama = isset($_GET['user']) ? $_GET['user'] : 'Admin';

// --- LOGIKA DATA ---

// 1. Hitung Total Laporan Bulan Ini
$bulan_ini = date('m');
$tahun_ini = date('Y');
$sql_masuk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM input_aspirasi WHERE MONTH(tgl_input) = '$bulan_ini' AND YEAR(tgl_input) = '$tahun_ini'");
$total_masuk = mysqli_fetch_assoc($sql_masuk)['total'];

// 2. Hitung Laporan Berdasarkan Status
$sql_status = mysqli_query($koneksi, "SELECT 
    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai,
    SUM(CASE WHEN status = 'Proses' THEN 1 ELSE 0 END) as proses,
    SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) as ditolak,
    SUM(CASE WHEN status IS NULL OR status = 'Menunggu' THEN 1 ELSE 0 END) as menunggu
    FROM aspirasi");
$data_st = mysqli_fetch_assoc($sql_status);

// 3. Ambil Data Per Kategori untuk Grafik Progress Bar
$sql_kategori = mysqli_query($koneksi, "SELECT kategori.ket_kategori, COUNT(input_aspirasi.id_pelaporan) as jumlah 
                                        FROM input_aspirasi 
                                        JOIN kategori ON input_aspirasi.id_kategori = kategori.id_kategori 
                                        GROUP BY kategori.id_kategori 
                                        ORDER BY jumlah DESC");

// 4. Hitung Total Semua untuk Persentase
$total_all_sql = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM input_aspirasi");
$total_all = mysqli_fetch_assoc($total_all_sql)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik & Laporan - SMKN 2 Magelang</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-blue: #004aad; 
            --accent-yellow: #fdb913; 
            --dark-overlay: rgba(0, 36, 88, 0.9);
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(var(--dark-overlay), var(--dark-overlay)), url('../img/gedung.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* --- Sidebar (Sama Persis dengan Manajemen) --- */
        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 20px;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .sidebar-logo img { width: 40px; }
        .sidebar-logo h5 { font-weight: 700; color: var(--primary-blue); font-size: 1rem; margin: 0; }

        .nav-link {
            color: #6c757d; font-weight: 500; padding: 12px 15px; border-radius: 10px; margin-bottom: 5px; transition: 0.3s; display: flex; align-items: center;
        }
        .nav-link:hover { color: var(--primary-blue); background-color: #f0f4ff; }
        .nav-link.active { background-color: var(--primary-blue); color: white; box-shadow: 0 4px 10px rgba(0, 74, 173, 0.3); }

        .user-profile {
            margin-top: auto; background: #f8f9fa; padding: 15px; border-radius: 12px; display: flex; align-items: center; gap: 10px;
        }

        /* --- Main Content --- */
        .main-content {
            margin-left: var(--sidebar-width); flex-grow: 1; padding: 30px; width: 100%;
        }

        /* --- Card Styling --- */
        .content-card {
            background: white; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15); overflow: hidden; margin-bottom: 30px; padding: 25px;
        }

        /* --- Widget Statistik Kecil --- */
        .stat-widget {
            background: white;
            border-radius: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            height: 100%;
            border-left: 5px solid transparent;
        }
        .stat-widget:hover { transform: translateY(-5px); }
        .stat-icon {
            width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
        }
        
        /* Warna Border & Icon Widget */
        .border-blue { border-left-color: var(--primary-blue); }
        .bg-icon-blue { background-color: rgba(0, 74, 173, 0.1); color: var(--primary-blue); }
        
        .border-green { border-left-color: #198754; }
        .bg-icon-green { background-color: rgba(25, 135, 84, 0.1); color: #198754; }

        .border-yellow { border-left-color: #ffc107; }
        .bg-icon-yellow { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }

        .border-red { border-left-color: #dc3545; }
        .bg-icon-red { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }

        /* --- Progress Bar --- */
        .progress { height: 10px; border-radius: 20px; background-color: #e9ecef; }
        .progress-bar { border-radius: 20px; }

        /* --- Print Styling --- */
        @media print {
            .sidebar, .btn-print, .user-profile { display: none !important; }
            .main-content { margin-left: 0; padding: 0; }
            body { background: white; -webkit-print-color-adjust: exact; }
            .content-card, .stat-widget { box-shadow: none; border: 1px solid #ddd; }
            .stat-widget { break-inside: avoid; }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="../img/logosmk.png" alt="Logo">
        <div>
            <h5>SMKN 2</h5>
            <small class="text-muted">ADMINISTRATOR</small>
        </div>
    </div>

    <nav class="nav flex-column">
        <a href="dashboard_admin.php?user=<?php echo $user_nama; ?>" class="nav-link">
            <i class="bi bi-inbox-fill me-3"></i> Masuk Aspirasi
        </a>
        <a href="dashboard_statistik.php?user=<?php echo $user_nama; ?>" class="nav-link active">
            <i class="bi bi-pie-chart-fill me-3"></i> Laporan & Statistik
        </a>
    </nav>

    <div class="user-profile">
        <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div class="fw-bold text-truncate small"><?php echo htmlspecialchars($user_nama); ?></div>
            <div class="text-muted" style="font-size: 0.7rem;">Admin Petugas</div>
        </div>
        <a href="../landing.php" class="text-danger" title="Logout"><i class="bi bi-box-arrow-right"></i></a>
    </div>
</aside>

<main class="main-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-white mb-0">Statistik Pengaduan</h2>
            <p class="text-white opacity-75 small mb-0">Ringkasan data aspirasi siswa bulan ini.</p>
        </div>
        <button class="btn btn-light text-primary fw-bold shadow-sm btn-print" onclick="window.print()">
            <i class="bi bi-printer-fill me-2"></i>Cetak Laporan
        </button>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-widget border-blue">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase">Bulan Ini</h6>
                    <h2 class="mb-0 fw-bold"><?= $total_masuk ?></h2>
                </div>
                <div class="stat-icon bg-icon-blue">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-widget border-green">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase">Selesai</h6>
                    <h2 class="mb-0 fw-bold"><?= $data_st['selesai'] ?? 0 ?></h2>
                </div>
                <div class="stat-icon bg-icon-green">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-widget border-yellow">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase">Proses</h6>
                    <h2 class="mb-0 fw-bold"><?= $data_st['proses'] ?? 0 ?></h2>
                </div>
                <div class="stat-icon bg-icon-yellow">
                    <i class="bi bi-arrow-clockwise"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-widget border-red">
                <div>
                    <h6 class="text-muted small fw-bold text-uppercase">Ditolak</h6>
                    <h2 class="mb-0 fw-bold"><?= $data_st['ditolak'] ?? 0 ?></h2>
                </div>
                <div class="stat-icon bg-icon-red">
                    <i class="bi bi-x-octagon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="content-card h-100">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Tren Kategori Masalah</h5>
                
                <?php if($total_all > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($sql_kategori)): 
                        $persen = ($row['jumlah'] / $total_all) * 100;
                    ?>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-semibold small text-dark"><?= $row['ket_kategori'] ?></span>
                            <span class="text-muted small fw-bold"><?= $row['jumlah'] ?> Laporan</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $persen ?>%"></div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">Belum ada data laporan masuk.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="content-card h-100">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-clipboard-data-fill text-primary me-2"></i>Status Global</h5>
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-hourglass-split text-warning fs-5"></i>
                            <div>
                                <div class="small fw-bold">Menunggu</div>
                                <div class="text-muted" style="font-size: 11px;">Belum diverifikasi</div>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill"><?= $data_st['menunggu'] ?? 0 ?></span>
                    </li>
                    <li class="list-group-item px-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-gear-wide-connected text-primary fs-5"></i>
                            <div>
                                <div class="small fw-bold">Sedang Diproses</div>
                                <div class="text-muted" style="font-size: 11px;">Tindak lanjut sarpras</div>
                            </div>
                        </div>
                        <span class="badge bg-primary rounded-pill"><?= $data_st['proses'] ?? 0 ?></span>
                    </li>
                    <li class="list-group-item px-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <div>
                                <div class="small fw-bold">Selesai</div>
                                <div class="text-muted" style="font-size: 11px;">Masalah teratasi</div>
                            </div>
                        </div>
                        <span class="badge bg-success rounded-pill"><?= $data_st['selesai'] ?? 0 ?></span>
                    </li>
                    <li class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                            <div>
                                <div class="small fw-bold">Ditolak</div>
                                <div class="text-muted" style="font-size: 11px;">Tidak relevan / Hoax</div>
                            </div>
                        </div>
                        <span class="badge bg-danger rounded-pill"><?= $data_st['ditolak'] ?? 0 ?></span>
                    </li>
                </ul>

                <div class="mt-4 pt-3 border-top text-center">
                    <p class="text-muted small mb-0">Total Akumulasi Laporan</p>
                    <h1 class="fw-bold text-dark mb-0"><?= $total_all ?></h1>
                    <span class="badge bg-light text-dark border">Semua Waktu</span>
                </div>
            </div>
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>