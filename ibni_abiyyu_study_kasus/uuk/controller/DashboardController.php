<?php
require_once 'model/AlatModel.php';
require_once 'model/PeminjamanModel.php';

class DashboardController {
    private $alatModel;
    private $peminjamanModel;

    public function __construct() {
        $this->alatModel = new AlatModel();
        $this->peminjamanModel = new PeminjamanModel();
    }

    public function admin() {
        // Pastikan return value adalah angka, bukan array
        $data['total_alat'] = $this->alatModel->getTotalAlat();
        $data['total_peminjaman'] = $this->peminjamanModel->getTotalPeminjaman();
        
        // Perbaikan: getPeminjamanAktif() harus return count, bukan array
        $peminjaman_aktif = $this->peminjamanModel->getPeminjamanAktif();
        $data['peminjaman_aktif'] = is_array($peminjaman_aktif) ? count($peminjaman_aktif) : $peminjaman_aktif;
        
        $data['pending_count'] = $this->peminjamanModel->getPeminjamanPendingCount();
        
        require_once 'view/dashboard/admin.php';
    }

    public function guru() {
        $data['alat_tersedia'] = $this->alatModel->getAlatTersedia();
        $data['riwayat_peminjaman'] = $this->peminjamanModel->getRiwayatByUser($_SESSION['user_id']);
        
        require_once 'view/dashboard/guru.php';
    }

    public function siswa() {
        $data['alat_tersedia'] = $this->alatModel->getAlatTersedia();
        $data['riwayat_peminjaman'] = $this->peminjamanModel->getRiwayatByUser($_SESSION['user_id']);
        
        require_once 'view/dashboard/siswa.php';
    }
}
?>