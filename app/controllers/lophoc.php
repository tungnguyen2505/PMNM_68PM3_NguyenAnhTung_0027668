<?php
class lophoc extends Controller {
    public function index() {
        $lophocModel = $this->model('lophocModel');

        $this->view('layout/masterlayout', [
            'danhSachLopHoc' => $lophocModel->getAll(),
            'viewname' => 'lophoc/index',
            'title' => 'Danh sách lớp học'
        ]);
    }

    public function create() {
        $this->view('layout/masterlayout', [
            'viewname' => 'lophoc/create',
            'title' => 'Thêm lớp học'
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /lophoc/create');
            exit();
        }

        $tenlop = trim($_POST['tenlop'] ?? '');
        $malop = trim($_POST['malop'] ?? '');

        if ($tenlop === '' || $malop === '') {
            header('Location: /lophoc/create?status=empty');
            exit();
        }

        try {
            $lophocModel = $this->model('lophocModel');
            $result = $lophocModel->create($tenlop, $malop);
        } catch (PDOException $e) {
            header('Location: /lophoc/create?status=duplicate');
            exit();
        }

        header('Location: /lophoc/index?status=' . ($result ? 'success' : 'error'));
        exit();
    }

    public function edit() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id || $id < 1) {
            header('Location: /lophoc/index');
            exit();
        }

        $lophocModel = $this->model('lophocModel');
        $lopHoc = $lophocModel->getById($id);

        if (!$lopHoc) {
            header('Location: /lophoc/index?status=notfound');
            exit();
        }

        $this->view('layout/masterlayout', [
            'lopHoc' => $lopHoc,
            'viewname' => 'lophoc/edit',
            'title' => 'Chỉnh sửa lớp học'
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /lophoc/index');
            exit();
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $tenlop = trim($_POST['tenlop'] ?? '');
        $malop = trim($_POST['malop'] ?? '');

        if (!$id || $id < 1 || $tenlop === '' || $malop === '') {
            header('Location: /lophoc/edit?id=' . urlencode((string)$id) . '&status=empty');
            exit();
        }

        try {
            $lophocModel = $this->model('lophocModel');
            $result = $lophocModel->update($id, $tenlop, $malop);
        } catch (PDOException $e) {
            header('Location: /lophoc/edit?id=' . urlencode((string)$id) . '&status=duplicate');
            exit();
        }

        header('Location: /lophoc/index?status=' . ($result ? 'updated' : 'error'));
        exit();
    }

    public function delete() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id || $id < 1) {
            header('Location: /lophoc/index');
            exit();
        }

        $lophocModel = $this->model('lophocModel');
        if (!$lophocModel->getById($id)) {
            header('Location: /lophoc/index?status=notfound');
            exit();
        }

        if ($lophocModel->countSinhVien($id) > 0) {
            header('Location: /lophoc/index?status=inuse');
            exit();
        }

        $result = $lophocModel->delete($id);
        header('Location: /lophoc/index?status=' . ($result ? 'deleted' : 'deleteerror'));
        exit();
    }
}
?>
