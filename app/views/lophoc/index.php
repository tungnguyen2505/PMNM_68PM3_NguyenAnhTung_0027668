<?php
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
$danhSachLopHoc = $danhSachLopHoc ?? [];
$currentPage = isset($currentPage) ? (int)$currentPage : 1;
$totalPages = isset($totalPages) ? (int)$totalPages : 1;
$totalRecords = isset($totalRecords) ? (int)$totalRecords : count($danhSachLopHoc);
$perPage = isset($perPage) ? max(1, (int)$perPage) : max(1, count($danhSachLopHoc));
$pageSize = isset($pageSize) ? (int)$pageSize : $perPage;
$pageSizeOptions = $pageSizeOptions ?? [5, 10, 20, 50];
$buildPageUrl = function ($page) use ($pageSize) {
    return '?' . http_build_query([
        'page' => (int)$page,
        'pageSize' => $pageSize,
    ]);
};

$messages = [
    'success' => ['type' => 'success', 'text' => 'Th&#234;m l&#7899;p h&#7885;c th&#224;nh c&#244;ng.'],
    'updated' => ['type' => 'success', 'text' => 'C&#7853;p nh&#7853;t l&#7899;p h&#7885;c th&#224;nh c&#244;ng.'],
    'deleted' => ['type' => 'success', 'text' => 'X&#243;a l&#7899;p h&#7885;c th&#224;nh c&#244;ng.'],
    'error' => ['type' => 'error', 'text' => 'C&#243; l&#7895;i x&#7843;y ra. Vui l&#242;ng th&#7917; l&#7841;i.'],
    'deleteerror' => ['type' => 'error', 'text' => 'X&#243;a l&#7899;p h&#7885;c th&#7845;t b&#7841;i.'],
    'notfound' => ['type' => 'error', 'text' => 'Kh&#244;ng t&#236;m th&#7845;y l&#7899;p h&#7885;c.'],
    'inuse' => ['type' => 'error', 'text' => 'Kh&#244;ng th&#7875; x&#243;a l&#7899;p h&#7885;c &#273;ang c&#243; sinh vi&#234;n.'],
];
?>

<style>
    .class-page {
        font-family: Arial, sans-serif;
    }

    .class-page h1 {
        color: #1e293b;
        margin-bottom: 18px;
        text-align: center;
    }

    .class-toolbar {
        align-items: center;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin: 0 auto 18px;
        max-width: 900px;
    }

    .class-toolbar p {
        color: #64748b;
        font-size: 14px;
    }

    .class-toolbar-actions {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .page-size-form {
        align-items: center;
        display: flex;
        gap: 8px;
    }

    .page-size-form label {
        color: #475569;
        font-size: 13px;
        font-weight: 600;
    }

    .page-size-form select {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        color: #334155;
        font-size: 14px;
        padding: 8px 10px;
    }

    .btn-primary,
    .btn-edit,
    .btn-delete {
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: inline-block;
        font-size: 14px;
        font-weight: 600;
        padding: 8px 12px;
        text-decoration: none;
    }

    .btn-primary,
    .btn-edit {
        background: #4f46e5;
        color: #ffffff;
    }

    .btn-primary:hover,
    .btn-edit:hover {
        background: #4338ca;
    }

    .btn-delete {
        background: #ef4444;
        color: #ffffff;
    }

    .btn-delete:hover {
        background: #dc2626;
    }

    .class-alert {
        border-radius: 8px;
        font-size: 14px;
        margin: 0 auto 16px;
        max-width: 900px;
        padding: 12px 14px;
    }

    .class-alert.success {
        background: #dcfce7;
        border: 1px solid #86efac;
        color: #166534;
    }

    .class-alert.error {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .class-table-wrap {
        margin: 0 auto;
        max-width: 900px;
        overflow-x: auto;
    }

    .class-table {
        background: #ffffff;
        border-collapse: collapse;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08);
        min-width: 720px;
        overflow: hidden;
        width: 100%;
    }

    .class-table th {
        background: #4f46e5;
        color: #ffffff;
        font-size: 15px;
        padding: 11px 14px;
    }

    .class-table td {
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        font-size: 14px;
        padding: 10px 14px;
        text-align: center;
    }

    .class-table tr:nth-child(even) {
        background: #f8fafc;
    }

    .class-table tr:hover {
        background: #eef2ff;
    }

    .empty-state {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #64748b;
        margin: 0 auto;
        max-width: 900px;
        padding: 24px;
        text-align: center;
    }

    .pagination {
        margin-top: 24px;
        padding: 14px 0;
    }

    .pagination-pages {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        justify-content: center;
    }

    .pagination a,
    .pagination span {
        border-radius: 7px;
        font-size: 14px;
        font-weight: 600;
        padding: 7px 12px;
        text-decoration: none;
    }

    .pagination a {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
    }

    .pagination a:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .pagination .current-page {
        background: #4f46e5;
        border: 1px solid #4f46e5;
        color: #ffffff;
    }
</style>

<section class="class-page">
    <h1>Danh s&#225;ch l&#7899;p h&#7885;c</h1>

    <div class="class-toolbar">
        <p>T&#7893;ng s&#7889;: <?php echo $totalRecords; ?> l&#7899;p h&#7885;c</p>
        <div class="class-toolbar-actions">
            <form class="page-size-form" action="/lophoc/index" method="get">
                <label for="pageSize">Hi&#7875;n th&#7883;</label>
                <select name="pageSize" id="pageSize" onchange="this.form.submit()">
                    <?php foreach ($pageSizeOptions as $option): ?>
                        <option value="<?php echo (int)$option; ?>" <?php echo (int)$option === $pageSize ? 'selected' : ''; ?>>
                            <?php echo (int)$option; ?>/trang
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <a class="btn-primary" href="/lophoc/create">+ Th&#234;m l&#7899;p h&#7885;c</a>
        </div>
    </div>

    <?php if (isset($messages[$status])): ?>
        <div class="class-alert <?php echo $messages[$status]['type']; ?>">
            <?php echo $messages[$status]['text']; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($danhSachLopHoc)): ?>
        <div class="class-table-wrap">
            <table class="class-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>T&#234;n l&#7899;p</th>
                        <th>M&#227; l&#7899;p</th>
                        <th>S&#7889; sinh vi&#234;n</th>
                        <th>H&#224;nh &#273;&#7897;ng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($danhSachLopHoc as $index => $lopHoc): ?>
                        <tr>
                            <td><?php echo (($currentPage - 1) * $perPage) + $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($lopHoc['tenlop'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($lopHoc['malop'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo (int)($lopHoc['sosinhvien'] ?? 0); ?></td>
                            <td>
                                <a class="btn-edit" href="/lophoc/edit?id=<?php echo urlencode((string)$lopHoc['id']); ?>">S&#7917;a</a>
                                <a class="btn-delete" href="/lophoc/delete?id=<?php echo urlencode((string)$lopHoc['id']); ?>" onclick="return confirm('Ban co chac chan muon xoa lop hoc nay?');">X&#243;a</a>
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
        <p class="empty-state">Ch&#432;a c&#243; d&#7919; li&#7879;u l&#7899;p h&#7885;c.</p>
    <?php endif; ?>
</section>
