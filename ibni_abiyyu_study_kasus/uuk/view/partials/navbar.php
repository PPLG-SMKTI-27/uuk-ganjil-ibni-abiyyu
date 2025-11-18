<?php
// view/partials/navbar.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-flask me-2"></i>Lab PPLG
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=dashboard">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=alat">
                                <i class="fas fa-tools me-1"></i> Data Alat
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=peminjaman">
                                <i class="fas fa-clipboard-list me-1"></i> Peminjaman
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=peminjaman&action=create">
                                <i class="fas fa-hand-holding me-1"></i> Pinjam Alat
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=peminjaman&action=riwayat">
                                <i class="fas fa-history me-1"></i> Riwayat
                            </a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    
                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> 
                            <?php echo htmlspecialchars($_SESSION['nama']); ?>
                            <span class="badge bg-<?php 
                                echo $_SESSION['role'] == 'admin' ? 'danger' : 
                                    ($_SESSION['role'] == 'guru' ? 'warning' : 'info'); 
                            ?> ms-1">
                                <?php echo ucfirst($_SESSION['role']); ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <div class="dropdown-header">
                                    <small>Login sebagai</small><br>
                                    <strong><?php echo htmlspecialchars($_SESSION['nama']); ?></strong>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="index.php?page=auth&action=profile">
                                    <i class="fas fa-user-edit me-2"></i> Edit Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?page=peminjaman&action=riwayat">
                                    <i class="fas fa-history me-2"></i> Riwayat Saya
                                </a>
                            </li>
                            <?php if ($_SESSION['role'] == 'admin'): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="index.php?page=alat&action=create">
                                        <i class="fas fa-plus-circle me-2"></i> Tambah Alat
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.php?page=peminjaman">
                                        <i class="fas fa-clipboard-check me-2"></i> Kelola Peminjaman
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="index.php?page=auth&action=logout">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light btn-sm me-2" href="index.php?page=auth&action=login">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary btn-sm" href="index.php?page=auth&action=register">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
.navbar-brand {
    font-weight: 700;
    font-size: 1.5rem;
}

.navbar-nav .nav-link {
    border-radius: 0.375rem;
    margin: 0 0.125rem;
    transition: all 0.3s ease;
}

.navbar-nav .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateY(-1px);
}

.dropdown-menu {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-radius: 0.5rem;
}

.dropdown-item {
    border-radius: 0.375rem;
    margin: 0.125rem 0.5rem;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.dropdown-header {
    font-size: 0.875rem;
    color: #6c757d;
}

.badge {
    font-size: 0.7em;
    font-weight: 500;
}

/* Notification bell animation */
@keyframes ring {
    0% { transform: rotate(0deg); }
    25% { transform: rotate(15deg); }
    50% { transform: rotate(-15deg); }
    75% { transform: rotate(15deg); }
    100% { transform: rotate(0deg); }
}

.fa-bell {
    animation: ring 2s ease-in-out infinite;
}

/* Mobile responsiveness */
@media (max-width: 991.98px) {
    .navbar-nav .nav-link {
        margin: 0.125rem 0;
    }
    
    .dropdown-menu {
        border: 1px solid rgba(0, 0, 0, 0.1);
    }
}
</style>
</body>
</html>