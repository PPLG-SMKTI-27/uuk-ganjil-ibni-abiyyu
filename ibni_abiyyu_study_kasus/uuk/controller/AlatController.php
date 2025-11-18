<?php
require_once 'model/AlatModel.php';

class AlatController {
    private $alatModel;

    public function __construct() {
        $this->alatModel = new AlatModel();
    }

    public function index() {
        if (!isset($_SESSION['role'])) {
            header('Location: index.php?page=auth&action=login');
            return;
        }

        $data['alat'] = $this->alatModel->getAllAlat();
        require_once 'view/alat/index.php'; // ← PERUBAHAN DI SINI
    }

    public function create() {
        if ($_SESSION['role'] != 'admin') {
            header('Location: index.php?page=dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama = $_POST['nama'];
            $kategori = $_POST['kategori'];
            $jumlah = $_POST['jumlah'];
            $deskripsi = $_POST['deskripsi'];

            if ($this->alatModel->createAlat($nama, $kategori, $jumlah, $deskripsi)) {
                header('Location: index.php?page=alat');
            } else {
                $error = "Gagal menambah alat!";
                require_once 'view/alat/create.php'; // ← PERUBAHAN DI SINI
            }
        } else {
            require_once 'view/alat/create.php'; // ← PERUBAHAN DI SINI
        }
    }

    public function edit() {
        if ($_SESSION['role'] != 'admin') {
            header('Location: index.php?page=dashboard');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $nama = $_POST['nama'];
            $kategori = $_POST['kategori'];
            $jumlah = $_POST['jumlah'];
            $deskripsi = $_POST['deskripsi'];

            if ($this->alatModel->updateAlat($id, $nama, $kategori, $jumlah, $deskripsi)) {
                header('Location: index.php?page=alat');
            } else {
                $error = "Gagal mengupdate alat!";
                $data['alat'] = $this->alatModel->getAlatById($id);
                require_once 'view/alat/edit.php'; // ← PERUBAHAN DI SINI
            }
        } else {
            $id = $_GET['id'];
            $data['alat'] = $this->alatModel->getAlatById($id);
            require_once 'view/alat/edit.php'; // ← PERUBAHAN DI SINI
        }
    }

    public function delete() {
        if ($_SESSION['role'] != 'admin') {
            header('Location: index.php?page=dashboard');
            return;
        }

        $id = $_GET['id'];
        if ($this->alatModel->deleteAlat($id)) {
            header('Location: index.php?page=alat');
        }
    }
}
?>