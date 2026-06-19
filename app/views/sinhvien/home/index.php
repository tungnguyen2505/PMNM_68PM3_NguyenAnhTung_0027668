<?php
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
$danhSachSinhVien = $danhSachSinhVien ?? [];
$currentPage = isset($currentPage) ? (int)$currentPage : 1;
$totalPages = isset($totalPages) ? (int)$totalPages : 1;
$totalRecords = isset($totalRecords) ? (int)$totalRecords : count($danhSachSinhVien);
$searchKeyword = isset($searchKeyword) ? trim((string)$searchKeyword) : '';
$perPage = isset($perPage) ? max(1, (int)$perPage) : max(1, count($danhSachSinhVien));
$hiddenColumns = ['id', 'lophoc_id'];
$buildPageUrl = function ($page) use ($searchKeyword) {
    $query = ['page' => (int)$page];
    if ($searchKeyword !== '') {
        $query['q'] = $searchKeyword;
    }

    return '?' . http_build_query($query);
};

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
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .student-search {
        width: 100%;
        max-width: 900px;
        margin: 0 auto 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .student-search input {
        flex: 1;
        min-width: 180px;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        font-size: 14px;
    }

    .student-search input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }

    .btn-search,
    .btn-clear {
        padding: 10px 14px;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-search {
        background-color: #0f172a;
        color: white;
    }

    .btn-search:hover {
        background-color: #1e293b;
    }

    .btn-clear {
        background-color: #e2e8f0;
        color: #334155;
    }

    .btn-clear:hover {
        background-color: #cbd5e1;
    }

    .search-summary {
        width: 100%;
        max-width: 900px;
        margin: -6px auto 16px;
        color: #475569;
        font-size: 14px;
    }

    .student-table-wrap {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
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
        padding: 10px 16px;
        font-size: 15px;
    }

    .student-table td {
        padding: 8px 16px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
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
        transition: all 0.2s ease;
    }

    .pagination a {
        background: #e2e8f0;
        color: #333;
    }

    .pagination a:hover {
        background: #cbd5e1;
        color: #1e293b;
    }

    .pagination .current-page {
        background: #4f46e5;
        color: white;
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

    @media (max-width: 640px) {
        .student-search {
            align-items: stretch;
            flex-direction: column;
        }

        .student-search input,
        .student-search button,
        .student-search a {
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }
    }
</style>

<section class="student-page">
    <h1>Danh s&#225;ch sinh vi&#234;n</h1>



    <div class="student-actions">
        <a href="/sinhvien/create" class="btn-edit">+ Th&#234;m sinh vi&#234;n m&#7899;i</a>
    </div>

    <form class="student-search" action="/sinhvien/index" method="get">
        <input
            type="search"
            name="q"
            value="<?php echo htmlspecialchars($searchKeyword, ENT_QUOTES, 'UTF-8'); ?>"
            placeholder="T&#236;m theo MSSV, h&#7885; t&#234;n ho&#7863;c l&#7899;p"
            aria-label="T&#236;m theo MSSV, h&#7885; t&#234;n ho&#7863;c l&#7899;p"
        >
        <button type="submit" class="btn-search">T&#236;m ki&#7871;m</button>
        <?php if ($searchKeyword !== ''): ?>
            <a href="/sinhvien/index" class="btn-clear">X&#243;a l&#7885;c</a>
        <?php endif; ?>
    </form>

    <?php if ($searchKeyword !== ''): ?>
        <p class="search-summary">
            T&#236;m th&#7845;y <?php echo $totalRecords; ?> sinh vi&#234;n ph&#249; h&#7907;p v&#7899;i "<?php echo htmlspecialchars($searchKeyword, ENT_QUOTES, 'UTF-8'); ?>".
        </p>
    <?php endif; ?>

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
                <div class="pagination-pages">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?php echo htmlspecialchars($buildPageUrl($currentPage - 1), ENT_QUOTES, 'UTF-8'); ?>">&lt;</a>
                    <?php endif; ?>

                    <?php
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);
                    ?>

                    <?php if ($startPage > 1): ?>
                        <a href="<?php echo htmlspecialchars($buildPageUrl(1), ENT_QUOTES, 'UTF-8'); ?>">1</a>
                        <?php if ($startPage > 2): ?>
                            <span>...</span>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <?php if ($i === $currentPage): ?>
                            <span class="current-page"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="<?php echo htmlspecialchars($buildPageUrl($i), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($endPage < $totalPages): ?>
                        <?php if ($endPage < $totalPages - 1): ?>
                            <span>...</span>
                        <?php endif; ?>
                        <a href="<?php echo htmlspecialchars($buildPageUrl($totalPages), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $totalPages; ?></a>
                    <?php endif; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?php echo htmlspecialchars($buildPageUrl($currentPage + 1), ENT_QUOTES, 'UTF-8'); ?>">&gt;</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <p class="empty-state">
            <?php echo $searchKeyword !== '' ? 'Kh&#244;ng t&#236;m th&#7845;y sinh vi&#234;n ph&#249; h&#7907;p' : 'Kh&#244;ng c&#243; d&#7919; li&#7879;u sinh vi&#234;n'; ?>
        </p>
    <?php endif; ?>
</section>
