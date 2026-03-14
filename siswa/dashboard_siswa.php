<?php
include '../config/koneksi.php';
$nis = isset($_GET['nis']) ? $_GET['nis'] : '';

// --- 1. LOGIKA KIRIM PESAN (CHAT) ---
if(isset($_POST['kirim_chat'])) {
    $id_asp = $_POST['id_aspirasi'];
    $pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);
    
    // Cek status dulu, jika 'Selesai' tolak chat (Security backend)
    $cek_stat = mysqli_query($koneksi, "SELECT status FROM aspirasi WHERE id_aspirasi='$id_asp'");
    $d_stat = mysqli_fetch_assoc($cek_stat);
    if($d_stat && $d_stat['status'] == 'Selesai'){
        echo "<script>alert('Laporan sudah selesai, tidak bisa mengirim pesan.'); window.history.back();</script>";
        exit;
    }
    
    // Masukkan ke tabel chat
    mysqli_query($koneksi, "INSERT INTO aspirasi_chat (id_aspirasi, sender_type, message) VALUES ('$id_asp', 'siswa', '$pesan')");
    
    // Redirect kembali dan buka modal otomatis
    header("Location: dashboard_siswa.php?nis=$nis&open_chat=$id_asp");
    exit;
}

// --- 2. LOGIKA BACA NOTIF & CHAT ---
if(isset($_GET['open_chat'])) {
    $id_open = $_GET['open_chat'];
    mysqli_query($koneksi, "UPDATE aspirasi_chat SET is_read=1 WHERE id_aspirasi='$id_open' AND sender_type='admin'");
}

// Logika Baca Notifikasi Bel
if(isset($_GET['read']) && $_GET['read'] == '1') {
    mysqli_query($koneksi, "UPDATE notifikasi SET is_read=1 WHERE nis='$nis'");
    header("Location: dashboard_siswa.php?nis=$nis");
    exit;
}

// --- 3. LOGIKA AKSI (Selesai/Tolak) ---
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id_p = mysqli_real_escape_string($koneksi, $_GET['id']);
    $aksi = $_GET['aksi'];
    $status_baru = ($aksi == 'selesai') ? 'Selesai' : 'Proses';
    
    mysqli_query($koneksi, "UPDATE aspirasi SET status='$status_baru' WHERE id_aspirasi='$id_p'");
    header("Location: dashboard_siswa.php?nis=$nis");
    exit;
}

// --- 4. DATA UTAMA ---
$notif_count_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM notifikasi WHERE nis='$nis' AND is_read=0");
$notif_count = mysqli_fetch_assoc($notif_count_query)['total'];
$list_notif = mysqli_query($koneksi, "SELECT * FROM notifikasi WHERE nis='$nis' ORDER BY tgl_notif DESC LIMIT 5");

