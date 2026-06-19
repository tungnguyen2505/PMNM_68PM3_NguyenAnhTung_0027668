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

    public function getAllLopHoc() {
        $sql = "SELECT id, tenlop, malop FROM lophoc ORDER BY tenlop ASC, malop ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($hoten, $gioitinh, $mssv, $lophoc_id = null) {
        $sql = "INSERT INTO tbl_sinhvien (hoten, gioitinh, mssv, lophoc_id) VALUES (:hoten, :gioitinh, :mssv, :lophoc_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':hoten', $hoten);
        $stmt->bindParam(':gioitinh', $gioitinh);
        $stmt->bindParam(':mssv', $mssv);
        $stmt->bindValue(':lophoc_id', $lophoc_id, $lophoc_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
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
        if ($lophoc_id !== null) {
            $sql = "UPDATE tbl_sinhvien SET hoten = :hoten, gioitinh = :gioitinh, mssv = :mssv, lophoc_id = :lophoc_id WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':lophoc_id', $lophoc_id, PDO::PARAM_INT);
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

    public function paging($limit = 5, $page = 1, $searchKeyword = '', $sortBy = 'hoten', $sortDirection = 'asc') {
        $limit = max(1, (int)$limit);
        $page = max(1, (int)$page);
        $searchKeyword = trim((string)$searchKeyword);
        $allowedSorts = [
            'hoten' => 'sv.hoten',
            'mssv' => 'sv.mssv',
        ];
        $sortBy = array_key_exists($sortBy, $allowedSorts) ? $sortBy : 'hoten';
        $sortDirection = strtolower((string)$sortDirection) === 'desc' ? 'desc' : 'asc';
        $orderSql = $allowedSorts[$sortBy] . ' ' . strtoupper($sortDirection) . ', sv.id ASC';
        $whereSql = '';
        $params = [];

        if ($searchKeyword !== '') {
            $whereSql = " WHERE sv.mssv LIKE :keyword OR sv.hoten LIKE :keyword OR lh.tenlop LIKE :keyword";
            $params[':keyword'] = '%' . $searchKeyword . '%';
        }

        // Tính tổng số bản ghi
        $countSql = "SELECT COUNT(*) as total FROM tbl_sinhvien sv LEFT JOIN lophoc lh ON sv.lophoc_id = lh.id" . $whereSql;
        $selectAllQuery = $this->conn->prepare($countSql);
        foreach ($params as $name => $value) {
            $selectAllQuery->bindValue($name, $value, PDO::PARAM_STR);
        }
        $selectAllQuery->execute();
        $row = $selectAllQuery->fetch(PDO::FETCH_ASSOC);
        $totalRecords = $row ? (int)$row['total'] : 0;
        $totalPages = $totalRecords > 0 ? (int)ceil($totalRecords / $limit) : 1;
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $limit;

        // Lấy danh sách sinh viên theo trang (sử dụng concatenation để tránh PDO binding issue với LIMIT)
        $sql = "SELECT sv.*, lh.tenlop FROM tbl_sinhvien sv LEFT JOIN lophoc lh ON sv.lophoc_id = lh.id" . $whereSql . " ORDER BY " . $orderSql . " LIMIT " . $limit . " OFFSET " . $offset;
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $name => $value) {
            $stmt->bindValue($name, $value, PDO::PARAM_STR);
        }
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $data,
            'totalRecords' => $totalRecords,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection
        ];
    } 
}
?>
