<?php

class sinhvien extends Controller{
    public function index(){
        $sinhvienModel = $this->model('sinhvienModel');
        $sinhviens = $sinhvienModel->getAllSinhVien();

        require_once __DIR__ . '/../views/sinhvien/index.php';
        $this->view('sinhvien/index', $sinhviens);
        

    }
    public function create(){
        require_once __DIR__ . '/../views/sinhvien/create.php';
    }
}

?>