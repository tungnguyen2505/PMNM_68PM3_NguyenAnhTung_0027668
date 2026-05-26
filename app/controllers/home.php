<?php
require_once '../app/models/sinhvienModel.php';

class home
{
    public function index()
    {
        $model = new sinhvienModel();
        $danhSachSinhVien = $model->getAllSinhVien();
        require_once '../app/views/sinhvien/home/index.php';
    }
    
    public function about(){
        echo "day la trang gioi thieu";
    }
    
    public function login(){
        require_once __DIR__ . '/../views/sinhvien/home/login.php';
    }

}
?>