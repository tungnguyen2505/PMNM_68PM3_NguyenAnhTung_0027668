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
        
        <!-- Pagination -->
        <div style="text-align: center; margin-top: 30px; padding: 20px;">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?php echo $currentPage - 1; ?>" style="padding: 8px 12px; margin: 0 5px; background: #4f46e5; color: white; text-decoration: none; border-radius: 5px;">← Trang trước</a>
            <?php endif; ?>
            
            <!-- Hiển thị số trang -->
            <div style="margin: 20px 0; display: flex; justify-content: center; flex-wrap: wrap; gap: 8px;">
                <?php 
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                
                // Nút trang đầu
                if ($startPage > 1): ?>
                    <a href="?page=1" style="padding: 8px 12px; background: #e2e8f0; color: #333; text-decoration: none; border-radius: 5px;">1</a>
                    <?php if ($startPage > 2): ?>
                        <span style="padding: 8px;">...</span>
                    <?php endif;
                endif;
                
                // Nút trang giữa
                for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span style="padding: 8px 12px; background: #4f46e5; color: white; border-radius: 5px; font-weight: bold;"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>" style="padding: 8px 12px; background: #e2e8f0; color: #333; text-decoration: none; border-radius: 5px;"><?php echo $i; ?></a>
                    <?php endif;
                endfor;
                
                // Nút trang cuối
                if ($endPage < $totalPages): ?>
                    <?php if ($endPage < $totalPages - 1): ?>
                        <span style="padding: 8px;">...</span>
                    <?php endif; ?>
                    <a href="?page=<?php echo $totalPages; ?>" style="padding: 8px 12px; background: #e2e8f0; color: #333; text-decoration: none; border-radius: 5px;"><?php echo $totalPages; ?></a>
                <?php endif; ?>
            </div>
            
            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?php echo $currentPage + 1; ?>" style="padding: 8px 12px; margin: 0 5px; background: #4f46e5; color: white; text-decoration: none; border-radius: 5px;">Trang sau →</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>Không có dữ liệu sinh viên</p>
    <?php endif; ?>
</body>
</html>
