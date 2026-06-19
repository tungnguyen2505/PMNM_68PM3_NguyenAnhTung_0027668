
    <style>
    .form-container {
        max-width: 500px;
        margin: 50px auto;
        background: #ffffff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid #eef2f6;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-container h2 {
        text-align: center;
        color: #1e1b4b;
        margin-bottom: 30px;
        font-size: 24px;
        font-weight: 700;
    }

    .form-group {
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 600;
        color: #4b5563;
        text-align: left;
    }

    .form-group input, .form-group select {
        padding: 12px 16px;
        border: 1.5px solid #d1d5db;
        border-radius: 8px;
        font-size: 15px;
        outline: none;
        transition: all 0.3s ease;
    }

    .form-group input:focus, .form-group select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
    }

    .btn-submit {
        flex: 1;
        background: #4f46e5;
        color: white;
        padding: 14px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-submit:hover {
        background: #4338ca;
    }

    .btn-cancel {
        display: inline-block;
        text-align: center;
        text-decoration: none;
        color: #4b5563;
        background: #f3f4f6;
        padding: 14px 20px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        transition: background 0.3s ease;
        line-height: 1.2;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
    }
</style>

   <div class="form-container">
    <h2>Thêm Sinh Viên Mới</h2>
    <form action="/sinhvien/store" method="POST">
        <div class="form-group">
            <label for="hoten">Họ và tên</label>
            <input type="text" name="hoten" id="hoten" placeholder="Nhập họ và tên sinh viên" required>
        </div>
        
        <div class="form-group">
            <label for="mssv">Mã số sinh viên (MSSV)</label>
            <input type="text" name="mssv" id="mssv" placeholder="Nhập mã số sinh viên" required>
        </div>

        <div class="form-group">
            <label for="gioitinh">Giới tính</label>
            <select name="gioitinh" id="gioitinh" required>
                <option value="" disabled selected>-- Chọn giới tính --</option>
                <option value="Nam">Nam</option>
                <option value="Nu">Nữ</option>
                <option value="Khac">Khác</option>
            </select>
        </div>

        <div class="form-group">
            <label for="lophoc_id">Khoa/L&#7899;p</label>
            <select name="lophoc_id" id="lophoc_id" required>
                <option value="" disabled selected>-- Ch&#7885;n khoa/l&#7899;p --</option>
                <?php foreach (($danhSachLopHoc ?? []) as $lopHoc): ?>
                    <option value="<?php echo htmlspecialchars((string)$lopHoc['id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars(($lopHoc['tenlop'] ?? '') . ' (' . ($lopHoc['malop'] ?? '') . ')', ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Thêm sinh viên</button>
            <a href="/sinhvien/index" class="btn-cancel">Hủy bỏ</a>
        </div>
    </form>
</div>
