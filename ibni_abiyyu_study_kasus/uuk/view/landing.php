<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Lab PPLG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Lab PPLG</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?page=auth&action=login">Login</a>
                <a class="nav-link" href="index.php?page=auth&action=register">Register</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 mb-4">Sistem Manajemen Lab PPLG</h1>
            <p class="lead mb-4">Kelola alat-alat lab dan peminjaman dengan mudah dan efisien</p>
            <a href="index.php?page=auth&action=login" class="btn btn-light btn-lg">Mulai Sekarang</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="feature-icon">🔧</div>
                    <h3>Manajemen Alat</h3>
                    <p>Kelola inventaris alat lab dengan sistem yang terorganisir</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-icon">📋</div>
                    <h3>Peminjaman Mudah</h3>
                    <p>Sistem peminjaman alat yang cepat dan terstruktur</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-icon">👥</div>
                    <h3>Multi User</h3>
                    <p>Mendukung admin, guru, dan siswa dengan hak akses berbeda</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p>&copy; 2024 Lab PPLG. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>