<?php
// Mulai session
session_start();

// Panggil koneksi database (karena file ini di folder admin, kita mundur satu folder pakai '../')
require '../config/koneksi.php';

// Jika admin sudah login, jangan biarkan masuk ke halaman login lagi, langsung lempar ke dashboard
if (isset($_SESSION['status_login']) && $_SESSION['status_login'] == true) {
    header("Location: index.php");
    exit;
}

$error = ''; // Variabel untuk menampung pesan error

// Jika tombol login ditekan
if (isset($_POST['login'])) {
    // Tangkap inputan dan amankan dari SQL Injection dasar
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Cek apakah username ada di database
    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($cek_user) > 0) {
        $data = mysqli_fetch_assoc($cek_user);
        
        // Verifikasi password (mencocokkan inputan dengan hash di database)
        if (password_verify($password, $data['password'])) {
            // Jika benar, buat session
            $_SESSION['status_login'] = true;
            $_SESSION['id_admin'] = $data['id'];
            $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
            
            // Redirect ke halaman dashboard admin
            header("Location: index.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SMAN 1 Coding</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .login-box { width: 100%; max-width: 400px; margin: 80px auto; }
    </style>
</head>
<body>

<div class="container">
    <div class="login-box">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0 fw-bold">🔒 Login Admin</h4>
            </div>
            <div class="card-body p-4">
                
                <!-- Tampilkan pesan error jika ada -->
                <?php if ($error != ''): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100 fw-bold">Masuk</button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="../index.php" class="text-decoration-none text-muted">← Kembali ke Halaman Web</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>