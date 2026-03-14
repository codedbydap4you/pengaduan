<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengaduan - SMKN 2 Magelang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-blue: #004aad; 
            --accent-yellow: #fdb913; 
            --dark-overlay: rgba(0, 36, 88, 0.70); /* Overlay untuk Hero */
            --footer-overlay: rgba(0, 36, 88, 0.90); /* Overlay lebih gelap untuk Footer */
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* NAVBAR */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        .navbar-brand img {
            height: 50px;
            margin-right: 10px;
        }
        .navbar-brand span {
            font-weight: 700;
            color: var(--primary-blue);
            font-size: 1.1rem;
            line-height: 1.2;
            display: inline-block;
            vertical-align: middle;
        }
        .btn-login {
            border: 2px solid var(--primary-blue);
            color: var(--primary-blue);
            font-weight: 600;
            padding: 8px 25px;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background-color: var(--primary-blue);
            color: white;
        }
        .btn-register {
            background-color: var(--accent-yellow);
            border: 2px solid var(--accent-yellow);
            color: #000;
            font-weight: 600;
            padding: 8px 25px;
            border-radius: 50px;
            margin-left: 10px;
            transition: all 0.3s;
        }
        .btn-register:hover {
            background-color: #e5a500;
            border-color: #e5a500;
        }

        /* HERO SECTION */
        .hero-section {
            background: linear-gradient(var(--dark-overlay), var(--dark-overlay)), url('img/gedung.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 90vh;
            display: flex;
            align-items: center;
            color: white;
            text-align: center;
            position: relative;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 40px;
            font-weight: 300;
            opacity: 0.9;
        }
        .btn-cta {
            background-color: var(--accent-yellow);
            color: #000;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 50px;
            border: none;
            transition: transform 0.3s;
            text-decoration: none;
        }
        .btn-cta:hover {
            transform: translateY(-5px);
            background-color: #e5a500;
            color: #000;
        }

        /* SECTION FITUR */
        .feature-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            height: 100%;
            border-bottom: 5px solid var(--primary-blue);
        }
        .feature-box:hover {
            transform: translateY(-10px);
        }
        .feature-icon {
            font-size: 3rem;
            color: var(--primary-blue);
            margin-bottom: 20px;
        }

        /* JURUSAN SECTION */
        .jurusan-section {
            background-color: #f8f9fa;
            padding: 80px 0;
        }
        .jurusan-card {
            background: white;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
            text-align: center;
            padding: 30px 20px;
            height: 100%;
        }
        .jurusan-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .jurusan-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-blue);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
        }
        .jurusan-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        /* FOOTER (BAGIAN YANG DIUBAH) */
        footer {
            /* Menggunakan gambar gedung dengan overlay yang lebih gelap (0.9) agar teks terbaca */
            background: linear-gradient(var(--footer-overlay), var(--footer-overlay)), url('img/gedung.jpg');
            background-size: cover;
            background-position: center; /* Fokus ke bagian bawah gedung jika dicrop */
            color: white;
            padding: 60px 0 30px 0;
            margin-top: auto;
        }
        .footer-logo {
            height: 70px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="img\logosmk.png" alt="Logo SMKN 2 Magelang">
                <span>SMK NEGERI 2<br><small>MAGELANG</small></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark fw-semibold" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link text-dark fw-semibold" href="#jurusan">Jurusan</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link text-dark fw-semibold" href="#kontak">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php" class="btn btn-login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="registrasi.php" class="btn btn-register">Daftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header id="beranda" class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h1 class="hero-title">Sistem Pengaduan Layanan<br>Fasilitas Sekolah</h1>
                    <p class="hero-subtitle">Wujudkan lingkungan belajar yang nyaman dan kondusif di SMK Negeri 2 Magelang dengan menyampaikan aspirasi Anda secara mudah dan transparan.</p>
                    <a href="index.php" class="btn-cta shadow">LAPORKAN SEKARANG</a>
                </div>
            </div>
        </div>
    </header>

    <section class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h6 class="text-primary fw-bold text-uppercase ls-2">Alur Pengaduan</h6>
                <h2 class="fw-bold">Bagaimana Cara Melapor?</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <i class="bi bi-person-plus-fill feature-icon"></i>
                        <h4>1. Daftar & Login</h4>
                        <p class="text-muted">Siswa melakukan registrasi menggunakan NIS dan Login ke dalam sistem.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <i class="bi bi-pencil-square feature-icon"></i>
                        <h4>2. Tulis Laporan</h4>
                        <p class="text-muted">Pilih kategori fasilitas (Kelas, Lab, Kantin, dll), tulis keluhan, dan kirim.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <i class="bi bi-check-circle-fill feature-icon"></i>
                        <h4>3. Tindak Lanjut</h4>
                        <p class="text-muted">Admin akan memproses laporan Anda. Anda dapat memantau status hingga selesai.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="jurusan" class="jurusan-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Kompetensi Keahlian</h2>
                <p class="text-muted">SMK Negeri 2 Magelang memiliki 4 program keahlian unggulan</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-3">
                    <div class="jurusan-card">
                        <div class="jurusan-icon">
                            <i class="bi bi-code-slash"></i>
                        </div>
                        <h5 class="jurusan-title">PPLG</h5>
                        <p class="small text-muted">Pengembangan Perangkat Lunak dan Gim</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="jurusan-card">
                        <div class="jurusan-icon" style="background-color: #fdb913; color: black;">
                            <i class="bi bi-building"></i>
                        </div>
                        <h5 class="jurusan-title">MPLB</h5>
                        <p class="small text-muted">Manajemen Perkantoran dan Layanan Bisnis</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="jurusan-card">
                        <div class="jurusan-icon">
                            <i class="bi bi-shop"></i>
                        </div>
                        <h5 class="jurusan-title">PEMASARAN</h5>
                        <p class="small text-muted">Bisnis Digital dan Pemasaran</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="jurusan-card">
                        <div class="jurusan-icon" style="background-color: #fdb913; color: black;">
                            <i class="bi bi-calculator"></i>
                        </div>
                        <h5 class="jurusan-title">AKL</h5>
                        <p class="small text-muted">Akuntansi dan Keuangan Lembaga</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="kontak">
        <div class="container text-center">
            <img src="img\logosmk-removebg-preview.png" alt="Logo" class="footer-logo">
            <h4 class="fw-bold">SMK NEGERI 2 MAGELANG</h4>
            <p class="mb-4 text-white-50">Jl. Jend. Ahmad Yani No. 135A, Magelang, Jawa Tengah</p>
            
            <div class="d-flex justify-content-center gap-3 mb-4">
                <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-white fs-4"><i class="bi bi-youtube"></i></a>
                <a href="#" class="text-white fs-4"><i class="bi bi-globe"></i></a>
            </div>
            
            <hr style="opacity: 0.3; border-color: white;">
            <p class="small mb-0 text-white-50">&copy; 2026 Sistem Aspirasi Siswa - All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>sakjshdh