<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Pending - Lab PPLG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-clock me-2"></i>Peminjaman Menunggu Approval</h2>
            <span class="badge bg-warning fs-6"><?php echo $data['pending_count']; ?> Pending</span>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <?php if (empty($data['peminjaman'])): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>Tidak ada peminjaman yang menunggu approval</h5>
                        <p class="text-muted">Semua peminjaman sudah diproses</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Peminjam</th>
                                    <th>Alat</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Jumlah</th>
                                    <th>Keperluan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['peminjaman'] as $p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p['nama_peminjam']); ?></td>
                                    <td><?php echo htmlspecialchars($p['nama_alat']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($p['tanggal_pinjam'])); ?></td>
                                    <td><?php echo date('d M Y', strtotime($p['tanggal_kembali'])); ?></td>
                                    <td><?php echo $p['jumlah']; ?></td>
                                    <td><?php echo htmlspecialchars($p['keperluan']); ?></td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="index.php?page=peminjaman&action=approve&id=<?php echo $p['id']; ?>" 
                                               class="btn btn-success btn-sm" 
                                               onclick="return confirm('Setujui peminjaman ini?')">
                                                <i class="fas fa-check"></i> Approve
                                            </a>
                                            <a href="index.php?page=peminjaman&action=reject&id=<?php echo $p['id']; ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Tolak peminjaman ini?')">
                                                <i class="fas fa-times"></i> Reject
                                            </a>
                                        </div>
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
</body>
</html>