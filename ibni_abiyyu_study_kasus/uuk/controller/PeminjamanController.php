<?php
require_once 'model/PeminjamanModel.php';
require_once 'model/AlatModel.php';

class PeminjamanController {
    private $peminjamanModel;
    private $alatModel;

    public function __construct() {
        $this->peminjamanModel = new PeminjamanModel();
        $this->alatModel = new AlatModel();
    }

    public function index() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?page=dashboard');
            return;
        }

        $data['peminjaman'] = $this->peminjamanModel->getAllPeminjaman();
        $data['pending_count'] = $this->peminjamanModel->getPeminjamanPendingCount();
        $data['stats'] = $this->getPeminjamanStats(); // Tambahkan stats
        require_once 'view/peminjaman/index.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth&action=login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $alat_id = $_POST['alat_id'];
            $tanggal_pinjam = $_POST['tanggal_pinjam'];
            $tanggal_kembali = $_POST['tanggal_kembali'];
            $jumlah = $_POST['jumlah'];
            $keperluan = trim($_POST['keperluan']);

            // Validasi input
            $errors = $this->validatePeminjaman($alat_id, $tanggal_pinjam, $tanggal_kembali, $jumlah, $keperluan);
            
            if (!empty($errors)) {
                $error = implode("<br>", $errors);
                $data['alat'] = $this->alatModel->getAlatTersedia();
                require_once 'view/peminjaman/create.php';
                return;
            }

            // Validasi jumlah tidak melebihi stok
            $alat = $this->alatModel->getAlatById($alat_id);
            if ($alat['jumlah'] < $jumlah) {
                $error = "Jumlah peminjaman melebihi stok yang tersedia! Stok tersedia: " . $alat['jumlah'];
                $data['alat'] = $this->alatModel->getAlatTersedia();
                require_once 'view/peminjaman/create.php';
                return;
            }

            // Validasi tanggal
            if (strtotime($tanggal_pinjam) > strtotime($tanggal_kembali)) {
                $error = "Tanggal kembali harus setelah tanggal pinjam!";
                $data['alat'] = $this->alatModel->getAlatTersedia();
                require_once 'view/peminjaman/create.php';
                return;
            }

            if (strtotime($tanggal_pinjam) < strtotime(date('Y-m-d'))) {
                $error = "Tanggal pinjam tidak boleh kurang dari hari ini!";
                $data['alat'] = $this->alatModel->getAlatTersedia();
                require_once 'view/peminjaman/create.php';
                return;
            }

            if ($this->peminjamanModel->createPeminjaman($_SESSION['user_id'], $alat_id, $tanggal_pinjam, $tanggal_kembali, $jumlah, $keperluan)) {
                $_SESSION['success'] = "Peminjaman berhasil diajukan! Menunggu persetujuan admin.";
                header('Location: index.php?page=peminjaman&action=riwayat');
                exit;
            } else {
                $error = "Gagal mengajukan peminjaman! Silakan coba lagi.";
                $data['alat'] = $this->alatModel->getAlatTersedia();
                require_once 'view/peminjaman/create.php';
            }
        } else {
            $data['alat'] = $this->alatModel->getAlatTersedia();
            require_once 'view/peminjaman/create.php';
        }
    }

    public function riwayat() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth&action=login');
            return;
        }

        $data['riwayat'] = $this->peminjamanModel->getRiwayatByUser($_SESSION['user_id']);
        $data['stats_user'] = $this->getUserPeminjamanStats($_SESSION['user_id']);
        require_once 'view/peminjaman/riwayat.php';
    }

    // ADMIN ACTIONS
    public function approve() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?page=dashboard');
            return;
        }

        $id = $_GET['id'];
        if ($this->peminjamanModel->approvePeminjaman($id)) {
            $_SESSION['success'] = "Peminjaman berhasil disetujui!";
        } else {
            $_SESSION['error'] = "Gagal menyetujui peminjaman!";
        }
        header('Location: index.php?page=peminjaman');
        exit;
    }

    public function reject() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?page=dashboard');
            return;
        }

        $id = $_GET['id'];
        $alasan = isset($_POST['alasan']) ? trim($_POST['alasan']) : '';
        
        if ($this->peminjamanModel->rejectPeminjaman($id, $alasan)) {
            $_SESSION['success'] = "Peminjaman berhasil ditolak!" . ($alasan ? " Alasan: " . $alasan : "");
        } else {
            $_SESSION['error'] = "Gagal menolak peminjaman!";
        }
        header('Location: index.php?page=peminjaman');
        exit;
    }

    public function start() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?page=dashboard');
            return;
        }

        $id = $_GET['id'];
        if ($this->peminjamanModel->startPeminjaman($id)) {
            $_SESSION['success'] = "Peminjaman berhasil dimulai! Alat sudah bisa diambil.";
        } else {
            $_SESSION['error'] = "Gagal memulai peminjaman!";
        }
        header('Location: index.php?page=peminjaman');
        exit;
    }

    public function complete() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?page=dashboard');
            return;
        }

        $id = $_GET['id'];
        $kondisi = isset($_POST['kondisi']) ? trim($_POST['kondisi']) : 'baik';
        
        if ($this->peminjamanModel->completePeminjaman($id, $kondisi)) {
            $_SESSION['success'] = "Peminjaman selesai! Alat telah dikembalikan." . ($kondisi != 'baik' ? " Kondisi: " . $kondisi : "");
        } else {
            $_SESSION['error'] = "Gagal menyelesaikan peminjaman!";
        }
        header('Location: index.php?page=peminjaman');
        exit;
    }

    public function pending() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?page=dashboard');
            return;
        }

        $data['peminjaman'] = $this->peminjamanModel->getPeminjamanPending();
        $data['pending_count'] = $this->peminjamanModel->getPeminjamanPendingCount();
        require_once 'view/peminjaman/pending.php';
    }

    public function detail() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth&action=login');
            return;
        }

        $id = $_GET['id'];
        $peminjaman = $this->peminjamanModel->getPeminjamanById($id);
        
        // Cek apakah user berhak melihat detail
        if ($_SESSION['role'] != 'admin' && $peminjaman['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Anda tidak berhak mengakses detail peminjaman ini!";
            header('Location: index.php?page=peminjaman&action=riwayat');
            return;
        }

        $data['peminjaman'] = $peminjaman;
        $data['alat'] = $this->alatModel->getAlatById($peminjaman['alat_id']);
        require_once 'view/peminjaman/detail.php';
    }

    public function cancel() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=auth&action=login');
            return;
        }

        $id = $_GET['id'];
        $peminjaman = $this->peminjamanModel->getPeminjamanById($id);
        
        // Cek apakah user berhak membatalkan
        if ($peminjaman['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Anda tidak berhak membatalkan peminjaman ini!";
            header('Location: index.php?page=peminjaman&action=riwayat');
            return;
        }

        // Hanya bisa membatalkan yang statusnya pending
        if ($peminjaman['status'] != 'pending') {
            $_SESSION['error'] = "Hanya peminjaman dengan status pending yang bisa dibatalkan!";
            header('Location: index.php?page=peminjaman&action=riwayat');
            return;
        }

        if ($this->peminjamanModel->cancelPeminjaman($id)) {
            $_SESSION['success'] = "Peminjaman berhasil dibatalkan!";
        } else {
            $_SESSION['error'] = "Gagal membatalkan peminjaman!";
        }
        header('Location: index.php?page=peminjaman&action=riwayat');
        exit;
    }

    // HELPER METHODS
    private function validatePeminjaman($alat_id, $tanggal_pinjam, $tanggal_kembali, $jumlah, $keperluan) {
        $errors = [];

        if (empty($alat_id)) {
            $errors[] = "Pilih alat yang akan dipinjam!";
        }

        if (empty($tanggal_pinjam)) {
            $errors[] = "Tanggal pinjam harus diisi!";
        }

        if (empty($tanggal_kembali)) {
            $errors[] = "Tanggal kembali harus diisi!";
        }

        if (empty($jumlah) || $jumlah < 1) {
            $errors[] = "Jumlah harus lebih dari 0!";
        }

        if (empty($keperluan)) {
            $errors[] = "Keperluan peminjaman harus diisi!";
        }

        if (strlen($keperluan) < 10) {
            $errors[] = "Keperluan peminjaman minimal 10 karakter!";
        }

        return $errors;
    }

    private function getPeminjamanStats() {
        $stats = [
            'total' => $this->peminjamanModel->getTotalPeminjaman(),
            'pending' => $this->peminjamanModel->getPeminjamanPendingCount(),
            'approved' => $this->peminjamanModel->getPeminjamanCountByStatus('approved'),
            'dipinjam' => $this->peminjamanModel->getPeminjamanCountByStatus('dipinjam'),
            'dikembalikan' => $this->peminjamanModel->getPeminjamanCountByStatus('dikembalikan'),
            'ditolak' => $this->peminjamanModel->getPeminjamanCountByStatus('ditolak')
        ];
        
        return $stats;
    }

    private function getUserPeminjamanStats($user_id) {
        $stats = [
            'total' => $this->peminjamanModel->getUserPeminjamanCount($user_id),
            'pending' => $this->peminjamanModel->getUserPeminjamanCountByStatus($user_id, 'pending'),
            'approved' => $this->peminjamanModel->getUserPeminjamanCountByStatus($user_id, 'approved'),
            'dipinjam' => $this->peminjamanModel->getUserPeminjamanCountByStatus($user_id, 'dipinjam'),
            'dikembalikan' => $this->peminjamanModel->getUserPeminjamanCountByStatus($user_id, 'dikembalikan'),
            'ditolak' => $this->peminjamanModel->getUserPeminjamanCountByStatus($user_id, 'ditolak')
        ];
        
        return $stats;
    }

    // API untuk mendapatkan data peminjaman (optional)
    public function api() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Access denied']);
            return;
        }

        $action = $_GET['action'] ?? 'stats';
        
        switch ($action) {
            case 'stats':
                echo json_encode($this->getPeminjamanStats());
                break;
            case 'pending':
                echo json_encode($this->peminjamanModel->getPeminjamanPending());
                break;
            default:
                http_response_code(400);
                echo json_encode(['error' => 'Invalid action']);
        }
    }
}
?>