<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Alat - Lab PPLG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <?php include __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-tools"></i> Data Alat Lab</h2>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="index.php?page=alat&action=create" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Tambah Alat
                </a>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Deskripsi</th>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['alat'])): ?>
                        <tr>
                            <td colspan="<?php echo $_SESSION['role'] == 'admin' ? '6' : '5'; ?>" class="text-center text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                Belum ada data alat
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; ?>
                        <?php foreach ($data['alat'] as $alat): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($alat['nama']); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo htmlspecialchars($alat['kategori']); ?></span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $alat['jumlah'] > 0 ? 'success' : 'danger'; ?>">
                                    <?php echo $alat['jumlah']; ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($alat['deskripsi']); ?></td>
                            <?php if ($_SESSION['role'] == 'admin'): ?>
                                <td>
                                    <a href="index.php?page=alat&action=edit&id=<?php echo $alat['id']; ?>" 
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="index.php?page=alat&action=delete&id=<?php echo $alat['id']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Yakin ingin menghapus alat ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>