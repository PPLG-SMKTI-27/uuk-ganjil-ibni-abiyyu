<?php
require_once 'model/AkunModel.php';

class AuthController {
    private $akunModel;

    public function __construct() {
        $this->akunModel = new AkunModel();
    }

    public function login() {
        // Jika user sudah login, redirect ke dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?page=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            // Validasi input
            if (empty($username) || empty($password)) {
                $error = "Username dan password harus diisi!";
                require_once 'view/auth/login.php';
                return;
            }

            // Coba login
            $user = $this->akunModel->login($username, $password);
            
            if ($user) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['login_time'] = time();
                
                // Set session flash message
                $_SESSION['success'] = "Login berhasil! Selamat datang " . $user['nama'];
                
                // Redirect berdasarkan role
                header('Location: index.php?page=dashboard');
                exit;
            } else {
                $error = "Username atau password salah!";
                require_once 'view/auth/login.php';
            }
        } else {
            // Tampilkan form login
            require_once 'view/auth/login.php';
        }
    }

    public function register() {
        // Jika user sudah login, redirect ke dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?page=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama = trim($_POST['nama']);
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $role = $_POST['role'];

            // Validasi input
            $errors = [];

            if (empty($nama)) {
                $errors[] = "Nama lengkap harus diisi!";
            }

            if (empty($username)) {
                $errors[] = "Username harus diisi!";
            } elseif (strlen($username) < 3) {
                $errors[] = "Username minimal 3 karakter!";
            }

            if (empty($password)) {
                $errors[] = "Password harus diisi!";
            } elseif (strlen($password) < 6) {
                $errors[] = "Password minimal 6 karakter!";
            }

            if ($password !== $confirm_password) {
                $errors[] = "Konfirmasi password tidak sesuai!";
            }

            if (empty($role) || !in_array($role, ['siswa', 'guru'])) {
                $errors[] = "Role harus dipilih!";
            }

            // Cek apakah username sudah ada
            if ($this->akunModel->isUsernameExists($username)) {
                $errors[] = "Username sudah digunakan!";
            }

            // Jika ada error, tampilkan kembali form
            if (!empty($errors)) {
                $error = implode("<br>", $errors);
                require_once 'view/auth/register.php';
                return;
            }

            // Coba register
            if ($this->akunModel->register($nama, $username, $password, $role)) {
                $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
                header('Location: index.php?page=auth&action=login');
                exit;
            } else {
                $error = "Gagal melakukan registrasi! Silakan coba lagi.";
                require_once 'view/auth/register.php';
            }
        } else {
            // Tampilkan form register
            require_once 'view/auth/register.php';
        }
    }

    public function logout() {
        // Hapus semua session
        session_unset();
        session_destroy();
        
        // Redirect ke halaman login
        header('Location: index.php?page=auth&action=login');
        exit;
    }

    public function profile() {
        // Cek apakah user sudah login
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user_id'];
            $nama = trim($_POST['nama']);
            $username = trim($_POST['username']);
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            // Validasi input
            $errors = [];

            if (empty($nama)) {
                $errors[] = "Nama lengkap harus diisi!";
            }

            if (empty($username)) {
                $errors[] = "Username harus diisi!";
            }

            // Cek apakah username sudah ada (kecuali untuk user ini)
            $current_user = $this->akunModel->getUserById($user_id);
            if ($username !== $current_user['username'] && $this->akunModel->isUsernameExists($username)) {
                $errors[] = "Username sudah digunakan!";
            }

            // Jika ingin ganti password
            if (!empty($new_password)) {
                if (empty($current_password)) {
                    $errors[] = "Password saat ini harus diisi untuk mengganti password!";
                } elseif (!$this->akunModel->verifyPassword($user_id, $current_password)) {
                    $errors[] = "Password saat ini salah!";
                } elseif (strlen($new_password) < 6) {
                    $errors[] = "Password baru minimal 6 karakter!";
                } elseif ($new_password !== $confirm_password) {
                    $errors[] = "Konfirmasi password baru tidak sesuai!";
                }
            }

            if (!empty($errors)) {
                $error = implode("<br>", $errors);
                $data['user'] = $this->akunModel->getUserById($user_id);
                require_once 'view/auth/profile.php';
                return;
            }

            // Update profile
            if ($this->akunModel->updateProfile($user_id, $nama, $username, $new_password)) {
                $_SESSION['success'] = "Profile berhasil diupdate!";
                $_SESSION['nama'] = $nama;
                $_SESSION['username'] = $username;
                header('Location: index.php?page=auth&action=profile');
                exit;
            } else {
                $error = "Gagal mengupdate profile!";
                $data['user'] = $this->akunModel->getUserById($user_id);
                require_once 'view/auth/profile.php';
            }
        } else {
            // Tampilkan form profile
            $data['user'] = $this->akunModel->getUserById($_SESSION['user_id']);
            require_once 'view/auth/profile.php';
        }
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username']);
            
            if (empty($username)) {
                $error = "Username harus diisi!";
                require_once 'view/auth/forgot_password.php';
                return;
            }

            // Cek apakah username exists
            $user = $this->akunModel->getUserByUsername($username);
            if ($user) {
                // Di sini bisa implementasi reset password via email
                // Untuk sementara, kita tampilkan pesan sukses
                $_SESSION['success'] = "Instruksi reset password telah dikirim ke email yang terdaftar.";
                header('Location: index.php?page=auth&action=login');
                exit;
            } else {
                $error = "Username tidak ditemukan!";
                require_once 'view/auth/forgot_password.php';
            }
        } else {
            require_once 'view/auth/forgot_password.php';
        }
    }
}
?>