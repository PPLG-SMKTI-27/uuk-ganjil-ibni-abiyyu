<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Lab PPLG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .register-container {
            max-width: 450px;
            margin: 40px auto;
            padding: 30px;
            border: 1px solid #dee2e6;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background: #fff;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
        }
        .btn-register {
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 5px;
            transition: all 0.3s ease;
        }
        .password-match {
            font-size: 0.875rem;
            margin-top: 5px;
        }
        .header-icon {
            font-size: 3rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include __DIR__ . '/../partials/navbar.php'; ?>
    
    <div class="container">
        <div class="register-container">
            <!-- Header -->
            <div class="text-center mb-4">
                <div class="header-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2 class="fw-bold text-primary">Daftar Akun Baru</h2>
                <p class="text-muted">Bergabung dengan sistem Lab PPLG</p>
            </div>
            
            <!-- Error Message -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div><?php echo $error; ?></div>
                </div>
            <?php endif; ?>

            <!-- Success Message -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <div><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                </div>
            <?php endif; ?>

            <!-- Register Form -->
            <form method="POST" id="registerForm">
                <!-- Nama Lengkap -->
                <div class="mb-3">
                    <label for="nama" class="form-label">
                        <i class="fas fa-user me-1"></i>Nama Lengkap
                    </label>
                    <input type="text" class="form-control" id="nama" name="nama" 
                           value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>" 
                           required placeholder="Masukkan nama lengkap Anda">
                </div>

                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">
                        <i class="fas fa-at me-1"></i>Username
                    </label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                           required placeholder="Pilih username unik">
                    <div class="form-text text-muted">
                        <small>Username minimal 3 karakter, hanya boleh huruf, angka, dan underscore</small>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-1"></i>Password
                    </label>
                    <input type="password" class="form-control" id="password" name="password" 
                           required placeholder="Buat password yang kuat">
                    <div class="password-strength" id="passwordStrength"></div>
                    <div class="form-text text-muted">
                        <small>Password minimal 6 karakter, disarankan kombinasi huruf dan angka</small>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="confirm_password" class="form-label">
                        <i class="fas fa-lock me-1"></i>Konfirmasi Password
                    </label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                           required placeholder="Ulangi password Anda">
                    <div class="password-match" id="passwordMatch"></div>
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label for="role" class="form-label">
                        <i class="fas fa-user-tag me-1"></i>Peran (Role)
                    </label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="">-- Pilih Peran --</option>
                        <option value="siswa" <?php echo (isset($_POST['role']) && $_POST['role'] == 'siswa') ? 'selected' : ''; ?>>
                            👨‍🎓 Siswa
                        </option>
                        <option value="guru" <?php echo (isset($_POST['role']) && $_POST['role'] == 'guru') ? 'selected' : ''; ?>>
                            👨‍🏫 Guru
                        </option>
                    </select>
                    <div class="form-text text-muted">
                        <small>Pilih peran sesuai dengan status Anda</small>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 btn-register mb-3">
                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                </button>

                <!-- Terms Notice -->
                <div class="text-center">
                    <small class="text-muted">
                        Dengan mendaftar, Anda menyetujui 
                        <a href="#" class="text-decoration-none">syarat dan ketentuan</a> 
                        yang berlaku
                    </small>
                </div>
            </form>
            
            <!-- Login Link -->
            <div class="text-center mt-4 pt-3 border-top">
                <p class="mb-0 text-muted">
                    Sudah punya akun?
                    <a href="index.php?page=auth&action=login" class="text-decoration-none fw-bold">
                        <i class="fas fa-sign-in-alt me-1"></i>Login di sini
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Password Strength Indicator
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('passwordStrength');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const passwordMatch = document.getElementById('passwordMatch');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // Length check
            if (password.length >= 6) strength += 25;
            if (password.length >= 8) strength += 15;
            
            // Character variety checks
            if (password.match(/[a-z]/)) strength += 20;
            if (password.match(/[A-Z]/)) strength += 20;
            if (password.match(/[0-9]/)) strength += 20;
            if (password.match(/[^a-zA-Z0-9]/)) strength += 20;
            
            // Update strength bar
            strengthBar.style.width = Math.min(strength, 100) + '%';
            
            // Update color based on strength
            if (strength < 40) {
                strengthBar.style.backgroundColor = '#dc3545';
                strengthBar.title = 'Password lemah';
            } else if (strength < 70) {
                strengthBar.style.backgroundColor = '#ffc107';
                strengthBar.title = 'Password cukup';
            } else {
                strengthBar.style.backgroundColor = '#198754';
                strengthBar.title = 'Password kuat';
            }
        });

        // Password Match Validation
        confirmPasswordInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const confirmPassword = this.value;
            
            if (confirmPassword === '') {
                passwordMatch.innerHTML = '';
                passwordMatch.className = 'password-match';
                this.style.borderColor = '#e9ecef';
            } else if (password === confirmPassword) {
                passwordMatch.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>Password cocok';
                passwordMatch.className = 'password-match text-success fw-bold';
                this.style.borderColor = '#198754';
            } else {
                passwordMatch.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i>Password tidak cocok';
                passwordMatch.className = 'password-match text-danger';
                this.style.borderColor = '#dc3545';
            }
        });

        // Form Validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const username = document.getElementById('username').value;
            
            let errors = [];
            
            // Password validation
            if (password.length < 6) {
                errors.push('Password minimal 6 karakter');
            }
            
            if (password !== confirmPassword) {
                errors.push('Password dan konfirmasi password tidak cocok');
            }
            
            // Username validation
            if (username.length < 3) {
                errors.push('Username minimal 3 karakter');
            }
            
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                errors.push('Username hanya boleh mengandung huruf, angka, dan underscore');
            }
            
            // Show errors if any
            if (errors.length > 0) {
                e.preventDefault();
                alert('Terjadi kesalahan:\n\n• ' + errors.join('\n• '));
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mendaftarkan...';
            submitBtn.disabled = true;
        });

        // Real-time username validation
        document.getElementById('username').addEventListener('input', function() {
            const username = this.value;
            if (username.length > 0 && username.length < 3) {
                this.style.borderColor = '#dc3545';
            } else if (username.length >= 3) {
                this.style.borderColor = '#198754';
            } else {
                this.style.borderColor = '#e9ecef';
            }
        });
    </script>
</body>
</html>