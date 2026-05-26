<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sinh viên</title>
  
</head>
<body>
    <h1>Danh sách sinh viên</h1>

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