<?php
require_once __DIR__ . '/../core/connectDB.php';

class lophocModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB::Connect();
    }

    public function getAll() {
        $sql = "SELECT lh.*, COUNT(sv.id) AS sosinhvien
                FROM lophoc lh
                LEFT JOIN tbl_sinhvien sv ON sv.lophoc_id = lh.id
                GROUP BY lh.id, lh.tenlop, lh.malop
                ORDER BY lh.tenlop ASC, lh.malop ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM lophoc WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($tenlop, $malop) {
        $sql = "INSERT INTO lophoc (tenlop, malop) VALUES (:tenlop, :malop)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':tenlop', $tenlop, PDO::PARAM_STR);
        $stmt->bindValue(':malop', $malop, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function update($id, $tenlop, $malop) {
        $sql = "UPDATE lophoc SET tenlop = :tenlop, malop = :malop WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':tenlop', $tenlop, PDO::PARAM_STR);
        $stmt->bindValue(':malop', $malop, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function countSinhVien($id) {
        $sql = "SELECT COUNT(*) AS total FROM tbl_sinhvien WHERE lophoc_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['total'] : 0;
    }

    public function delete($id) {
        $sql = "DELETE FROM lophoc WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
