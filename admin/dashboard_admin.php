<?php
include '../config/koneksi.php';
$user_nama = isset($_GET['user']) ? $_GET['user'] : 'Admin';

// --- 1. LOGIKA KIRIM CHAT ADMIN ---
if (isset($_POST['kirim_chat_admin'])) {
    $id_asp = $_POST['id_aspirasi'];
    $pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);
    
    // Cek status dulu, jika 'Selesai' tolak chat (Security backend)
    $cek_stat = mysqli_query($koneksi, "SELECT status FROM aspirasi WHERE id_aspirasi='$id_asp'");
    $d_stat = mysqli_fetch_assoc($cek_stat);
    if($d_stat && $d_stat['status'] == 'Selesai'){
        echo "<script>alert('Laporan sudah selesai, tidak bisa mengirim pesan.'); window.history.back();</script>";
        exit;
    }

    // 1. Masukkan Chat
    mysqli_query($koneksi, "INSERT INTO aspirasi_chat (id_aspirasi, sender_type, message) VALUES ('$id_asp', 'admin', '$pesan')");
    
    // 2. Kirim Notifikasi Sistem ke Siswa
    $get_nis = mysqli_query($koneksi, "SELECT nis FROM input_aspirasi WHERE id_pelaporan='$id_asp'");
    $nis_target = mysqli_fetch_assoc($get_nis)['nis'];
    $notif_msg = "Admin membalas laporan #$id_asp: " . substr($pesan, 0, 30) . "...";
    mysqli_query($koneksi, "INSERT INTO notifikasi (id_aspirasi, nis, pesan) VALUES ('$id_asp', '$nis_target', '$notif_msg')");
    
    header("Location: dashboard_admin.php?user=$user_nama&open_chat=$id_asp");
    exit;
}

// --- 2. LOGIKA BACA CHAT (Saat Modal Dibuka) ---
if(isset($_GET['open_chat'])) {
    $id_open = $_GET['open_chat'];
    mysqli_query($koneksi, "UPDATE aspirasi_chat SET is_read=1 WHERE id_aspirasi='$id_open' AND sender_type='siswa'");
}

// --- 3. LOGIKA UPDATE STATUS (Proses/Tolak) ---
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $aksi = $_GET['aksi'];
    
    // Tentukan status baru - SESUAIKAN dengan ENUM di database
    if($aksi == 'proses') {
        $status_baru = "Proses";
    } elseif($aksi == 'tolak') {
        $status_baru = "Ditolak";
    } else {
        $status_baru = "Menunggu";
    }

    // Cek apakah data sudah ada di tabel aspirasi
    $cek = mysqli_query($koneksi, "SELECT * FROM aspirasi WHERE id_aspirasi = '$id'");
    
    if (mysqli_num_rows($cek) > 0) {
        // Jika sudah ada, UPDATE
        mysqli_query($koneksi, "UPDATE aspirasi SET status='$status_baru' WHERE id_aspirasi='$id'");
    } else {
        // Jika belum ada, INSERT
        mysqli_query($koneksi, "INSERT INTO aspirasi (id_aspirasi, status, feedback) VALUES ('$id', '$status_baru', '')");
    }
    
    // Kirim Notifikasi Status ke Siswa
    $dt_query = mysqli_query($koneksi, "SELECT nis FROM input_aspirasi WHERE id_pelaporan='$id'");
    if($dt_query && mysqli_num_rows($dt_query) > 0) {
        $dt = mysqli_fetch_assoc($dt_query);
        $pesan_n = "Status laporan #$id diperbarui menjadi: $status_baru";
        mysqli_query($koneksi, "INSERT INTO notifikasi (id_aspirasi, nis, pesan) VALUES ('$id', '".$dt['nis']."', '$pesan_n')");
    }

    // Redirect dengan notifikasi
    header("Location: dashboard_admin.php?user=$user_nama&notif=status_updated");
    exit;
}

// --- 4. PAGINATION & FILTER SETUP ---
$limit = 15; // Data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// --- 5. QUERY UTAMA dengan FILTER ---
$where_clauses = [];

// Filter Pencarian Siswa
if (!empty($_GET['cari_siswa'])) {
    $kw = mysqli_real_escape_string($koneksi, $_GET['cari_siswa']);
    $where_clauses[] = "(input_aspirasi.nis LIKE '%$kw%' OR siswa.nama LIKE '%$kw%')";
}

