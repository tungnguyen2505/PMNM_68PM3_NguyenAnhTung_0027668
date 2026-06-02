<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sinh viên</title>
  <style>
  body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color: #4f46e5;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #eef2ff;
            transition: 0.2s;
        }
    </style>
</head>
<body>
    <h1>Danh sách sinh viên</h1>
  <div style="text-align: center; margin-bottom: 20px;">
        <a href="sinhvien/create" class="btn-add">Thêm Sinh Viên Mới</a>
    </div>

    <?php if (!empty($danhSachSinhVien)): ?>
        <table border="1">
            <thead>
                <tr>
                    <?php foreach (array_keys($danhSachSinhVien[0]) as $column): ?>
                        <th><?php echo ucfirst($column); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($danhSachSinhVien as $sinhVien): ?>
                    <tr>
                        <?php foreach ($sinhVien as $value): ?>
                            <td><?php echo htmlspecialchars($value); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Không có dữ liệu sinh viên</p>
    <?php endif; ?>
</body>
</html>