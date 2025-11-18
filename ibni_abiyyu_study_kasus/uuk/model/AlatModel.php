<?php
require_once 'config/database.php';

class AlatModel {
    private $conn;
    private $table_name = "alat";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllAlat() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAlatById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAlatTersedia() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE jumlah > 0 ORDER BY nama";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createAlat($nama, $kategori, $jumlah, $deskripsi) {
        $query = "INSERT INTO " . $this->table_name . " SET nama=:nama, kategori=:kategori, jumlah=:jumlah, deskripsi=:deskripsi";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':kategori', $kategori);
        $stmt->bindParam(':jumlah', $jumlah);
        $stmt->bindParam(':deskripsi', $deskripsi);

        return $stmt->execute();
    }

    public function updateAlat($id, $nama, $kategori, $jumlah, $deskripsi) {
        $query = "UPDATE " . $this->table_name . " SET nama=:nama, kategori=:kategori, jumlah=:jumlah, deskripsi=:deskripsi WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':kategori', $kategori);
        $stmt->bindParam(':jumlah', $jumlah);
        $stmt->bindParam(':deskripsi', $deskripsi);

        return $stmt->execute();
    }

    public function deleteAlat($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function getTotalAlat() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function updateStok($id, $jumlah) {
        $query = "UPDATE " . $this->table_name . " SET jumlah = jumlah - :jumlah WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':jumlah', $jumlah);

        return $stmt->execute();
    }
    // Tambahkan di AlatModel.php
public function returnStok($id, $jumlah) {
    $query = "UPDATE " . $this->table_name . " SET jumlah = jumlah + :jumlah WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':jumlah', $jumlah);

    return $stmt->execute();
}
}
?>