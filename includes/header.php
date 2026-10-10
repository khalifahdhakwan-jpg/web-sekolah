<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMA Negeri 1 Coding</title>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Kasih jarak dari atas karena navbar kita pakai fixed-top */
        body {
            padding-top: 70px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">🎓 SMAN 1 Coding</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="guru.php">Guru</a></li>
                    <li class="nav-item"><a class="nav-link" href="siswa.php">Siswa</a></li>
                    <li class="nav-item"><a class="nav-link" href="jadwal.php">Jadwal</a></li>
                    <li class="nav-item"><a class="nav-link" href="berita.php">Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="galeri.php">Galeri</a></li>
                    <!-- Tombol Login untuk Admin -->
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-warning fw-bold text-dark w-100" href="admin/login.php">Login Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>