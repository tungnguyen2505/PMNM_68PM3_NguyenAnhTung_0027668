<?php
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
$danhSachLopHoc = $danhSachLopHoc ?? [];

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
        gap: 12px;
        margin: 0 auto 18px;
        max-width: 900px;
    }

    .class-toolbar p {
        color: #64748b;
        font-size: 14px;
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
</style>

<section class="class-page">
    <h1>Danh s&#225;ch l&#7899;p h&#7885;c</h1>

    <div class="class-toolbar">
        <p>T&#7893;ng s&#7889;: <?php echo count($danhSachLopHoc); ?> l&#7899;p h&#7885;c</p>
        <a class="btn-primary" href="/lophoc/create">+ Th&#234;m l&#7899;p h&#7885;c</a>
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
                            <td><?php echo $index + 1; ?></td>
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
    <?php else: ?>
        <p class="empty-state">Ch&#432;a c&#243; d&#7919; li&#7879;u l&#7899;p h&#7885;c.</p>
    <?php endif; ?>
</section>
