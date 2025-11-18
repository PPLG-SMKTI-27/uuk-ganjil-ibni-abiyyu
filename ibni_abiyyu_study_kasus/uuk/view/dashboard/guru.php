<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - Lab PPLG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .dashboard-card:hover {
            transform: translateY(-2px);
        }
        .logout-btn {
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            transform: scale(1.05);
        }
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        .badge-available {
            background-color: #198754;
        }
        .badge-low {
            background-color: #ffc107;
            color: #000;
        }
        .badge-out {
            background-color: #dc3545;
        }
        .status-badge {
            font-size: 0.75rem;
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
                <h2 class="mb-1"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard Guru</h2>
                <p class="text-muted mb-0">Selamat datang di sistem peminjaman alat lab PPLG</p>
            </div>
            <a href="index.php?page=auth&action=logout" class="btn btn-danger logout-btn">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a>
        </div>

        <div class="row">
            <!-- Alat Tersedia -->
            <div class="col-md-8">
                <div class="card mb-4 dashboard-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Alat Tersedia</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($data['alat_tersedia'])): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Tidak ada alat tersedia saat ini</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Alat</th>
                                            <th>Kategori</th>
                                            <th>Jumlah Tersedia</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['alat_tersedia'] as $alat): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($alat['nama']); ?></strong>
                                                <?php if (!empty($alat['deskripsi'])): ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($alat['deskripsi']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo htmlspecialchars($alat['kategori']); ?></span>
                                            </td>
                                            <td>
                                                <strong><?php echo $alat['jumlah']; ?></strong> unit
                                            </td>
                                            <td>
                                                <?php if ($alat['jumlah'] > 5): ?>
                                                    <span class="badge badge-available status-badge">Tersedia</span>
                                                <?php elseif ($alat['jumlah'] > 0): ?>
                                                    <span class="badge badge-low status-badge">Terbatas</span>
                                                <?php else: ?>
                                                    <span class="badge badge-out status-badge">Habis</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($alat['jumlah'] > 0): ?>
                                                    <a href="index.php?page=peminjaman&action=create&alat_id=<?php echo $alat['id']; ?>" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-hand-holding me-1"></i>Pinjam
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-times me-1"></i>Habis
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Info Akun & Quick Actions -->
            <div class="col-md-4">
                <!-- Info Akun -->
                <div class="card mb-4 dashboard-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Info Akun</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong><i class="fas fa-user me-2 text-primary"></i>Nama:</strong>
                            <p class="ms-4 mb-1"><?php echo htmlspecialchars($_SESSION['nama']); ?></p>
                        </div>
                        <div class="mb-3">
                            <strong><i class="fas fa-user-tag me-2 text-warning"></i>Role:</strong>
                            <p class="ms-4 mb-1"><span class="badge bg-warning">Guru</span></p>
                        </div>
                        <div class="mb-3">
                            <strong><i class="fas fa-at me-2 text-secondary"></i>Username:</strong>
                            <p class="ms-4 mb-1"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mb-4 dashboard-card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="index.php?page=peminjaman&action=create" class="btn btn-primary">
                                <i class="fas fa-hand-holding me-2"></i>Pinjam Alat
                            </a>
                            <a href="index.php?page=peminjaman&action=riwayat" class="btn btn-outline-info">
                                <i class="fas fa-history me-2"></i>Riwayat Peminjaman
                            </a>
                            <a href="index.php?page=auth&action=profile" class="btn btn-outline-secondary">
                                <i class="fas fa-user-edit me-2"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                </div>


        <!-- Stats Summary -->
        <?php if (!empty($data['riwayat_peminjaman'])): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body py-3">
                        <div class="row text-center">
                            <div class="col">
                                <h4 class="mb-1 text-primary"><?php echo count($data['riwayat_peminjaman']); ?></h4>
                                <small class="text-muted">Total Pengajuan</small>
                            </div>
                            <div class="col">
                                <h4 class="mb-1 text-warning"><?php echo count(array_filter($data['riwayat_peminjaman'], fn($p) => $p['status'] == 'pending')); ?></h4>
                                <small class="text-muted">Menunggu</small>
                            </div>
                            <div class="col">
                                <h4 class="mb-1 text-info"><?php echo count(array_filter($data['riwayat_peminjaman'], fn($p) => $p['status'] == 'approved')); ?></h4>
                                <small class="text-muted">Disetujui</small>
                            </div>
                            <div class="col">
                                <h4 class="mb-1 text-success"><?php echo count(array_filter($data['riwayat_peminjaman'], fn($p) => $p['status'] == 'dikembalikan')); ?></h4>
                                <small class="text-muted">Selesai</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
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

            // Auto-update waktu login setiap menit
            function updateLoginTime() {
                const now = new Date();
                const options = { 
                    day: '2-digit', 
                    month: 'short', 
                    year: 'numeric',
                    hour: '2-digit', 
                    minute: '2-digit'
                };
                const timeString = now.toLocaleDateString('id-ID', options);
                
                // Update semua elemen dengan class login-time
                document.querySelectorAll('.login-time').forEach(el => {
                    el.textContent = timeString;
                });
            }

            // Update setiap menit
            setInterval(updateLoginTime, 60000);
        });

        // Highlight overdue items
        function checkOverdue() {
            const rows = document.querySelectorAll('tbody tr');
            const now = new Date();
            
            rows.forEach(row => {
                const returnDateText = row.cells[3]?.textContent.trim();
                const statusBadge = row.cells[2]?.querySelector('.badge');
                
                if (statusBadge && statusBadge.textContent.includes('Dipinjam') && returnDateText) {
                    const returnDate = new Date(returnDateText);
                    if (returnDate < now) {
                        row.style.backgroundColor = 'rgba(220, 53, 69, 0.1)';
                    }
                }
            });
        }

        // Run on page load
        checkOverdue();
    </script>
</body>
</html>