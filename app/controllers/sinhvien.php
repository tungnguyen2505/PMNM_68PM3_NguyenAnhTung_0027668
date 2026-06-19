<?php
class sinhvien extends Controller {
    public function index() {
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        if ($page === false || $page === null || $page < 1) {
            $page = 1;
        }

        $allowedPageSizes = [5, 10, 20, 50];
        $pageSize = filter_input(INPUT_GET, 'pageSize', FILTER_VALIDATE_INT);
        if (!in_array($pageSize, $allowedPageSizes, true)) {
            $pageSize = 5;
        }
        $searchKeyword = trim((string)($_GET['q'] ?? ''));

        // Hỗ trợ cả sort_combo (gộp) lẫn sort+direction riêng lẻ
        $sortCombo = trim((string)($_GET['sort_combo'] ?? ''));
        if ($sortCombo !== '' && preg_match('/^(hoten|mssv)_(asc|desc)$/', $sortCombo, $m)) {
            $sortBy = $m[1];
            $sortDirection = $m[2];
        } else {
            $sortBy = trim((string)($_GET['sort'] ?? 'hoten'));
            $sortDirection = strtolower(trim((string)($_GET['direction'] ?? 'asc')));
        }

        $sinhvienModel = $this->model('sinhvienModel');
        $pagingResult = $sinhvienModel->paging($pageSize, $page, $searchKeyword, $sortBy, $sortDirection);

        $this->view('layout/masterlayout', [
            'danhSachSinhVien' => $pagingResult['data'],
            'totalPages' => $pagingResult['totalPages'],
            'currentPage' => $pagingResult['currentPage'],
            'totalRecords' => $pagingResult['totalRecords'],
            'searchKeyword' => $searchKeyword,
            'sortBy' => $pagingResult['sortBy'],
            'sortDirection' => $pagingResult['sortDirection'],
            'perPage' => $pageSize,
            'pageSize' => $pageSize,
            'pageSizeOptions' => $allowedPageSizes,
            'viewname' => 'sinhvien/home/index',
            'title' => 'Danh sách sinh viên'
        ]);
    }

    public function create() {
        $sinhvienModel = $this->model('sinhvienModel');

        $this->view('layout/masterlayout', [
            'danhSachLopHoc' => $sinhvienModel->getAllLopHoc(),
            'viewname' => 'sinhvien/create',
            'title' => 'Thêm sinh viên mới'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hoten = trim($_POST['hoten'] ?? '');
            $gioitinh = trim($_POST['gioitinh'] ?? '');
            $mssv = trim($_POST['mssv'] ?? '');
            $lophoc_id = filter_input(INPUT_POST, 'lophoc_id', FILTER_VALIDATE_INT);

            if (!empty($hoten) && !empty($gioitinh) && !empty($mssv) && $lophoc_id && $lophoc_id > 0) {
                $sinhvienModel = $this->model('sinhvienModel');
                $result = $sinhvienModel->create($hoten, $gioitinh, $mssv, $lophoc_id);
                
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
            'danhSachLopHoc' => $sinhvienModel->getAllLopHoc(),
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
            $lophoc_id = filter_input(INPUT_POST, 'lophoc_id', FILTER_VALIDATE_INT);

            if ($id && !empty($hoten) && !empty($gioitinh) && !empty($mssv) && $lophoc_id && $lophoc_id > 0) {
                $sinhvienModel = $this->model('sinhvienModel');
                $result = $sinhvienModel->update($id, $hoten, $gioitinh, $mssv, $lophoc_id);
                
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
