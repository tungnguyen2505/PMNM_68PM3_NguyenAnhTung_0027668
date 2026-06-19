<?php
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
$danhSachSinhVien = $danhSachSinhVien ?? [];
$currentPage = isset($currentPage) ? (int)$currentPage : 1;
$totalPages = isset($totalPages) ? (int)$totalPages : 1;
$totalRecords = isset($totalRecords) ? (int)$totalRecords : count($danhSachSinhVien);
$searchKeyword = isset($searchKeyword) ? trim((string)$searchKeyword) : '';
$sortBy = isset($sortBy) && in_array($sortBy, ['mssv', 'hoten'], true) ? $sortBy : 'hoten';
$sortDirection = isset($sortDirection) && $sortDirection === 'desc' ? 'desc' : 'asc';
$perPage = isset($perPage) ? max(1, (int)$perPage) : max(1, count($danhSachSinhVien));
$pageSize = isset($pageSize) ? (int)$pageSize : $perPage;
$pageSizeOptions = $pageSizeOptions ?? [5, 10, 20, 50];
$hiddenColumns = ['id', 'lophoc_id'];
$buildListUrl = function (array $overrides = []) use ($searchKeyword, $sortBy, $sortDirection, $pageSize) {
    $query = ['page' => 1];
    if ($searchKeyword !== '') {
        $query['q'] = $searchKeyword;
    }
    if ($sortBy !== '') {
        $query['sort'] = $sortBy;
        $query['direction'] = $sortDirection;
    }
    $query['pageSize'] = $pageSize;
    $query = array_merge($query, $overrides);

    return '?' . http_build_query($query);
};
$buildPageUrl = function ($page) use ($buildListUrl) {
    return $buildListUrl(['page' => (int)$page]);
};
$buildSortUrl = function ($column) use ($buildListUrl, $sortBy, $sortDirection) {
    $nextDirection = ($sortBy === $column && $sortDirection === 'asc') ? 'desc' : 'asc';

    return $buildListUrl([
        'page' => 1,
        'sort' => $column,
        'direction' => $nextDirection,
    ]);
};
$sortLabels = [
    'asc' => '&#8593;',
    'desc' => '&#8595;',
];
$columnLabels = [
    'hoten' => 'H&#7885; t&#234;n',
    'gioitinh' => 'Gi&#7899;i t&#237;nh',
    'mssv' => 'MSSV',
    'tenlop' => 'L&#7899;p h&#7885;c',
];

// Combo value = sort + "_" + direction, tự parse khi form submit
$sortCombo = $sortBy . '_' . $sortDirection;

// Tất cả combo options: label => [sort, direction]
$sortComboOptions = [
    'hoten_asc'  => 'H&#7885; t&#234;n &nbsp;&#8593; T&#259;ng d&#7847;n',
    'hoten_desc' => 'H&#7885; t&#234;n &nbsp;&#8595; Gi&#7843;m d&#7847;n',
    'mssv_asc'   => 'MSSV &nbsp;&#8593; T&#259;ng d&#7847;n',
    'mssv_desc'  => 'MSSV &nbsp;&#8595; Gi&#7843;m d&#7847;n',
];
?>

