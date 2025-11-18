<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Lab PPLG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .logout-btn {
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            transform: scale(1.05);
        }
        .pending-alert {
            border-left: 4px solid #ffc107;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { background-color: #fff3cd; }
            50% { background-color: #ffeaa7; }
            100% { background-color: #fff3cd; }
        }
        .quick-action-card {
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .quick-action-card:hover {
            border-color: #0d6efd;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container mt-4">
        <!-- Header dengan Tombol Logout -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard Admin</h2>
                <p class="text-muted mb-0">Sistem Manajemen Lab PPLG - Panel Administrator</p>
            </div>
            <div>
                <a href="index.php?page=auth&action=logout" class="btn btn-danger logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>

        <!-- Pending Approval Alert -->
        <?php if (isset($data['pending_count']) && $data['pending_count'] > 0): ?>
        <div class="alert alert-warning pending-alert alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">Ada <?php echo $data['pending_count']; ?> Peminjaman Menunggu Approval!</h5>
                    <p class="mb-0">Segera tinjau pengajuan peminjaman yang menunggu persetujuan Anda.</p>
                </div>
                <a href="index.php?page=peminjaman&action=pending" class="btn btn-warning ms-3">
                    <i class="fas fa-clock me-1"></i>Review Sekarang
                </a>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-primary dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0"><?php echo isset($data['total_alat']) ? $data['total_alat'] : 0; ?></h2>
                                <p class="mb-0">Total Alat</p>
                            </div>
                            <div>
                                <i class="fas fa-tools stat-icon"></i>
                            </div>
                        </div>
                        <a href="index.php?page=alat" class="text-white text-decoration-none small mt-2 d-block">
                            <i class="fas fa-arrow-right me-1"></i>Kelola alat
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0"><?php echo isset($data['total_peminjaman']) ? $data['total_peminjaman'] : 0; ?></h2>
                                <p class="mb-0">Total Peminjaman</p>
                            </div>
                            <div>
                                <i class="fas fa-clipboard-list stat-icon"></i>
                            </div>
                        </div>
                        <a href="index.php?page=peminjaman" class="text-white text-decoration-none small mt-2 d-block">
                            <i class="fas fa-arrow-right me-1"></i>Lihat semua
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-info dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0"><?php echo isset($data['peminjaman_aktif']) ? $data['peminjaman_aktif'] : 0; ?></h2>
                                <p class="mb-0">Sedang Dipinjam</p>
                            </div>
                            <div>
                                <i class="fas fa-sync stat-icon"></i>
                            </div>
                        </div>
                        <a href="index.php?page=peminjaman" class="text-white text-decoration-none small mt-2 d-block">
                            <i class="fas fa-arrow-right me-1"></i>Kelola aktif
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-warning dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0"><?php echo isset($data['pending_count']) ? $data['pending_count'] : 0; ?></h2>
                                <p class="mb-0">Pending Approval</p>
                            </div>
                            <div>
                                <i class="fas fa-clock stat-icon"></i>
                            </div>
                        </div>
                        <a href="index.php?page=peminjaman&action=pending" class="text-white text-decoration-none small mt-2 d-block">
                            <i class="fas fa-arrow-right me-1"></i>Review sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & User Info -->
        <div class="row">
            <!-- Quick Actions -->
            <div class="col-md-8">
                <div class="row">
                    <!-- Management Actions -->
                    <div class="col-md-6 mb-3">
                        <div class="card dashboard-card quick-action-card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Management</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="index.php?page=alat&action=create" class="btn btn-outline-primary text-start">
                                        <i class="fas fa-plus-circle me-2"></i>Tambah Alat Baru
                                    </a>
                                    <a href="index.php?page=alat" class="btn btn-outline-primary text-start">
                                        <i class="fas fa-tools me-2"></i>Kelola Data Alat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Approval Actions -->
                    <div class="col-md-6 mb-3">
                        <div class="card dashboard-card quick-action-card h-100">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-check-double me-2"></i>Approval System</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="index.php?page=peminjaman&action=pending" class="btn btn-outline-warning text-start">
                                        <i class="fas fa-clock me-2"></i>Pending Approval
                                        <?php if (isset($data['pending_count']) && $data['pending_count'] > 0): ?>
                                            <span class="badge bg-danger ms-2"><?php echo $data['pending_count']; ?></span>
                                        <?php endif; ?>
                                    </a>
                                    <a href="index.php?page=peminjaman" class="btn btn-outline-warning text-start">
                                        <i class="fas fa-list me-2"></i>Approved Peminjaman
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="col-12 mb-3">
                        <div class="card dashboard-card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Aktivitas Terkini</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3 mb-2">
                                        <div class="border rounded p-3">
                                            <i class="fas fa-clock text-warning fa-2x mb-2"></i>
                                            <h5><?php echo isset($data['pending_count']) ? $data['pending_count'] : 0; ?></h5>
                                            <small class="text-muted">Menunggu Approval</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <div class="border rounded p-3">
                                            <i class="fas fa-check text-success fa-2x mb-2"></i>
                                            <h5><?php echo isset($data['peminjaman_aktif']) ? $data['peminjaman_aktif'] : 0; ?></h5>
                                            <small class="text-muted">Aktif Hari Ini</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <div class="border rounded p-3">
                                            <i class="fas fa-tools text-primary fa-2x mb-2"></i>
                                            <h5><?php echo isset($data['total_alat']) ? $data['total_alat'] : 0; ?></h5>
                                            <small class="text-muted">Total Inventaris</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <div class="border rounded p-3">
                                            <i class="fas fa-users text-info fa-2x mb-2"></i>
                                            <h5><?php echo isset($data['total_peminjaman']) ? $data['total_peminjaman'] : 0; ?></h5>
                                            <small class="text-muted">Total Transaksi</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Info & System Status -->
            <div class="col-md-4">
                <!-- User Information -->
                <div class="card dashboard-card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Admin Profile</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-user-circle fa-3x text-success mb-2"></i>
                            <h5><?php echo htmlspecialchars($_SESSION['nama']); ?></h5>
                            <span class="badge bg-danger">Administrator</span>
                        </div>
                        
                        <div class="mb-2">
                            <strong><i class="fas fa-at me-2 text-muted"></i>Username:</strong>
                            <span class="float-end"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </div>
                        <div class="mb-2">
                            <strong><i class="fas fa-clock me-2 text-muted"></i>Login:</strong>
                            <span class="float-end"><?php echo date('H:i'); ?></span>
                        </div>
                        <div class="mb-2">
                            <strong><i class="fas fa-calendar me-2 text-muted"></i>Hari:</strong>
                            <span class="float-end"><?php echo date('d M Y'); ?></span>
                        </div>
                        
                        <div class="mt-4 pt-3 border-top">
                            <a href="index.php?page=auth&action=profile" class="btn btn-outline-success w-100 mb-2">
                                <i class="fas fa-user-edit me-2"></i>Edit Profile
                            </a>
                            <a href="index.php?page=auth&action=logout" class="btn btn-outline-danger w-100">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="card dashboard-card border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-server me-2"></i>System Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="fas fa-circle text-success me-2"></i>
                            <span>Application</span>
                            <span class="float-end badge bg-success">Online</span>
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-circle text-success me-2"></i>
                            <span>Database</span>
                            <span class="float-end badge bg-success">Connected</span>
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-circle text-success me-2"></i>
                            <span>Security</span>
                            <span class="float-end badge bg-success">Active</span>
                        </div>
                        <div class="mb-0">
                            <i class="fas fa-circle 
                                <?php echo (isset($data['pending_count']) && $data['pending_count'] > 0) ? 'text-warning' : 'text-success'; ?> 
                                me-2"></i>
                            <span>Approval System</span>
                            <span class="float-end badge 
                                <?php echo (isset($data['pending_count']) && $data['pending_count'] > 0) ? 'bg-warning' : 'bg-success'; ?>">
                                <?php echo (isset($data['pending_count']) && $data['pending_count'] > 0) ? 'Pending' : 'Clear'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Workflow Guide -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-random me-2"></i>Approval Workflow</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-2 mb-3">
                                <div class="bg-warning text-dark rounded p-3">
                                    <i class="fas fa-clock fa-2x mb-2"></i>
                                    <h6>Pending</h6>
                                    <small>Menunggu Approval</small>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <div class="bg-success text-white rounded p-3">
                                    <i class="fas fa-check fa-2x mb-2"></i>
                                    <h6>Approved</h6>
                                    <small>Disetujui</small>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <div class="bg-info text-white rounded p-3">
                                    <i class="fas fa-play fa-2x mb-2"></i>
                                    <h6>Dipinjam</h6>
                                    <small>Sedang Berjalan</small>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <div class="bg-secondary text-white rounded p-3">
                                    <i class="fas fa-check-double fa-2x mb-2"></i>
                                    <h6>Selesai</h6>
                                    <small>Sudah Kembali</small>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <div class="bg-danger text-white rounded p-3">
                                    <i class="fas fa-times fa-2x mb-2"></i>
                                    <h6>Ditolak</h6>
                                    <small>Tidak Disetujui</small>
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <div class="bg-dark text-white rounded p-3">
                                    <i class="fas fa-sync fa-2x mb-2"></i>
                                    <h6>Workflow</h6>
                                    <small>Alur Proses</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Konfirmasi logout
        document.addEventListener('DOMContentLoaded', function() {
            const logoutButtons = document.querySelectorAll('a[href*="logout"]');
            
            logoutButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Apakah Anda yakin ingin logout dari sistem?')) {
                        e.preventDefault();
                    }
                });
            });

            // Auto-update time
            function updateTime() {
                const now = new Date();
                document.querySelectorAll('.login-time').forEach(el => {
                    el.textContent = now.toLocaleTimeString('id-ID');
                });
            }
            setInterval(updateTime, 60000);
        });
    </script>
</body>
</html>