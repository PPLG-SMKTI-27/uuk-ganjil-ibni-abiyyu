<?php
require_once 'config/database.php';

class AkunModel {
    private $conn;
    private $table_name = "akun";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username AND password = :password";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', md5($password));
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function register($nama, $username, $password, $role) {
        $query = "INSERT INTO " . $this->table_name . " SET nama=:nama, username=:username, password=:password, role=:role";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', md5($password));
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tambahkan method-method berikut di class AkunModel

public function isUsernameExists($username) {
    $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    return $stmt->rowCount() > 0;
}

public function getUserByUsername($username) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return false;
}

public function verifyPassword($user_id, $password) {
    $query = "SELECT password FROM " . $this->table_name . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user['password'] === md5($password);
    }
    return false;
}

public function updateProfile($user_id, $nama, $username, $new_password = null) {
    if ($new_password) {
        $query = "UPDATE " . $this->table_name . " SET nama=:nama, username=:username, password=:password WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', md5($new_password));
    } else {
        $query = "UPDATE " . $this->table_name . " SET nama=:nama, username=:username WHERE id=:id";
        $stmt = $this->conn->prepare($query);
    }

    $stmt->bindParam(':id', $user_id);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':username', $username);

    return $stmt->execute();
}
}
?>