<style>
    /* ── Import font ── */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    .student-page {
        font-family: 'Inter', Arial, sans-serif;
    }

    .student-page h1 {
        text-align: center;
        color: #1e293b;
        margin-bottom: 24px;
        font-size: 26px;
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    /* ── Actions bar ── */
    .student-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
    }

    /* ── Filter card ── */
    .filter-card {
        width: 100%;
        max-width: 900px;
        margin: 0 auto 20px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px 20px 14px;
        box-shadow: 0 2px 12px rgba(79, 70, 229, 0.07);
    }

    .filter-card-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.9px;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 14px;
    }

    .filter-row {
        display: flex;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 12px;
    }

    /* ── Individual field ── */
    .filter-field {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-field--grow {
        flex: 1;
        min-width: 200px;
    }

    .filter-field--compact {
        flex: 0 0 210px;
    }

    .filter-field--small {
        flex: 0 0 120px;
    }

    .filter-field label {
        font-size: 11.5px;
        font-weight: 600;
        color: #64748b;
        letter-spacing: 0.3px;
    }

    /* ── Search input wrapper (icon inside) ── */
    .input-icon-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon-wrap svg {
        position: absolute;
        left: 11px;
        color: #94a3b8;
        pointer-events: none;
        flex-shrink: 0;
    }

    .input-icon-wrap input {
        width: 100%;
        padding: 9px 12px 9px 34px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13.5px;
        font-family: inherit;
        color: #1e293b;
        background: #f8fafc;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        outline: none;
        box-sizing: border-box;
    }

    .input-icon-wrap input:focus {
        border-color: #4f46e5;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    /* ── Select wrapper (custom arrow) ── */
    .select-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .select-wrap svg {
        position: absolute;
        left: 10px;
        color: #94a3b8;
        pointer-events: none;
        flex-shrink: 0;
    }

    .select-wrap select {
        width: 100%;
        appearance: none;
        -webkit-appearance: none;
        padding: 9px 32px 9px 32px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13.5px;
        font-family: inherit;
        color: #1e293b;
        background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 10px center;
        cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        outline: none;
        box-sizing: border-box;
    }

    .select-wrap select:focus {
        border-color: #4f46e5;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    /* ── Filter actions ── */
    .filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 1px;
    }

    .btn-search {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 18px;
        border: none;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.35);
        transition: opacity 0.18s, transform 0.15s, box-shadow 0.18s;
        white-space: nowrap;
    }

    .btn-search:hover {
        opacity: 0.92;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
    }

    .btn-search:active {
        transform: translateY(0);
        opacity: 1;
    }

    .btn-clear {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 9px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        font-family: inherit;
        cursor: pointer;
        background: #fff;
        color: #64748b;
        text-decoration: none;
        transition: border-color 0.18s, color 0.18s, background 0.18s;
        white-space: nowrap;
    }

    .btn-clear:hover {
        border-color: #ef4444;
        color: #dc2626;
        background: #fff5f5;
    }

    /* ── Search summary ── */
    .search-summary {
        width: 100%;
        max-width: 900px;
        margin: 0 auto 14px;
        display: flex;
        align-items: center;
        gap: 7px;
        color: #475569;
        font-size: 13.5px;
    }

    .search-summary svg {
        color: #4f46e5;
        flex-shrink: 0;
    }

    /* ── Table ── */
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
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        border-radius: 10px;
        overflow: hidden;
    }

    .student-table th {
        background-color: #4f46e5;
        color: white;
        padding: 12px 16px;
        font-size: 13.5px;
        font-weight: 600;
        letter-spacing: 0.2px;
    }

    .student-table th a {
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 999px;
        color: inherit;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 4px 10px;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s;
    }

    .student-table th a:hover,
    .student-table th a.active {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.6);
    }

    .sort-indicator {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.2);
        border-radius: 999px;
        height: 18px;
        min-width: 18px;
        font-size: 11px;
    }

    .student-table td {
        padding: 10px 16px;
        text-align: center;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13.5px;
        color: #334155;
    }

    .student-table tr:last-child td {
        border-bottom: none;
    }

    .student-table tr:nth-child(even) td {
        background-color: #f8fafc;
    }

    .student-table tbody tr:hover td {
        background-color: #eef2ff;
        transition: background 0.18s;
    }

    /* ── Action buttons ── */
    .btn-edit,
    .btn-delete {
        padding: 5px 13px;
        margin: 0 3px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-family: inherit;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: opacity 0.18s, transform 0.15s;
    }

    .btn-edit {
        background-color: #4f46e5;
        color: white;
    }

    .btn-edit:hover {
        opacity: 0.88;
        transform: translateY(-1px);
    }

    .btn-delete {
        background-color: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        opacity: 0.88;
        transform: translateY(-1px);
    }

    /* ── Pagination ── */
    .pagination {
        margin-top: 28px;
        padding: 16px 0;
    }

    .pagination-pages {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 7px;
    }

    .pagination a,
    .pagination span {
        padding: 7px 13px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        transition: all 0.18s ease;
    }

    .pagination a {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .pagination a:hover {
        background: #e2e8f0;
        color: #1e293b;
        border-color: #cbd5e1;
    }

    .pagination .current-page {
        background: #4f46e5;
        color: white;
        font-weight: 700;
        border: 1px solid #4f46e5;
        box-shadow: 0 2px 6px rgba(79,70,229,0.3);
    }

    .pagination span:not(.current-page) {
        color: #94a3b8;
        background: transparent;
        border: none;
        padding: 7px 5px;
    }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        color: #64748b;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 32px;
        max-width: 900px;
        margin: 0 auto;
    }

    /* ── Responsive ── */
    @media (max-width: 640px) {
        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-field--compact {
            flex: 1;
        }

        .filter-actions {
            justify-content: stretch;
        }

        .btn-search,
        .btn-clear {
            flex: 1;
            justify-content: center;
        }
    }
