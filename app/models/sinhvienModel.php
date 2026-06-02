<?php
require_once __DIR__ . '/../core/connectDB.php';

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
    public function create($hoten, $gioitinh, $mssv) {
        $sql = "INSERT INTO tbl_sinhvien (hoten, gioitinh, mssv) VALUES (:hoten, :gioitinh, :mssv)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':hoten', $hoten);
        $stmt->bindParam(':gioitinh', $gioitinh);
        $stmt->bindParam(':mssv', $mssv);
        return $stmt->execute();
  
    }
}
?>