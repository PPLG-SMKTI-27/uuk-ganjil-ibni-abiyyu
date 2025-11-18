<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Peminjaman - Lab PPLG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-approved { background-color: #0dcaf0; color: #000; }
        .badge-dipinjam { background-color: #0d6efd; }
        .badge-dikembalikan { background-color: #198754; }
        .badge-ditolak { background-color: #dc3545; }
        .badge-overdue { 
            background-color: #dc3545; 
            color: white;
            animation: blink 1s infinite;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        .nav-tabs .nav-link.active {
            font-weight: 600;
            border-bottom: 3px solid #0d6efd;
        }
        .overdue-row {
            background-color: #fff5f5 !important;
            border-left: 4px solid #dc3545;
        }
        .warning-row {
            background-color: #fffbf0 !important;
            border-left: 4px solid #ffc107;
        }
        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .indicator-overdue { background-color: #dc3545; }
        .indicator-warning { background-color: #ffc107; }
        .indicator-normal { background-color: #198754; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container mt-4">
        <!-- Header dengan Tab Navigation -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-clipboard-list me-2"></i>Kelola Peminjaman</h2>
            <div>
                <?php if (isset($data['overdue_count']) && $data['overdue_count'] > 0): ?>
                    <a href="index.php?page=peminjaman&action=overdue" class="btn btn-danger position-relative me-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>Terlambat
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">
                            <?php echo $data['overdue_count']; ?>
                            <span class="visually-hidden">overdue items</span>
                        </span>
                    </a>
                <?php endif; ?>
                <?php if (isset($data['pending_count']) && $data['pending_count'] > 0): ?>
                    <a href="index.php?page=peminjaman&action=pending" class="btn btn-warning position-relative">
                        <i class="fas fa-clock me-1"></i>Pending
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $data['pending_count']; ?>
                            <span class="visually-hidden">pending approvals</span>
                        </span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Warning untuk yang hampir terlambat -->
        <?php if (isset($data['warning_count']) && $data['warning_count'] > 0): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-clock me-2"></i>
                Ada <strong><?php echo $data['warning_count']; ?> peminjaman</strong> yang mendekati batas tanggal kembali.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Tab Navigation -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?page=peminjaman">
                            <i class="fas fa-list me-1"></i>Semua Peminjaman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=peminjaman&action=overdue">
                            <i class="fas fa-exclamation-triangle me-1"></i>Keterlambatan
                            <?php if (isset($data['overdue_count']) && $data['overdue_count'] > 0): ?>
                                <span class="badge bg-danger ms-1"><?php echo $data['overdue_count']; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=peminjaman&action=pending">
                            <i class="fas fa-clock me-1"></i>Menunggu Approval
                            <?php if (isset($data['pending_count']) && $data['pending_count'] > 0): ?>
                                <span class="badge bg-warning ms-1"><?php echo $data['pending_count']; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <?php if (empty($data['peminjaman'])): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada data peminjaman</h5>
                        <p class="text-muted">Belum ada pengajuan peminjaman alat</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Peminjam</th>
                                    <th>Alat</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Keperluan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($data['peminjaman'] as $peminjaman): 
                                    // Hitung status keterlambatan
                                    $today = new DateTime();
                                    $tgl_kembali = new DateTime($peminjaman['tanggal_kembali']);
                                    $is_overdue = false;
                                    $is_warning = false;
                                    $row_class = '';
                                    
                                    if ($peminjaman['status'] == 'dipinjam') {
                                        if ($today > $tgl_kembali) {
                                            $is_overdue = true;
                                            $row_class = 'overdue-row';
                                        } elseif ($today->diff($tgl_kembali)->days <= 2) {
                                            $is_warning = true;
                                            $row_class = 'warning-row';
                                        }
                                    }
                                ?>
                                <tr class="<?php echo $row_class; ?>">
                                    <td>
                                        <?php if ($is_overdue): ?>
                                            <span class="status-indicator indicator-overdue"></span>
                                        <?php elseif ($is_warning): ?>
                                            <span class="status-indicator indicator-warning"></span>
                                        <?php else: ?>
                                            <span class="status-indicator indicator-normal"></span>
                                        <?php endif; ?>
                                        <?php echo $no++; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($peminjaman['nama_peminjam']); ?></strong>
                                        <?php if ($is_overdue): ?>
                                            <br><small class="text-danger"><i class="fas fa-exclamation-circle"></i> Terlambat!</small>
                                        <?php elseif ($is_warning): ?>
                                            <br><small class="text-warning"><i class="fas fa-clock"></i> Hampir jatuh tempo</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($peminjaman['nama_alat']); ?></strong>
                                    </td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($peminjaman['tanggal_pinjam'])); ?>
                                    </td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($peminjaman['tanggal_kembali'])); ?>
                                        <?php if ($is_overdue): ?>
                                            <br><small class="text-danger">(Melebihi batas)</small>
                                        <?php elseif ($is_warning): ?>
                                            <br><small class="text-warning">(Segera kembali)</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo $peminjaman['jumlah']; ?> unit</span>
                                    </td>
                                    <td>
                                        <?php
                                        $status_config = [
                                            'pending' => ['badge-pending', 'Menunggu', 'clock'],
                                            'approved' => ['badge-approved', 'Disetujui', 'check'],
                                            'dipinjam' => ['badge-dipinjam', 'Dipinjam', 'hand-holding'],
                                            'dikembalikan' => ['badge-dikembalikan', 'Dikembalikan', 'check-double'],
                                            'ditolak' => ['badge-ditolak', 'Ditolak', 'times']
                                        ];
                                        $status = $peminjaman['status'];
                                        ?>
                                        <span class="badge <?php echo $status_config[$status][0]; ?>">
                                            <i class="fas fa-<?php echo $status_config[$status][2]; ?> me-1"></i>
                                            <?php echo $status_config[$status][1]; ?>
                                        </span>
                                        <?php if ($is_overdue): ?>
                                            <br><span class="badge overdue mt-1">TERLAMBAT</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo htmlspecialchars($peminjaman['keperluan']); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <?php if ($peminjaman['status'] == 'pending'): ?>
                                                <!-- Approve/Reject for pending -->
                                                <a href="index.php?page=peminjaman&action=approve&id=<?php echo $peminjaman['id']; ?>" 
                                                   class="btn btn-success" 
                                                   title="Setujui Peminjaman"
                                                   onclick="return confirm('Setujui peminjaman <?php echo htmlspecialchars($peminjaman['nama_alat']); ?> oleh <?php echo htmlspecialchars($peminjaman['nama_peminjam']); ?>?')">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="index.php?page=peminjaman&action=reject&id=<?php echo $peminjaman['id']; ?>" 
                                                   class="btn btn-danger"
                                                   title="Tolak Peminjaman"
                                                   onclick="return confirm('Tolak peminjaman <?php echo htmlspecialchars($peminjaman['nama_alat']); ?> oleh <?php echo htmlspecialchars($peminjaman['nama_peminjam']); ?>?')">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            <?php elseif ($peminjaman['status'] == 'approved'): ?>
                                                <!-- Start peminjaman -->
                                                <a href="index.php?page=peminjaman&action=start&id=<?php echo $peminjaman['id']; ?>" 
                                                   class="btn btn-primary"
                                                   title="Mulai Peminjaman"
                                                   onclick="return confirm('Mulai peminjaman <?php echo htmlspecialchars($peminjaman['nama_alat']); ?>?')">
                                                    <i class="fas fa-play"></i>
                                                </a>
                                            <?php elseif ($peminjaman['status'] == 'dipinjam'): ?>
                                                <!-- Complete peminjaman -->
                                                <a href="index.php?page=peminjaman&action=complete&id=<?php echo $peminjaman['id']; ?>" 
                                                   class="btn btn-success"
                                                   title="Selesaikan Peminjaman"
                                                   onclick="return confirm('Tandai peminjaman <?php echo htmlspecialchars($peminjaman['nama_alat']); ?> sebagai selesai?')">
                                                    <i class="fas fa-check-double"></i>
                                                </a>
                                            <?php else: ?>
                                                <!-- No actions for completed/rejected -->
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Stats -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <div class="row text-center">
                                        <div class="col">
                                            <small class="text-muted">Total: <strong><?php echo count($data['peminjaman']); ?></strong></small>
                                        </div>
                                        <div class="col">
                                            <small>
                                                <span class="badge badge-pending">Pending: <?php echo array_filter($data['peminjaman'], fn($p) => $p['status'] == 'pending') ? count(array_filter($data['peminjaman'], fn($p) => $p['status'] == 'pending')) : 0; ?></span>
                                            </small>
                                        </div>
                                        <div class="col">
                                            <small>
                                                <span class="badge badge-approved">Approved: <?php echo array_filter($data['peminjaman'], fn($p) => $p['status'] == 'approved') ? count(array_filter($data['peminjaman'], fn($p) => $p['status'] == 'approved')) : 0; ?></span>
                                            </small>
                                        </div>
                                        <div class="col">
                                            <small>
                                                <span class="badge badge-dipinjam">Active: <?php echo array_filter($data['peminjaman'], fn($p) => $p['status'] == 'dipinjam') ? count(array_filter($data['peminjaman'], fn($p) => $p['status'] == 'dipinjam')) : 0; ?></span>
                                            </small>
                                        </div>
                                        <div class="col">
                                            <small>
                                                <span class="badge bg-danger">Terlambat: <?php 
                                                    $overdue_count = 0;
                                                    foreach ($data['peminjaman'] as $p) {
                                                        if ($p['status'] == 'dipinjam') {
                                                            $today = new DateTime();
                                                            $tgl_kembali = new DateTime($p['tanggal_kembali']);
                                                            if ($today > $tgl_kembali) {
                                                                $overdue_count++;
                                                            }
                                                        }
                                                    }
                                                    echo $overdue_count;
                                                ?></span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Quick Guide</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <span class="badge badge-pending p-2 mb-2">Pending</span>
                                <p class="small text-muted mb-0">Menunggu approval</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge badge-approved p-2 mb-2">Approved</span>
                                <p class="small text-muted mb-0">Disetujui</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge badge-dipinjam p-2 mb-2">Dipinjam</span>
                                <p class="small text-muted mb-0">Sedang dipinjam</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge badge-dikembalikan p-2 mb-2">Selesai</span>
                                <p class="small text-muted mb-0">Sudah dikembalikan</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge bg-warning p-2 mb-2">Warning</span>
                                <p class="small text-muted mb-0">≤ 2 hari lagi</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge overdue p-2 mb-2">Terlambat</span>
                                <p class="small text-muted mb-0">Melebihi batas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Confirm before actions
            const actionButtons = document.querySelectorAll('a[href*="approve"], a[href*="reject"], a[href*="start"], a[href*="complete"]');
            actionButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!this.getAttribute('onclick')) {
                        if (!confirm('Apakah Anda yakin ingin melakukan aksi ini?')) {
                            e.preventDefault();
                        }
                    }
                });
            });
        });

        // Auto-refresh page every 5 minutes to update status
        setTimeout(function() {
            location.reload();
        }, 300000); // 5 minutes
    </script>
</body>
</html>