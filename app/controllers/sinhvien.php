<?php
class sinhvien extends Controller{
    public function index(){
    
        $sinhvienModel = $this->model('sinhvienModel');
        $sinhviens = $sinhvienModel->getAllSinhVien();
             $this->view('layout/masterlayout', [
            'danhSachSinhVien' => $sinhviens,
            'viewname' => 'sinhvien/home/index',
            'title' => 'Danh sách sinh viên'
        ]);

    }
    public function create(){
        require_once __DIR__ . '/../views/sinhvien/create.php';
    }

    public function store(){
        
        $hoten = $_POST['hoten'];
        $gioitinh = $_POST['gioitinh'];
        $mssv = $_POST['mssv'];
        echo "Họ tên: " . $hoten . "<br>";
        echo "Giới tính: " . $gioitinh . "<br>";
        echo "Mã số sinh viên: " . $mssv . "<br>";
        $result = $sinhvienModel = $this->model('sinhvienModel')->create($hoten, $gioitinh, $mssv);
        if($result){
            echo "Thêm sinh viên thành công!";
        } else {
            echo "Thêm sinh viên thất bại!";
        }
    }

 
}

?>