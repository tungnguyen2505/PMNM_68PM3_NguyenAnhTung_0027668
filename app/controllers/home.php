<?php
class home
{
    public function index()
    {
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