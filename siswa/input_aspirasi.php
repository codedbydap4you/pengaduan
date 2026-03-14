<?php
// 1. Koneksi ke database
include '../config/koneksi.php'; 

// 2. Menangkap NIS dari URL
$nis = isset($_GET['nis']) ? $_GET['nis'] : '';

// 3. LOGIKA PROSES
if (isset($_POST['submit'])) {
    $nis_input   = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $id_kategori = mysqli_real_escape_string($koneksi, $_POST['id_kategori']);
    $lokasi      = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    $ket         = mysqli_real_escape_string($koneksi, $_POST['ket']);

    // Query Insert
    $sql = "INSERT INTO input_aspirasi (nis, id_kategori, lokasi, ket) 
            VALUES ('$nis_input', '$id_kategori', '$lokasi', '$ket')";

    if (mysqli_query($koneksi, $sql)) {
        // Redirect dengan parameter sukses untuk memicu SweetAlert
        header("Location: dashboard_siswa.php?nis=$nis_input&status=sukses");
        exit;
    } else {
        $error_msg = mysqli_error($koneksi);
    }
}

// 4. Ambil data kategori untuk Dropdown
$query_kategori = "SELECT * FROM kategori";
$data_kategori = mysqli_query($koneksi, $query_kategori);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Aspirasi - SMKN 2 Magelang</title>
    
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

        /* --- Sidebar Styling (Sama Persis dengan Dashboard) --- */
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
        .sidebar-logo h5 {
            font-weight: 700;
            color: var(--primary-blue);
            font-size: 1rem;
            margin: 0;
            line-height: 1.2;
        }

        .nav-link {
            color: #6c757d;
            font-weight: 500;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 5px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .nav-link i { font-size: 1.2rem; margin-right: 12px; }
        
        .nav-link:hover {
            color: var(--primary-blue);
            background-color: #f0f4ff;
        }

        .nav-link.active {
            background-color: var(--primary-blue);
            color: white;
            box-shadow: 0 4px 10px rgba(0, 74, 173, 0.3);
        }

        .user-profile {
            margin-top: auto;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* --- Main Content --- */
        .main-content {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 30px;
            width: 100%;
            display: flex;
            justify-content: center; /* Center form horizontally */
            align-items: flex-start;
        }

        /* --- Form Card Styling --- */
        .card-form {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
            width: 100%;
            max-width: 800px; /* Batas lebar form agar rapi */
        }

        .card-header-custom {
            background: var(--primary-blue);
            padding: 25px;
            color: white;
            text-align: center;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid #dee2e6;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.25rem rgba(0, 74, 173, 0.15);
        }

        .btn-submit {
            background-color: var(--primary-blue);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background-color: #003075;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 74, 173, 0.3);
        }

        .btn-cancel {
            background-color: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-cancel:hover {
            background-color: #e9ecef;
            color: #495057;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="../img/logosmk.png" alt="Logo">
        <div>
            <h5>SMKN 2</h5>
            <small class="text-muted">MAGELANG</small>
        </div>
    </div>

    <nav class="nav flex-column">
        <a href="#" class="nav-link active">
            <i class="bi bi-pencil-square"></i> Buat Laporan
        </a>
        <a href="dashboard_siswa.php?nis=<?php echo $nis; ?>" class="nav-link">
            <i class="bi bi-clock-history"></i> Histori Aspirasi
        </a>
    </nav>

    <div class="user-profile">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="bi bi-person-fill"></i>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div class="fw-bold text-truncate small">NIS: <?php echo htmlspecialchars($nis); ?></div>
            <div class="text-muted" style="font-size: 0.75rem;">Siswa</div>
        </div>
        <a href="../landing.php" class="text-danger" title="Logout"><i class="bi bi-box-arrow-right"></i></a>
    </div>
</aside>

<main class="main-content">
    
    <div class="card card-form">
        <div class="card-header-custom">
            <h4 class="mb-1 fw-bold"><i class="bi bi-megaphone-fill me-2"></i> Form Pengaduan Sarana</h4>
            <p class="mb-0 opacity-75 small">Silakan isi detail kerusakan fasilitas sekolah dengan jelas.</p>
        </div>

        <div class="card-body p-4 p-md-5">
            
            <?php if(isset($error_msg)): ?>
                <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>Gagal mengirim: <?php echo $error_msg; ?></div>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <input type="hidden" name="nis" value="<?php echo $nis; ?>">

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Kategori Sarana</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-grid text-primary"></i></span>
                            <select name="id_kategori" class="form-select border-start-0 ps-0" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php while($kat = mysqli_fetch_assoc($data_kategori)) { ?>
                                    <option value="<?php echo $kat['id_kategori']; ?>">
                                        <?php echo $kat['ket_kategori']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Lokasi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="bi bi-geo-alt text-danger"></i></span>
                            <input type="text" name="lokasi" class="form-control border-start-0 ps-0" placeholder="Contoh: Lab Komputer 1" required maxlength="50">
                        </div>
                        <div class="form-text small text-end">“Masukkan lokasi atau bagian terkait”</div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Detail Masalah</label>
                    <textarea name="ket" class="form-control" rows="5" placeholder="Jelaskan secara rinci masalah anda..." required maxlength="50"></textarea>
                    <div class="form-text small text-end">Jelaskan dengan singkat & jelas (Max 50 char)</div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="dashboard_siswa.php?nis=<?php echo $nis; ?>" class="btn btn-cancel">Batal</a>
                    <button type="submit" name="submit" class="btn btn-primary btn-submit shadow-sm">
                        <i class="bi bi-send-fill me-2"></i> Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>