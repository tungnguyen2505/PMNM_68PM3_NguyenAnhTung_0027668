<?php
require_once '../app/core/connectDB.php';

class sinhvienModel {
    private $conn;
    
    public function __construct() {
        $this->conn = connectDB::Connect();
    }   
    
    public function getAllSinhVien() {
        $sql = "SELECT * FROM tbl_sinhvien";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>