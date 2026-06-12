<?php
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
$danhSachSinhVien = $danhSachSinhVien ?? [];
$currentPage = isset($currentPage) ? (int)$currentPage : 1;
$totalPages = isset($totalPages) ? (int)$totalPages : 1;
$perPage = isset($perPage) ? max(1, (int)$perPage) : max(1, count($danhSachSinhVien));
$hiddenColumns = ['id', 'lophoc_id'];

$columnLabels = [
    'hoten' => 'H&#7885; t&#234;n',
    'gioitinh' => 'Gi&#7899;i t&#237;nh',
    'mssv' => 'MSSV',
    'tenlop' => 'L&#7899;p h&#7885;c',
];
?>

<style>
    .student-page {
        font-family: Arial, sans-serif;
    }

    .student-page h1 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    .student-actions {
        text-align: center;
        margin-bottom: 20px;
    }

    .student-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .student-table {
        width: 100%;
        min-width: 720px;
        margin: auto;
        border-collapse: collapse;
        background-color: white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .student-table th {
        background-color: #4f46e5;
        color: white;
        padding: 12px;
    }

    .student-table td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    .student-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .student-table tr:hover {
        background-color: #eef2ff;
        transition: 0.2s;
    }

    .btn-edit,
    .btn-delete {
        padding: 6px 12px;
        margin: 0 4px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: 0.2s;
    }

    .btn-edit {
        background-color: #4f46e5;
        color: white;
    }

    .btn-edit:hover {
        background-color: #4338ca;
    }

    .btn-delete {
        background-color: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background-color: #dc2626;
    }

    .student-page .alert {
        padding: 12px 16px;
        margin-bottom: 20px;
        border-radius: 8px;
        font-size: 14px;
    }

    .student-page .alert-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .student-page .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .pagination {
        text-align: center;
        margin-top: 30px;
        padding: 20px;
    }

    .pagination-pages {
        margin: 20px 0;
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border-radius: 5px;
        text-decoration: none;
    }

    .pagination a {
        background: #e2e8f0;
        color: #333;
    }

    .pagination .pagination-prev,
    .pagination .pagination-next,
    .pagination .current-page {
        background: #4f46e5;
        color: white;
    }

    .pagination .current-page {
        font-weight: bold;
    }

    .empty-state {
        text-align: center;
        color: #64748b;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 24px;
    }
</style>

<section class="student-page">
    <h1>Danh s&#225;ch sinh vi&#234;n</h1>

    <?php if ($status === 'success'): ?>
        <div class="alert alert-success">Th&#234;m sinh vi&#234;n m&#7899;i th&#224;nh c&#244;ng!</div>
    <?php elseif ($status === 'updated'): ?>
        <div class="alert alert-success">C&#7853;p nh&#7853;t sinh vi&#234;n th&#224;nh c&#244;ng!</div>
    <?php elseif ($status === 'deleted'): ?>
        <div class="alert alert-success">X&#243;a sinh vi&#234;n th&#224;nh c&#244;ng!</div>
    <?php elseif ($status === 'error'): ?>
        <div class="alert alert-error">C&#243; l&#7895;i x&#7843;y ra. Vui l&#242;ng th&#7917; l&#7841;i.</div>
    <?php elseif ($status === 'notfound'): ?>
        <div class="alert alert-error">Kh&#244;ng t&#236;m th&#7845;y sinh vi&#234;n.</div>
    <?php elseif ($status === 'deleteerror'): ?>
        <div class="alert alert-error">X&#243;a sinh vi&#234;n th&#7845;t b&#7841;i. Vui l&#242;ng th&#7917; l&#7841;i.</div>
    <?php endif; ?>

    <div class="student-actions">
        <a href="/sinhvien/create" class="btn-edit">+ Th&#234;m sinh vi&#234;n m&#7899;i</a>
    </div>

    <?php if (!empty($danhSachSinhVien)): ?>
        <div class="student-table-wrap">
            <table class="student-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <?php foreach (array_keys($danhSachSinhVien[0]) as $column): ?>
                            <?php if (!in_array($column, $hiddenColumns, true)): ?>
                                <th><?php echo $columnLabels[$column] ?? htmlspecialchars(ucfirst($column), ENT_QUOTES, 'UTF-8'); ?></th>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <th>H&#224;nh &#273;&#7897;ng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($danhSachSinhVien as $index => $sinhVien): ?>
                        <tr>
                            <td><?php echo (($currentPage - 1) * $perPage) + $index + 1; ?></td>
                            <?php foreach ($sinhVien as $column => $value): ?>
                                <?php if (!in_array($column, $hiddenColumns, true)): ?>
                                    <td><?php echo htmlspecialchars((string)($value ?? 'N/A'), ENT_QUOTES, 'UTF-8'); ?></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <td>
                                <a href="/sinhvien/edit?id=<?php echo urlencode((string)$sinhVien['id']); ?>" class="btn-edit">S&#7917;a</a>
                                <a href="/sinhvien/delete?id=<?php echo urlencode((string)$sinhVien['id']); ?>" class="btn-delete" onclick="return confirm('Ban co chac chan muon xoa sinh vien nay?');">X&#243;a</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a class="pagination-prev" href="?page=<?php echo $currentPage - 1; ?>">Trang tr&#432;&#7899;c</a>
                <?php endif; ?>

                <div class="pagination-pages">
                    <?php
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);
                    ?>

                    <?php if ($startPage > 1): ?>
                        <a href="?page=1">1</a>
                        <?php if ($startPage > 2): ?>
                            <span>...</span>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <?php if ($i === $currentPage): ?>
                            <span class="current-page"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($endPage < $totalPages): ?>
                        <?php if ($endPage < $totalPages - 1): ?>
                            <span>...</span>
                        <?php endif; ?>
                        <a href="?page=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a>
                    <?php endif; ?>
                </div>

                <?php if ($currentPage < $totalPages): ?>
                    <a class="pagination-next" href="?page=<?php echo $currentPage + 1; ?>">Trang sau</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <p class="empty-state">Kh&#244;ng c&#243; d&#7919; li&#7879;u sinh vi&#234;n</p>
    <?php endif; ?>
</section>
