<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat - Lab PPLG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <?php include __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Alat</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $data['alat']['id']; ?>">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Alat</label>
                                <input type="text" class="form-control" id="nama" name="nama" 
                                       value="<?php echo htmlspecialchars($data['alat']['nama']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select class="form-control" id="kategori" name="kategori" required>
                                    <option value="Elektronik" <?php echo $data['alat']['kategori'] == 'Elektronik' ? 'selected' : ''; ?>>Elektronik</option>
                                    <option value="Jaringan" <?php echo $data['alat']['kategori'] == 'Jaringan' ? 'selected' : ''; ?>>Jaringan</option>
                                    <option value="Perkakas" <?php echo $data['alat']['kategori'] == 'Perkakas' ? 'selected' : ''; ?>>Perkakas</option>
                                    <option value="Software" <?php echo $data['alat']['kategori'] == 'Software' ? 'selected' : ''; ?>>Software</option>
                                    <option value="Lainnya" <?php echo $data['alat']['kategori'] == 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                       value="<?php echo $data['alat']['jumlah']; ?>" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo htmlspecialchars($data['alat']['deskripsi']); ?></textarea>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Update Alat
                                </button>
                                <a href="index.php?page=alat" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>