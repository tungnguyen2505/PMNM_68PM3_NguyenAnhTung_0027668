<?php
class sinhvien extends Controller {
    public function index() {
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        if ($page === false || $page === null || $page < 1) {
            $page = 1;
        }

        $limit = 5;

        $sinhvienModel = $this->model('sinhvienModel');
        $pagingResult = $sinhvienModel->paging($limit, $page);

        $this->view('layout/masterlayout', [
            'danhSachSinhVien' => $pagingResult['data'],
            'totalPages' => $pagingResult['totalPages'],
            'currentPage' => $pagingResult['currentPage'],
            'perPage' => $limit,
            'viewname' => 'sinhvien/home/index',
            'title' => 'Danh sách sinh viên'
        ]);
    }

    public function create() {
        $this->view('layout/masterlayout', [
            'viewname' => 'sinhvien/create',
            'title' => 'Thêm sinh viên mới'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hoten = trim($_POST['hoten'] ?? '');
            $gioitinh = trim($_POST['gioitinh'] ?? '');
            $mssv = trim($_POST['mssv'] ?? '');

            if (!empty($hoten) && !empty($gioitinh) && !empty($mssv)) {
                $sinhvienModel = $this->model('sinhvienModel');
                $result = $sinhvienModel->create($hoten, $gioitinh, $mssv);
                
                if ($result) {
                    // Chuyển hướng kèm trạng thái thành công
                    header('Location: /sinhvien/index?status=success');
                    exit();
                } else {
                    header('Location: /sinhvien/create?status=error');
                    exit();
                }
            } else {
                header('Location: /sinhvien/create?status=empty');
                exit();
            }
        } else {
            header('Location: /sinhvien/create');
            exit();
        }
    }

    public function edit() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$id || $id < 1) {
            header('Location: /sinhvien/index');
            exit();
        }

        $sinhvienModel = $this->model('sinhvienModel');
        $sinhvien = $sinhvienModel->getById($id);

        if (!$sinhvien) {
            header('Location: /sinhvien/index?status=notfound');
            exit();
        }

        $this->view('layout/masterlayout', [
            'sinhvien' => $sinhvien,
            'viewname' => 'sinhvien/edit',
            'title' => 'Chỉnh sửa sinh viên'
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $hoten = trim($_POST['hoten'] ?? '');
            $gioitinh = trim($_POST['gioitinh'] ?? '');
            $mssv = trim($_POST['mssv'] ?? '');

            if ($id && !empty($hoten) && !empty($gioitinh) && !empty($mssv)) {
                $sinhvienModel = $this->model('sinhvienModel');
                $result = $sinhvienModel->update($id, $hoten, $gioitinh, $mssv);
                
                if ($result) {
                    header('Location: /sinhvien/index?status=updated');
                    exit();
                } else {
                    header('Location: /sinhvien/edit?id=' . $id . '&status=error');
                    exit();
                }
            } else {
                header('Location: /sinhvien/edit?id=' . $id . '&status=empty');
                exit();
            }
        } else {
            header('Location: /sinhvien/index');
            exit();
        }
    }

    public function delete() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            header('Location: /sinhvien/index');
            exit();
        }

        $sinhvienModel = $this->model('sinhvienModel');
        
        // Kiểm tra sinh viên có tồn tại
        $sinhvien = $sinhvienModel->getById($id);
        if (!$sinhvien) {
            header('Location: /sinhvien/index?status=notfound');
            exit();
        }

        $result = $sinhvienModel->delete($id);
        
        if ($result) {
            header('Location: /sinhvien/index?status=deleted');
        } else {
            header('Location: /sinhvien/index?status=deleteerror');
        }
        exit();
    }
}
?>