// Filter Kategori
if (!empty($_GET['filter_kategori'])) {
    $kat = mysqli_real_escape_string($koneksi, $_GET['filter_kategori']);
    $where_clauses[] = "input_aspirasi.id_kategori = '$kat'";
}

// Filter Tanggal Dari
if (!empty($_GET['tanggal_dari'])) {
    $tgl_dari = mysqli_real_escape_string($koneksi, $_GET['tanggal_dari']);
    $where_clauses[] = "DATE(input_aspirasi.tgl_input) >= '$tgl_dari'";
}

// Filter Tanggal Sampai
if (!empty($_GET['tanggal_sampai'])) {
    $tgl_sampai = mysqli_real_escape_string($koneksi, $_GET['tanggal_sampai']);
    $where_clauses[] = "DATE(input_aspirasi.tgl_input) <= '$tgl_sampai'";
}

$query_base = "SELECT input_aspirasi.*, kategori.ket_kategori, aspirasi.status 
               FROM input_aspirasi 
               JOIN kategori ON input_aspirasi.id_kategori = kategori.id_kategori
               LEFT JOIN aspirasi ON input_aspirasi.id_pelaporan = aspirasi.id_aspirasi
               LEFT JOIN siswa ON input_aspirasi.nis = siswa.nis";

if (count($where_clauses) > 0) { 
    $query_base .= " WHERE " . implode(' AND ', $where_clauses); 
}

// Hitung total data untuk pagination
$count_query = str_replace("SELECT input_aspirasi.*, kategori.ket_kategori, aspirasi.status", "SELECT COUNT(*) as total", $query_base);
$count_result = mysqli_query($koneksi, $count_query);
$total_data = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_data / $limit);