</style>

<section class="student-page">
    <h1>Danh s&#225;ch sinh vi&#234;n</h1>



    <div class="student-actions">
        <a href="/sinhvien/create" class="btn-edit">+ Th&#234;m sinh vi&#234;n m&#7899;i</a>
    </div>

    <form class="filter-card" action="/sinhvien/index" method="get">
        <div class="filter-card-title">&#128269; T&#236;m ki&#7871;m &amp; S&#7855;p x&#7871;p</div>
        <div class="filter-row">

            <!-- Search -->
            <div class="filter-field filter-field--grow">
                <label for="q">T&#236;m ki&#7871;m</label>
                <div class="input-icon-wrap">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input
                        type="search"
                        name="q"
                        id="q"
                        value="<?php echo htmlspecialchars($searchKeyword, ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="MSSV, h&#7885; t&#234;n ho&#7863;c l&#7899;p..."
                        aria-label="T&#236;m theo MSSV, h&#7885; t&#234;n ho&#7863;c l&#7899;p"
                    >
                </div>
            </div>

            <!-- Sort combo (sort + direction gộp 1) -->
            <div class="filter-field filter-field--compact">
                <label for="sort_combo">S&#7855;p x&#7871;p theo</label>
                <div class="select-wrap">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                        <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                    </svg>
                    <select name="sort_combo" id="sort_combo" onchange="this.form.submit()">
                        <?php foreach ($sortComboOptions as $val => $label): ?>
                            <option value="<?php echo $val; ?>" <?php echo $sortCombo === $val ? 'selected' : ''; ?>>
                                <?php echo $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="filter-field filter-field--small">
                <label for="pageSize">Hi&#7875;n th&#7883;</label>
                <div class="select-wrap">
                    <select name="pageSize" id="pageSize" onchange="this.form.submit()">
                        <?php foreach ($pageSizeOptions as $option): ?>
                            <option value="<?php echo (int)$option; ?>" <?php echo (int)$option === $pageSize ? 'selected' : ''; ?>>
                                <?php echo (int)$option; ?>/trang
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Actions -->
            <div class="filter-actions">
                <button type="submit" class="btn-search">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    T&#236;m ki&#7871;m
                </button>
                <?php if ($searchKeyword !== '' || $sortBy !== 'hoten' || $sortDirection !== 'asc' || $pageSize !== 5): ?>
                    <a href="/sinhvien/index" class="btn-clear">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        &#272;&#7863;t l&#7841;i
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <?php if ($searchKeyword !== ''): ?>
        <p class="search-summary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            T&#236;m th&#7845;y <strong><?php echo $totalRecords; ?></strong> sinh vi&#234;n ph&#249; h&#7907;p v&#7899;i "<?php echo htmlspecialchars($searchKeyword, ENT_QUOTES, 'UTF-8'); ?>".
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
                                <th>
                                    <?php if (in_array($column, ['mssv', 'hoten'], true)): ?>
                                        <a class="<?php echo $sortBy === $column ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($buildSortUrl($column), ENT_QUOTES, 'UTF-8'); ?>" aria-label="S&#7855;p x&#7871;p theo <?php echo $columnLabels[$column] ?? htmlspecialchars(ucfirst($column), ENT_QUOTES, 'UTF-8'); ?>">
                                            <span><?php echo $columnLabels[$column] ?? htmlspecialchars(ucfirst($column), ENT_QUOTES, 'UTF-8'); ?></span>
                                            <span class="sort-indicator"><?php echo $sortBy === $column ? $sortLabels[$sortDirection] : '&#8597;'; ?></span>
                                        </a>
                                    <?php else: ?>
                                        <?php echo $columnLabels[$column] ?? htmlspecialchars(ucfirst($column), ENT_QUOTES, 'UTF-8'); ?>
                                    <?php endif; ?>
                                </th>
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