$query = "SELECT input_aspirasi.*, aspirasi.status, kategori.ket_kategori 
          FROM input_aspirasi 
          LEFT JOIN aspirasi ON input_aspirasi.id_pelaporan = aspirasi.id_aspirasi
          LEFT JOIN kategori ON input_aspirasi.id_kategori = kategori.id_kategori
          WHERE input_aspirasi.nis = '$nis' ORDER BY input_aspirasi.tgl_input DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - SMKN 2 Magelang</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
        
        /* Sidebar Styling (Sama dengan Admin) */
        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            height: 100vh;
            position: fixed;
            top: 0; left: 0; z-index: 1000;
            padding: 20px;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            display: flex; flex-direction: column;
            transition: all 0.3s;
        }
        .sidebar-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .sidebar-logo img { width: 40px; }
        .sidebar-logo h5 { font-weight: 700; color: var(--primary-blue); font-size: 1rem; margin: 0; }
        
        .nav-link { color: #6c757d; font-weight: 500; padding: 12px 15px; border-radius: 10px; margin-bottom: 5px; transition: 0.3s; display: flex; align-items: center; }
        .nav-link:hover { color: var(--primary-blue); background-color: #f0f4ff; }
        .nav-link.active { background-color: var(--primary-blue); color: white; box-shadow: 0 4px 10px rgba(0, 74, 173, 0.3); }

        .user-profile { margin-top: auto; background: #f8f9fa; padding: 15px; border-radius: 12px; display: flex; align-items: center; gap: 10px; }

        /* Main Content */
        .main-content { margin-left: var(--sidebar-width); flex-grow: 1; padding: 30px; width: 100%; }
        .content-card { background: white; border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15); overflow: hidden; margin-bottom: 30px; }

        /* Chat Styles (Sama dengan Admin) */
        .chat-box { height: 350px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 10px; display: flex; flex-direction: column; gap: 10px; border: 1px solid #eee; }
        .chat-bubble { max-width: 75%; padding: 10px 15px; border-radius: 15px; font-size: 0.9rem; position: relative; }
        
        .chat-admin { align-self: flex-start; background: #fff; color: #333; border: 1px solid #ddd; border-bottom-left-radius: 2px; }
        .chat-me { align-self: flex-end; background: var(--primary-blue); color: white; border-bottom-right-radius: 2px; }
        
        .chat-time { font-size: 0.65rem; opacity: 0.7; display: block; margin-top: 5px; text-align: right; }
        .red-dot { width: 10px; height: 10px; background-color: #dc3545; border-radius: 50%; display: inline-block; position: absolute; top: -2px; right: -2px; border: 2px solid white; z-index: 5; }

        /* Custom Scrollbar */
        .chat-box::-webkit-scrollbar { width: 6px; }
        .chat-box::-webkit-scrollbar-track { background: #f1f1f1; }
        .chat-box::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
        .chat-box::-webkit-scrollbar-thumb:hover { background: #aaa; }

        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .main-content { margin-left: 0; } }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="../img/logosmk.png" alt="Logo">
        <div><h5>SMKN 2</h5><small class="text-muted">MAGELANG</small></div>
    </div>
    
    <nav class="nav flex-column">
        <a href="input_aspirasi.php?nis=<?php echo $nis; ?>" class="nav-link"><i class="bi bi-pencil-square me-3"></i> Buat Laporan</a>
        <a href="#" class="nav-link active"><i class="bi bi-clock-history me-3"></i> Histori Aspirasi</a>
    </nav>

    <div class="user-profile">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="bi bi-person-fill"></i>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div class="fw-bold text-truncate small">NIS: <?php echo htmlspecialchars($nis); ?></div>
            <div class="text-muted" style="font-size: 0.7rem;">Siswa</div>
        </div>
        <a href="../landing.php" class="text-danger" title="Keluar"><i class="bi bi-box-arrow-right"></i></a>
    </div>
</aside>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4 text-white">
        <div>
            <h2 class="fw-bold mb-0">Dashboard Siswa</h2>
            <p class="mb-0 opacity-75 small">Pantau status laporan dan berdiskusi dengan admin.</p>
        </div>
        
        <div class="dropdown">
            <button class="btn btn-light position-relative shadow-sm rounded-circle p-0 d-flex align-items-center justify-content-center" 
                    style="width: 45px; height: 45px;" data-bs-toggle="dropdown">
                <i class="bi bi-bell-fill fs-5 text-primary"></i>
                <?php if($notif_count > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                        <?php echo $notif_count; ?>
                    </span>
                <?php endif; ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 mt-2 p-0 border-0 overflow-hidden" style="width: 320px;">
                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold m-0 text-dark">Notifikasi</h6>
                    <a href="?nis=<?php echo $nis; ?>&read=1" class="small text-decoration-none fw-bold">Tandai dibaca</a>
                </div>
                <div style="max-height: 300px; overflow-y: auto;">
                    <?php if(mysqli_num_rows($list_notif) > 0): ?>
                        <?php while($n = mysqli_fetch_assoc($list_notif)): ?>
                        <li class="dropdown-item p-3 border-bottom text-wrap <?php echo $n['is_read'] == 0 ? 'bg-info bg-opacity-10' : ''; ?>">
                            <small class="d-block text-secondary mb-1" style="font-size: 0.7rem;"><?php echo date('d M H:i', strtotime($n['tgl_notif'])); ?></small>
                            <span class="small"><?php echo $n['pesan']; ?></span>
                        </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="p-4 text-center text-muted small">Tidak ada notifikasi baru.</li>
                    <?php endif; ?>
                </div>
            </ul>
        </div>
    </div>

    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="py-3 ps-4">Tanggal</th>
                        <th class="py-3">Kategori & Lokasi</th>
                        <th class="py-3">Isi Laporan</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-center pe-4">Diskusi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($result) > 0):
                        while($row = mysqli_fetch_assoc($result)): 
                        $st = $row['status'] ?? 'Menunggu'; 
                        $id_asp = $row['id_pelaporan'];
                        
                        // Hitung chat belum dibaca dari Admin
                        $cek_chat = mysqli_query($koneksi, "SELECT COUNT(*) as unread FROM aspirasi_chat WHERE id_aspirasi='$id_asp' AND sender_type='admin' AND is_read=0");
                        $unread_chat = mysqli_fetch_assoc($cek_chat)['unread'];
                        
                        // Cek apakah chat disabled (saat status Selesai)
                        $is_chat_disabled = ($st == 'Selesai');
                    ?>
                    <tr>
                        <td class="ps-4 text-muted small">
                            <i class="bi bi-calendar3 me-2"></i><?php echo date('d M Y', strtotime($row['tgl_input'])); ?>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-primary border border-info mb-1"><?php echo $row['ket_kategori']; ?></span>
                            <div class="small text-secondary"><i class="bi bi-geo-alt-fill text-danger"></i> <?php echo $row['lokasi']; ?></div>
                        </td>
                        <td>
                            <div class="text-secondary small text-truncate" style="max-width: 250px;" title="<?php echo htmlspecialchars($row['ket']); ?>">
                                "<?php echo htmlspecialchars($row['ket']); ?>"
                            </div>
                        </td>
                        <td class="text-center">
                            <?php if($st == 'Proses'): ?>
                                <span class="badge rounded-pill bg-primary mb-2 px-3">Diproses</span><br>
                                <button onclick="konfirmasiAksi('selesai', '<?php echo $id_asp; ?>')" class="btn btn-outline-success btn-sm py-0 px-2" style="font-size: 0.7rem; border-radius: 20px;">
                                    <i class="bi bi-check-lg"></i> Selesai?
                                </button>
                            <?php elseif($st == 'Selesai'): ?>
                                <span class="badge rounded-pill bg-success mb-2 px-3">Selesai</span><br>
                                <button onclick="konfirmasiAksi('tolak', '<?php echo $id_asp; ?>')" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 0.7rem; border-radius: 20px;">
                                    <i class="bi bi-arrow-counterclockwise"></i> Buka Lagi
                                </button>
                            <?php elseif($st == 'Ditolak'): ?>
                                <span class="badge rounded-pill bg-danger px-3"><?php echo $st; ?></span>
                            <?php else: ?>
                                <span class="badge rounded-pill bg-warning text-dark px-3"><?php echo $st; ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center pe-4">
                            <?php if($is_chat_disabled): ?>
                                <button class="btn btn-sm btn-secondary opacity-50 rounded-pill px-3 py-1 position-relative shadow-sm" disabled style="cursor: not-allowed;">
                                    <i class="bi bi-chat-dots-fill me-1"></i> Chat
                                </button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-light border text-primary rounded-pill px-3 py-1 position-relative shadow-sm hover-effect" 
                                        onclick="window.location.href='?nis=<?php echo $nis; ?>&open_chat=<?php echo $id_asp; ?>'">
                                    <i class="bi bi-chat-dots-fill me-1"></i> Chat
                                    <?php if($unread_chat > 0): ?>
                                        <span class="red-dot"></span>
                                    <?php endif; ?>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php if(isset($_GET['open_chat']) && $_GET['open_chat'] == $id_asp): ?>
                    <div class="modal fade show" id="modalChat<?php echo $id_asp; ?>" tabindex="-1" aria-modal="true" role="dialog" style="display: block; background: rgba(0,0,0,0.5);">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                                <div class="modal-header bg-primary text-white">
                                    <h6 class="modal-title fw-bold"><i class="bi bi-chat-text me-2"></i>Diskusi Laporan</h6>
                                    <a href="dashboard_siswa.php?nis=<?php echo $nis; ?>" class="btn-close btn-close-white"></a>
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

                                    <div class="chat-box" id="chatContainer">
                                        <?php
                                        // Ambil history chat
                                        $chats = mysqli_query($koneksi, "SELECT * FROM aspirasi_chat WHERE id_aspirasi='$id_asp' ORDER BY created_at ASC");
                                        if(mysqli_num_rows($chats) > 0){
                                            while($c = mysqli_fetch_assoc($chats)){
                                                $is_me = ($c['sender_type'] == 'siswa');
                                                echo '<div class="chat-bubble '.($is_me ? 'chat-me' : 'chat-admin').'">';
                                                echo nl2br(htmlspecialchars($c['message']));
                                                echo '<span class="chat-time text-'.($is_me?'light':'muted').'">'.date('d M H:i', strtotime($c['created_at'])).'</span>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo '<div class="text-center text-muted small mt-5">Belum ada percakapan.</div>';
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
                                                      rows="2" placeholder="Tulis pesan..." 
                                                      style="resize: none; font-size: 0.9rem;" required></textarea>
                                            
                                            <div class="border-start mx-2" style="height: 25px; border-color: #ddd;"></div>
                                            
                                            <button type="submit" name="kirim_chat" class="btn btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center flex-shrink-0" 
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

                    <?php endwhile; else: ?>
                    <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada riwayat laporan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto scroll chat ke bawah
<?php if(isset($_GET['open_chat'])): ?>
    var chatContainer = document.getElementById("chatContainer");
    if(chatContainer) { chatContainer.scrollTop = chatContainer.scrollHeight; }
<?php endif; ?>

function konfirmasiAksi(tipe, id) {
    Swal.fire({
        title: tipe === 'selesai' ? 'Sudah Beres?' : 'Masih Bermasalah?',
        text: tipe === 'selesai' ? 'Yakin fasilitas sudah diperbaiki?' : 'Ingin membuka kembali laporan ini untuk diproses ulang?',
        icon: 'question', 
        showCancelButton: true, 
        confirmButtonColor: '#004aad',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Lanjutkan', 
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) window.location.href = `dashboard_siswa.php?nis=<?php echo $nis; ?>&id=${id}&aksi=${tipe}`;
    });
}
</script>
</body>
</html>