<?php
require_once __DIR__ . '/../core/connectDB.php';

class sinhvienModel {
    private $conn;
    
    public function __construct() {
        $this->conn = connectDB::Connect();
    }   
    
    public function getAllSinhVien() {
        $sql = "SELECT sv.*, lh.tenlop FROM tbl_sinhvien sv LEFT JOIN lophoc lh ON sv.lophoc_id = lh.id";
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
    public function getById($id) {
        $sql = "SELECT * FROM tbl_sinhvien WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $hoten, $gioitinh, $mssv, $lophoc_id = null) {
        if ($lophoc_id) {
            $sql = "UPDATE tbl_sinhvien SET hoten = :hoten, gioitinh = :gioitinh, mssv = :mssv, lophoc_id = :lophoc_id WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':lophoc_id', $lophoc_id);
        } else {
            $sql = "UPDATE tbl_sinhvien SET hoten = :hoten, gioitinh = :gioitinh, mssv = :mssv WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
        }
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':hoten', $hoten);
        $stmt->bindParam(':gioitinh', $gioitinh);
        $stmt->bindParam(':mssv', $mssv);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM tbl_sinhvien WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function paging($limit = 5, $page = 1) {
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);

        // Tính tổng số bản ghi
        $selectAllQuery = $this->conn->prepare("SELECT COUNT(*) as total FROM tbl_sinhvien");
        $selectAllQuery->execute();
        $row = $selectAllQuery->fetch(PDO::FETCH_ASSOC);
        $totalRecords = $row ? (int)$row['total'] : 0;
        $totalPages = $totalRecords > 0 ? (int)ceil($totalRecords / $limit) : 1;
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $limit;

        // Lấy danh sách sinh viên theo trang (sử dụng concatenation để tránh PDO binding issue với LIMIT)
        $sql = "SELECT sv.*, lh.tenlop FROM tbl_sinhvien sv LEFT JOIN lophoc lh ON sv.lophoc_id = lh.id ORDER BY sv.id ASC, sv.hoten ASC, sv.mssv ASC LIMIT " . $limit . " OFFSET " . $offset;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $data,
            'totalRecords' => $totalRecords,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ];
    } 
}
?>
