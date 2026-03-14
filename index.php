<?php
include 'config/koneksi.php'; 

if (isset($_POST['login'])) {
    $user_input = mysqli_real_escape_string($koneksi, $_POST['user_input']);
    $password   = mysqli_real_escape_string($koneksi, $_POST['password']);

    // 1. Cek apakah ini SISWA (berdasarkan NIS)
    $query_siswa = "SELECT * FROM siswa WHERE nis='$user_input' AND password='$password'";
    $cek_siswa = mysqli_query($koneksi, $query_siswa);

    if (mysqli_num_rows($cek_siswa) > 0) {
        header("location:siswa/dashboard_siswa.php?nis=$user_input");
        exit;
    } 

    // 2. Cek apakah ini ADMIN (berdasarkan Username)
    $query_admin = "SELECT * FROM admin WHERE username='$user_input' AND password='$password'";
    $cek_admin = mysqli_query($koneksi, $query_admin);

    if (mysqli_num_rows($cek_admin) > 0) {
        header("location:admin/dashboard_admin.php?user=$user_input");
        exit;
    } 

    // Jika tidak keduanya
    $error = "NIS/Username atau Password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMKN 2 Magelang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-blue: #004aad; 
            --accent-yellow: #fdb913; 
            --dark-overlay: rgba(0, 36, 88, 0.85); /* Sedikit lebih gelap dari landing page agar fokus ke form */
        }

        body {
            font-family: 'Poppins', sans-serif;
            /* Background Gambar Sekolah dengan Overlay */
            background: linear-gradient(var(--dark-overlay), var(--dark-overlay)), url('img/gedung.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-login {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.98); /* Sedikit transparan */
        }

        .logo-login {
            width: 80px;
            margin-bottom: 15px;
        }

        .login-title {
            color: var(--primary-blue);
            font-weight: 700;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-blue);
            background-color: #fff;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-right: none;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            color: var(--primary-blue);
        }

        .form-control {
            border-left: none;
        }

        .btn-login {
            background-color: var(--primary-blue);
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background-color: #003075;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 74, 173, 0.3);
        }

        .link-back {
            color: #fff;
            text-decoration: none;
            font-size: 0.9rem;
            margin-top: 20px;
            display: inline-block;
            transition: 0.3s;
            opacity: 0.7;
        }

        .link-back:hover {
            color: var(--accent-yellow);
            opacity: 1;
        }
        
        .alert-custom {
            border-radius: 10px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            
            <div class="card card-login p-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="img/logosmk.png" alt="Logo SMKN 2" class="logo-login">
                        <h4 class="login-title mb-1">LOGIN SISTEM</h4>
                        <p class="text-muted small">Silakan masuk untuk melanjutkan</p>
                    </div>

                    <?php if(isset($error)) { ?>
                        <div class="alert alert-danger alert-custom d-flex align-items-center mb-3" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div><?php echo $error; ?></div>
                        </div>
                    <?php } ?>
                    
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary ms-1">NIS / Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" name="user_input" class="form-control" placeholder="Masukan NIS/Username" required autocomplete="off">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary ms-1">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="********" required>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" name="login" class="btn btn-primary btn-login">MASUK SEKARANG</button>
                        </div>

                        <div class="text-center">
                            <p class="small text-muted mb-0">Belum punya akun? <a href="registrasi.php" class="text-primary fw-bold text-decoration-none">Daftar disini</a></p>
                        </div>
                    </form>
                </div>
            </div>
    
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>