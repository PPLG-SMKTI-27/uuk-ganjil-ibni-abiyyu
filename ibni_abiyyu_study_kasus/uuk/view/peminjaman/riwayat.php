<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman - Lab PPLG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-approved { background-color: #0dcaf0; color: #000; }
        .badge-dipinjam { background-color: #0d6efd; }
        .badge-dikembalikan { background-color: #198754; }
        .badge-ditolak { background-color: #dc3545; }
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        .status-legend {
            font-size: 0.875rem;
        }
        .empty-state {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="fas fa-history me-2 text-primary"></i>Riwayat Peminjaman Saya</h2>
                <p class="text-muted mb-0">Lihat semua pengajuan peminjaman alat Anda</p>
            </div>
            <a href="index.php?page=peminjaman&action=create" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i>Ajukan Peminjaman Baru
            </a>
        </div>

        <!-- Success Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Stats Summary -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body py-3">
                        <div class="row text-center">
                            <div class="col">
                                <h4 class="mb-1 text-primary"><?php echo count($data['riwayat']); ?></h4>
                                <small class="text-muted">Total Pengajuan</small>
                            </div>
                            <div class="col">
                                <h4 class="mb-1 text-warning"><?php echo count(array_filter($data['riwayat'], fn($p) => $p['status'] == 'pending')); ?></h4>
                                <small class="text-muted">Menunggu</small>
                            </div>
                            <div class="col">
                                <h4 class="mb-1 text-info"><?php echo count(array_filter($data['riwayat'], fn($p) => $p['status'] == 'approved')); ?></h4>
                                <small class="text-muted">Disetujui</small>
                            </div>
                            <div class="col">
                                <h4 class="mb-1 text-success"><?php echo count(array_filter($data['riwayat'], fn($p) => $p['status'] == 'dikembalikan')); ?></h4>
                                <small class="text-muted">Selesai</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Table -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Peminjaman</h5>
            </div>
            <div class="card-body">
                <?php if (empty($data['riwayat'])): ?>
                    <!-- Empty State -->
                    <div class="text-center py-5 empty-state rounded">
                        <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">Belum ada riwayat peminjaman</h4>
                        <p class="text-muted mb-4">Anda belum pernah mengajukan peminjaman alat</p>
                        <a href="index.php?page=peminjaman&action=create" class="btn btn-primary btn-lg">
                            <i class="fas fa-hand-holding me-2"></i>Ajukan Peminjaman Pertama
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Alat</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Keperluan</th>
                                    <th>Tanggal Pengajuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($data['riwayat'] as $peminjaman): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($peminjaman['nama_alat']); ?></strong>
                                        <?php if ($peminjaman['status'] == 'pending'): ?>
                                            <br><small class="text-warning"><i class="fas fa-clock me-1"></i>Menunggu approval admin</small>
                                        <?php elseif ($peminjaman['status'] == 'ditolak'): ?>
                                            <br><small class="text-danger"><i class="fas fa-times me-1"></i>Ditolak oleh admin</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($peminjaman['tanggal_pinjam'])); ?>
                                    </td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($peminjaman['tanggal_kembali'])); ?>
                                        <?php if ($peminjaman['status'] == 'dipinjam' && strtotime($peminjaman['tanggal_kembali']) < time()): ?>
                                            <br><small class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Melebihi batas</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo $peminjaman['jumlah']; ?> unit</span>
                                    </td>
                                    <td>
                                        <?php
                                        $status_config = [
                                            'pending' => [
                                                'class' => 'badge-pending',
                                                'text' => 'Menunggu Approval',
                                                'icon' => 'clock',
                                                'description' => 'Menunggu persetujuan admin'
                                            ],
                                            'approved' => [
                                                'class' => 'badge-approved', 
                                                'text' => 'Disetujui',
                                                'icon' => 'check',
                                                'description' => 'Peminjaman disetujui, siap diambil'
                                            ],
                                            'dipinjam' => [
                                                'class' => 'badge-dipinjam',
                                                'text' => 'Sedang Dipinjam',
                                                'icon' => 'hand-holding',
                                                'description' => 'Alat sedang dipinjam'
                                            ],
                                            'dikembalikan' => [
                                                'class' => 'badge-dikembalikan',
                                                'text' => 'Selesai',
                                                'icon' => 'check-double',
                                                'description' => 'Peminjaman telah selesai'
                                            ],
                                            'ditolak' => [
                                                'class' => 'badge-ditolak',
                                                'text' => 'Ditolak',
                                                'icon' => 'times',
                                                'description' => 'Peminjaman ditolak oleh admin'
                                            ]
                                        ];
                                        $status = $peminjaman['status'];
                                        ?>
                                        <span class="badge <?php echo $status_config[$status]['class']; ?> position-relative" 
                                              title="<?php echo $status_config[$status]['description']; ?>">
                                            <i class="fas fa-<?php echo $status_config[$status]['icon']; ?> me-1"></i>
                                            <?php echo $status_config[$status]['text']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                              title="<?php echo htmlspecialchars($peminjaman['keperluan']); ?>">
                                            <?php echo htmlspecialchars($peminjaman['keperluan']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('d M Y H:i', strtotime($peminjaman['created_at'])); ?>
                                        </small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Status Legend -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-muted mb-3"><i class="fas fa-info-circle me-2"></i>Keterangan Status:</h6>
                        <div class="row status-legend">
                            <div class="col-md-2 text-center mb-2">
                                <span class="badge badge-pending p-2 d-block">Menunggu Approval</span>
                                <small class="text-muted">Sedang ditinjau admin</small>
                            </div>
                            <div class="col-md-2 text-center mb-2">
                                <span class="badge badge-approved p-2 d-block">Disetujui</span>
                                <small class="text-muted">Siap untuk diambil</small>
                            </div>
                            <div class="col-md-2 text-center mb-2">
                                <span class="badge badge-dipinjam p-2 d-block">Sedang Dipinjam</span>
                                <small class="text-muted">Alat sedang digunakan</small>
                            </div>
                            <div class="col-md-2 text-center mb-2">
                                <span class="badge badge-dikembalikan p-2 d-block">Selesai</span>
                                <small class="text-muted">Sudah dikembalikan</small>
                            </div>
                            <div class="col-md-2 text-center mb-2">
                                <span class="badge badge-ditolak p-2 d-block">Ditolak</span>
                                <small class="text-muted">Tidak disetujui admin</small>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <i class="fas fa-question-circle fa-2x text-primary mb-3"></i>
                        <h5>Butuh Bantuan?</h5>
                        <p class="text-muted">Jika ada pertanyaan mengenai status peminjaman, hubungi admin lab.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope me-1"></i>Hubungi Admin
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <i class="fas fa-lightbulb fa-2x text-success mb-3"></i>
                        <h5>Tips Peminjaman</h5>
                        <p class="text-muted">Ajukan peminjaman minimal 1 hari sebelumnya untuk proses yang lancar.</p>
                        <a href="index.php?page=peminjaman&action=create" class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-1"></i>Ajukan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Tooltip for status badges
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Highlight overdue items
        function checkOverdue() {
            const rows = document.querySelectorAll('tbody tr');
            const now = new Date();
            
            rows.forEach(row => {
                const returnDateText = row.cells[3].textContent.trim();
                const statusBadge = row.cells[5].querySelector('.badge');
                
                if (statusBadge && statusBadge.textContent.includes('Sedang Dipinjam')) {
                    // Simple check for overdue (you might want more sophisticated date parsing)
                    if (returnDateText && new Date(returnDateText) < now) {
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