// Query dengan LIMIT untuk pagination
$query_base .= " ORDER BY input_aspirasi.tgl_input DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query_base);
$data_kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMKN 2 Magelang</title>
    
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

        /* --- Sidebar Styling (Matches Dashboard Statistik) --- */
        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            height: 100vh;
            position: fixed;
            top: 0; left: 0; z-index: 1000;
            padding: 20px;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .sidebar-logo {
            display: flex; align-items: center; gap: 10px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee;
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
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 30px; width: 100%; }
        
        /* --- Card Styling --- */
        .content-card {
            background: white; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15); overflow: hidden; margin-bottom: 30px;
        }

        /* Chat Styles */
        .chat-box { height: 350px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 10px; display: flex; flex-direction: column; gap: 10px; border: 1px solid #eee; }
        .chat-bubble { max-width: 80%; padding: 10px 15px; border-radius: 15px; font-size: 0.9rem; position: relative; }
        .chat-me { align-self: flex-end; background: var(--primary-blue); color: white; border-bottom-right-radius: 2px; }
        .chat-other { align-self: flex-start; background: #e9ecef; color: #333; border-bottom-left-radius: 2px; }
        .chat-time { font-size: 0.65rem; opacity: 0.7; display: block; margin-top: 5px; text-align: right; }
        .red-dot { width: 10px; height: 10px; background-color: #dc3545; border-radius: 50%; display: inline-block; position: absolute; top: -3px; right: -3px; border: 2px solid white; z-index: 5; }
        
        /* Pagination Style */
        .pagination { gap: 5px; }
        .page-link { border-radius: 8px; color: var(--primary-blue); font-weight: 500; }
        .page-item.active .page-link { background-color: var(--primary-blue); border-color: var(--primary-blue); }

        /* Custom Scrollbar */
        .chat-box::-webkit-scrollbar { width: 6px; }
        .chat-box::-webkit-scrollbar-track { background: #f1f1f1; }
        .chat-box::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
        .chat-box::-webkit-scrollbar-thumb:hover { background: #aaa; }

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
        <div><h5>SMKN 2</h5><small class="text-muted">ADMINISTRATOR</small></div>
    </div>

    <nav class="nav flex-column">
        <a href="#" class="nav-link active"><i class="bi bi-inbox-fill me-3"></i> Masuk Aspirasi</a>
        <a href="../admin/dashboard_statistik.php" class="nav-link"><i class="bi bi-pie-chart-fill me-3"></i> Laporan & Statistik</a>
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
    <div class="d-flex justify-content-between align-items-center mb-4 text-white">
        <div>
            <h2 class="fw-bold mb-0">Manajemen Aspirasi</h2>
            <p class="mb-0 opacity-75 small">Kelola status laporan, proses perbaikan, dan diskusi dengan siswa.</p>
        </div>
    </div>

    <div class="content-card">
        <div class="p-3 bg-light border-bottom">
            <form action="" method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="user" value="<?php echo htmlspecialchars($user_nama); ?>">
                
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">Pencarian</label>
                    <input type="text" name="cari_siswa" class="form-control form-control-sm" placeholder="NIS..." value="<?php echo isset($_GET['cari_siswa']) ? htmlspecialchars($_GET['cari_siswa']) : ''; ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted mb-1">Kategori</label>
                    <select name="filter_kategori" class="form-select form-select-sm">
                        <option value="">-- Semua --</option>
                        <?php 
                        if(mysqli_num_rows($data_kategori) > 0) {
                            mysqli_data_seek($data_kategori, 0);
                            while($k = mysqli_fetch_assoc($data_kategori)): 
                        ?>
                            <option value="<?php echo $k['id_kategori']; ?>" <?php echo (isset($_GET['filter_kategori']) && $_GET['filter_kategori'] == $k['id_kategori']) ? 'selected' : ''; ?>>
                                <?php echo $k['ket_kategori']; ?>
                            </option>
                        <?php endwhile; } ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted mb-1">Dari Tanggal</label>
                    <input type="date" name="tanggal_dari" class="form-control form-control-sm" value="<?php echo isset($_GET['tanggal_dari']) ? htmlspecialchars($_GET['tanggal_dari']) : ''; ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted mb-1">Sampai Tanggal</label>
                    <input type="date" name="tanggal_sampai" class="form-control form-control-sm" value="<?php echo isset($_GET['tanggal_sampai']) ? htmlspecialchars($_GET['tanggal_sampai']) : ''; ?>">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold"><i class="bi bi-search"></i> Cari Data</button>
                </div>
                <div class="col-md-1">
                     <a href="dashboard_admin.php?user=<?php echo $user_nama; ?>" class="btn btn-outline-secondary btn-sm w-100" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th width="15%">NIS & Tgl</th>
                        <th width="20%">Kategori & Lokasi</th>
                        <th width="25%">Isi Laporan</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="15%" class="text-center">Aksi Admin</th>
                        <th width="15%" class="text-center">Diskusi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): 
                        $st = $row['status'] ?? 'Menunggu';
                        $id_asp = $row['id_pelaporan'];
                        
                        // Cek chat unread
                        $cek_unread = mysqli_query($koneksi, "SELECT COUNT(*) as unread FROM aspirasi_chat WHERE id_aspirasi='$id_asp' AND sender_type='siswa' AND is_read=0");
                        $unread = mysqli_fetch_assoc($cek_unread)['unread'];
                        
                        // Logika Tombol Chat (Hanya mati jika 'Selesai')
                        $is_chat_disabled = ($st == 'Selesai');
                    ?>
                    <tr>
                        <td>
                            <div class="fw-bold small"><?php echo $row['nis']; ?></div>
                            <small class="text-muted"><?php echo date('d M Y', strtotime($row['tgl_input'])); ?></small>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-primary border border-info mb-1""><?php echo $row['ket_kategori']; ?></span>
                            <div class="small text-secondary"><i class="bi bi-geo-alt-fill text-danger"></i> <?php echo $row['lokasi']; ?></div>
                        </td>
                        <td>
                            <div class="text-secondary small text-truncate" style="max-width: 200px;" title="<?php echo $row['ket']; ?>">"<?php echo $row['ket']; ?>"</div>
                        </td>
                        <td class="text-center">
                            <?php if($st == 'Proses'): ?>
                                <span class="badge bg-primary">Diproses</span>
                            <?php elseif($st == 'Selesai'): ?>
                                <span class="badge bg-success">Selesai</span>
                            <?php elseif($st == 'Ditolak'): ?>
                                <span class="badge bg-danger">Ditolak</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="text-center">
                            <?php if($st == 'Menunggu'): ?>
                                <div class="btn-group" role="group">
                                    <a href="?user=<?php echo $user_nama; ?>&id=<?php echo $id_asp; ?>&aksi=proses" 
                                       class="btn btn-outline-primary btn-sm"
                                       onclick="return confirm('Apakah Anda yakin ingin MEMPROSES laporan ini?');">
                                       <i class="bi bi-gear-fill"></i> Proses
                                    </a>
                                    <a href="?user=<?php echo $user_nama; ?>&id=<?php echo $id_asp; ?>&aksi=tolak" 
                                       class="btn btn-outline-danger btn-sm"
                                       onclick="return confirm('Apakah Anda yakin ingin MENOLAK laporan ini?');">
                                       <i class="bi bi-x-circle-fill"></i>
                                    </a>
                                </div>
                            
                            <?php elseif($st == 'Proses'): ?>
                                <a href="?user=<?php echo $user_nama; ?>&id=<?php echo $id_asp; ?>&aksi=tolak" 
                                   class="btn btn-outline-danger btn-sm rounded-pill mb-2 px-3"
                                   onclick="return confirm('Apakah Anda yakin ingin MENOLAK laporan yang sedang diproses ini?');">
                                   <i class="bi bi-x-circle"></i> Tolak
                                </a>

                            <?php else: // Jika Selesai atau Ditolak -> TERKUNCI ?>
                                <span class="text-muted small fw-bold">
                                    <i class="bi bi-lock-fill"></i> Terkunci
                                </span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center">
                            <?php if($is_chat_disabled): ?>
                                <button class="btn btn-sm btn-secondary opacity-50 rounded-pill px-3 py-1 border position-relative shadow-sm" disabled style="cursor: not-allowed;">
                                    <i class="bi bi-chat-text-fill"></i> Chat
                                </button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-light border position-relative rounded-pill px-3 py-1 text-primary shadow-sm" 
                                        onclick="window.location.href='?user=<?php echo $user_nama; ?>&open_chat=<?php echo $id_asp; ?><?php 
                                        // Pertahankan parameter filter
                                        if(isset($_GET['cari_siswa'])) echo '&cari_siswa='.urlencode($_GET['cari_siswa']);
                                        if(isset($_GET['filter_kategori'])) echo '&filter_kategori='.$_GET['filter_kategori'];
                                        if(isset($_GET['tanggal_dari'])) echo '&tanggal_dari='.$_GET['tanggal_dari'];
                                        if(isset($_GET['tanggal_sampai'])) echo '&tanggal_sampai='.$_GET['tanggal_sampai'];
                                        if(isset($_GET['page'])) echo '&page='.$_GET['page'];
                                        ?>'">
                                    <i class="bi bi-chat-text-fill"></i> Chat
                                    <?php if($unread > 0): ?>
                                        <span class="red-dot"></span>
                                    <?php endif; ?>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php if(isset($_GET['open_chat']) && $_GET['open_chat'] == $id_asp): ?>
                    <div class="modal fade show" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow rounded-4">
                                <div class="modal-header bg-primary text-white">
                                    <h6 class="modal-title fw-bold"><i class="bi bi-chat-dots me-2"></i>Diskusi #<?php echo $id_asp; ?></h6>
                                    <a href="dashboard_admin.php?user=<?php echo $user_nama; ?><?php 
                                    // Pertahankan parameter filter saat menutup chat
                                    if(isset($_GET['cari_siswa'])) echo '&cari_siswa='.urlencode($_GET['cari_siswa']);
                                    if(isset($_GET['filter_kategori'])) echo '&filter_kategori='.$_GET['filter_kategori'];
                                    if(isset($_GET['tanggal_dari'])) echo '&tanggal_dari='.$_GET['tanggal_dari'];
                                    if(isset($_GET['tanggal_sampai'])) echo '&tanggal_sampai='.$_GET['tanggal_sampai'];
                                    if(isset($_GET['page'])) echo '&page='.$_GET['page'];
                                    ?>" class="btn-close btn-close-white"></a>
                                </div>
                                <div class="modal-body bg-light">
                                    
                                    <div class="bg-white p-3 rounded-3 shadow-sm mb-3 border position-relative">
                                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 0.7rem;">ISI LAPORAN:</small>
                                        <p class="small text-secondary m-0" style="white-space: pre-wrap; line-height: 1.4;"><?php echo htmlspecialchars($row['ket']); ?></p>
                                        
                                        <?php if($st == 'Selesai'): ?>
                                            <div class="mt-2 text-center text-success small fw-bold border-top pt-2">
                                                <i class="bi bi-check-circle-fill"></i> Laporan Telah Diselesaikan
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="chat-box" id="chatAdminBox">
                                        <?php 
                                        $chats = mysqli_query($koneksi, "SELECT * FROM aspirasi_chat WHERE id_aspirasi='$id_asp' ORDER BY created_at ASC");
                                        if(mysqli_num_rows($chats) > 0){
                                            while($c = mysqli_fetch_assoc($chats)){
                                                $is_me = ($c['sender_type'] == 'admin'); 
                                                echo '<div class="chat-bubble '.($is_me ? 'chat-me' : 'chat-other').'">';
                                                echo nl2br(htmlspecialchars($c['message']));
                                                echo '<span class="chat-time text-'.($is_me?'light':'muted').'">'.date('d M H:i', strtotime($c['created_at'])).'</span>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo '<div class="text-center small text-muted mt-5">Belum ada percakapan.</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <?php if($st != 'Selesai'): ?>
                                <div class="modal-footer p-2 bg-white rounded-bottom-4">
                                    <form action="" method="POST" class="w-100">
                                        <input type="hidden" name="id_aspirasi" value="<?php echo $id_asp; ?>">
                                        
                                        <div class="d-flex align-items-center bg-light border rounded-4 px-2 py-1 shadow-sm w-100">
                                            
                                            <textarea name="pesan" class="form-control border-0 bg-transparent shadow-none" 
                                                      rows="1" placeholder="Tulis balasan..." 
                                                      style="resize: none; font-size: 0.9rem;" required></textarea>
                                            
                                            <div class="border-start mx-2" style="height: 25px; border-color: #ddd;"></div>
                                            
                                            <button type="submit" name="kirim_chat_admin" class="btn btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center flex-shrink-0" 
                                                    style="width: 35px; height: 35px;">
                                                <i class="bi bi-send-fill" style="font-size: 0.8rem; margin-left: 2px;"></i>
                                            </button>
                                            
                                        </div>
                                    </form>
                                </div>
                                <?php else: ?>
                                <div class="modal-footer p-2 bg-secondary text-white rounded-bottom-4 justify-content-center">
                                    <small><i class="bi bi-lock-fill"></i> Laporan Selesai. Diskusi Ditutup.</small>
                                </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada laporan masuk.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($total_pages > 1): ?>
        <div class="p-3 border-top bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan <?php echo (($page-1)*$limit)+1; ?> - <?php echo min($page*$limit, $total_data); ?> dari <?php echo $total_data; ?> data
                </small>
                
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?user=<?php echo $user_nama; ?>&page=<?php echo $page-1; ?><?php 
                            if(isset($_GET['cari_siswa'])) echo '&cari_siswa='.urlencode($_GET['cari_siswa']);
                            if(isset($_GET['filter_kategori'])) echo '&filter_kategori='.$_GET['filter_kategori'];
                            if(isset($_GET['tanggal_dari'])) echo '&tanggal_dari='.$_GET['tanggal_dari'];
                            if(isset($_GET['tanggal_sampai'])) echo '&tanggal_sampai='.$_GET['tanggal_sampai'];
                            ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>

                        <?php 
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $page + 2);
                        
                        for($i = $start_page; $i <= $end_page; $i++): 
                        ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?user=<?php echo $user_nama; ?>&page=<?php echo $i; ?><?php 
                                if(isset($_GET['cari_siswa'])) echo '&cari_siswa='.urlencode($_GET['cari_siswa']);
                                if(isset($_GET['filter_kategori'])) echo '&filter_kategori='.$_GET['filter_kategori'];
                                if(isset($_GET['tanggal_dari'])) echo '&tanggal_dari='.$_GET['tanggal_dari'];
                                if(isset($_GET['tanggal_sampai'])) echo '&tanggal_sampai='.$_GET['tanggal_sampai'];
                                ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?user=<?php echo $user_nama; ?>&page=<?php echo $page+1; ?><?php 
                            if(isset($_GET['cari_siswa'])) echo '&cari_siswa='.urlencode($_GET['cari_siswa']);
                            if(isset($_GET['filter_kategori'])) echo '&filter_kategori='.$_GET['filter_kategori'];
                            if(isset($_GET['tanggal_dari'])) echo '&tanggal_dari='.$_GET['tanggal_dari'];
                            if(isset($_GET['tanggal_sampai'])) echo '&tanggal_sampai='.$_GET['tanggal_sampai'];
                            ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto scroll ke bawah chat jika modal terbuka
    <?php if(isset($_GET['open_chat'])): ?>
    var chatBox = document.getElementById("chatAdminBox");
    if(chatBox) { chatBox.scrollTop = chatBox.scrollHeight; }
    <?php endif; ?>
</script>

</body>
</html>