<?php
require_once 'config/database.php';

class PeminjamanModel {
    private $conn;
    private $table_name = "peminjaman";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllPeminjaman() {
        $query = "SELECT p.*, a.nama as nama_alat, u.nama as nama_peminjam 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN alat a ON p.alat_id = a.id 
                  LEFT JOIN akun u ON p.user_id = u.id 
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPeminjamanPending() {
        $query = "SELECT p.*, a.nama as nama_alat, u.nama as nama_peminjam 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN alat a ON p.alat_id = a.id 
                  LEFT JOIN akun u ON p.user_id = u.id 
                  WHERE p.status = 'pending'
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPeminjamanAktif() {
        $query = "SELECT p.*, a.nama as nama_alat, u.nama as nama_peminjam 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN alat a ON p.alat_id = a.id 
                  LEFT JOIN akun u ON p.user_id = u.id 
                  WHERE p.status IN ('approved', 'dipinjam')
                  ORDER BY p.tanggal_pinjam DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRiwayatByUser($user_id) {
        $query = "SELECT p.*, a.nama as nama_alat 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN alat a ON p.alat_id = a.id 
                  WHERE p.user_id = :user_id 
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createPeminjaman($user_id, $alat_id, $tanggal_pinjam, $tanggal_kembali, $jumlah, $keperluan) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET user_id=:user_id, alat_id=:alat_id, tanggal_pinjam=:tanggal_pinjam, 
                  tanggal_kembali=:tanggal_kembali, jumlah=:jumlah, keperluan=:keperluan, 
                  status='pending'"; // Status default: pending
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':alat_id', $alat_id);
        $stmt->bindParam(':tanggal_pinjam', $tanggal_pinjam);
        $stmt->bindParam(':tanggal_kembali', $tanggal_kembali);
        $stmt->bindParam(':jumlah', $jumlah);
        $stmt->bindParam(':keperluan', $keperluan);

        return $stmt->execute();
        // Tidak update stok langsung, tunggu approval admin
    }

    public function approvePeminjaman($id) {
        // Dapatkan data peminjaman
        $peminjaman = $this->getPeminjamanById($id);
        
        if (!$peminjaman) return false;

        // Update status menjadi approved
        $query = "UPDATE " . $this->table_name . " SET status = 'approved' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            // Update stok alat
            require_once 'AlatModel.php';
            $alatModel = new AlatModel();
            return $alatModel->updateStok($peminjaman['alat_id'], $peminjaman['jumlah']);
        }
        return false;
    }

    public function rejectPeminjaman($id) {
        $query = "UPDATE " . $this->table_name . " SET status = 'ditolak' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function startPeminjaman($id) {
        $query = "UPDATE " . $this->table_name . " SET status = 'dipinjam' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function completePeminjaman($id) {
        // Dapatkan data peminjaman
        $peminjaman = $this->getPeminjamanById($id);
        
        if (!$peminjaman) return false;

        // Update status menjadi dikembalikan
        $query = "UPDATE " . $this->table_name . " SET status = 'dikembalikan' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            // Kembalikan stok alat
            require_once 'AlatModel.php';
            $alatModel = new AlatModel();
            return $alatModel->returnStok($peminjaman['alat_id'], $peminjaman['jumlah']);
        }
        return false;
    }

    public function getPeminjamanById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTotalPeminjaman() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getPeminjamanPendingCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    // Tambahkan method-method ini di PeminjamanModel
public function getPeminjamanCountByStatus($status) {
    $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE status = :status";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':status', $status);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

public function getUserPeminjamanCount($user_id) {
    $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE user_id = :user_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

public function getUserPeminjamanCountByStatus($user_id, $status) {
    $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE user_id = :user_id AND status = :status";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':status', $status);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

public function cancelPeminjaman($id) {
    $query = "UPDATE " . $this->table_name . " SET status = 'dibatalkan' WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    
    return $stmt->execute();
}
}
?>