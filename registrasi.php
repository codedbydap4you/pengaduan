<?php
include 'config/koneksi.php'; 

if (isset($_POST['register'])) {
    $nis   = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $pass  = mysqli_real_escape_string($koneksi, $_POST['password']);

    $cek_nis = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis = '$nis'");
    
    if (mysqli_num_rows($cek_nis) > 0) {
        echo "<script>alert('NIS sudah terdaftar! Silakan langsung login.');</script>";
    } else {
        $query = "INSERT INTO siswa (nis, kelas, password) VALUES ('$nis', '$kelas', '$pass')";
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Gagal Registrasi: " . mysqli_error($koneksi) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa - SMKN 2 Magelang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-blue: #004aad; 
            --accent-yellow: #fdb913; 
            --dark-overlay: rgba(0, 36, 88, 0.85); 
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(var(--dark-overlay), var(--dark-overlay)), url('img/gedung.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 0; /* Memberi ruang saat layar pendek */
        }

        /* Container Pembungkus agar Card berada ditengah sempurna */
        .wrapper {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .card-register {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            background-color: #ffffff;
            width: 100%;
            max-width: 400px; /* Lebar ideal untuk form login/regis */
            overflow: hidden;
        }

        .card-body {
            padding: 2.5rem !important; /* Spasi dalam lebih lega */
        }

        .logo-reg {
            width: 75px;
            height: auto;
            margin-bottom: 15px;
        }

        .reg-title {
            color: var(--primary-blue);
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        /* Styling Input Group */
        .input-group {
            background-color: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #dee2e6;
            transition: all 0.3s;
        }

        .input-group:focus-within {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.25rem rgba(0, 74, 173, 0.1);
        }

        .input-group-text {
            background-color: transparent;
            border: none;
            color: var(--primary-blue);
            padding-left: 15px;
        }

        .form-control {
            background-color: transparent;
            border: none;
            padding: 12px 15px 12px 5px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            background-color: transparent;
            box-shadow: none;
            border: none;
        }

        /* Button Styling */
        .btn-reg {
            background-color: var(--accent-yellow);
            border: none;
            color: #000;
            border-radius: 10px;
            padding: 12px;
            font-weight: 700;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .btn-reg:hover {
            background-color: #e5a500;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(253, 185, 19, 0.3);
        }

        .link-back {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.85rem;
            margin-top: 20px;
            transition: 0.3s;
        }

        .link-back:hover {
            color: var(--accent-yellow);
        }
        
        label {
            font-size: 0.8rem;
            margin-bottom: 5px;
            color: #555;
            display: block;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-4">
                
                <div class="card card-register">
                    <div class="card-body text-center">
                        
                        <img src="img/logosmk.png" alt="Logo SMKN 2" class="logo-reg">
                        <h4 class="reg-title mb-1">REGISTRASI</h4>
                        <p class="text-muted small mb-4">Buat akun untuk lapor fasilitas</p>

                        <form action="" method="POST" class="text-start">
                            <div class="mb-3">
                                <label class="fw-bold">NIS </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                    <input type="number" name="nis" class="form-control" placeholder="12345" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Kelas</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-door-open"></i></span>
                                    <input type="text" name="kelas" class="form-control" placeholder="XII PPLG 2" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="fw-bold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="Buat password baru" required>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="register" class="btn btn-reg">DAFTAR AKUN</button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="small text-muted mb-0">Sudah punya akun? 
                                    <a href="index.php" class="text-primary fw-bold text-decoration-none">Login</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>