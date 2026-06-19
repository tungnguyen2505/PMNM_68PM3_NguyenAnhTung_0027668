<?php
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
$lopHoc = $lopHoc ?? [];
?>

<style>
    .class-form {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        font-family: Arial, sans-serif;
        margin: 40px auto;
        max-width: 520px;
        padding: 32px;
    }

    .class-form h1 {
        color: #1e293b;
        font-size: 24px;
        margin-bottom: 22px;
        text-align: center;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 18px;
    }

    .form-group label {
        color: #475569;
        font-size: 14px;
        font-weight: 700;
    }

    .form-group input {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 15px;
        padding: 12px 14px;
    }

    .form-group input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        outline: none;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .btn-submit,
    .btn-cancel {
        border: none;
        border-radius: 6px;
        cursor: pointer;
        flex: 1;
        font-size: 15px;
        font-weight: 700;
        padding: 12px 16px;
        text-align: center;
        text-decoration: none;
    }

    .btn-submit {
        background: #4f46e5;
        color: #ffffff;
    }

    .btn-submit:hover {
        background: #4338ca;
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #334155;
    }

    .btn-cancel:hover {
        background: #cbd5e1;
    }

    .alert-error {
        background: #fee2e2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        color: #991b1b;
        font-size: 14px;
        margin-bottom: 18px;
        padding: 12px 14px;
    }
</style>

<section class="class-form">
    <h1>Ch&#7881;nh s&#7917;a l&#7899;p h&#7885;c</h1>

    <?php if ($status === 'empty'): ?>
        <div class="alert-error">Vui l&#242;ng nh&#7853;p &#273;&#7847;y &#273;&#7911; t&#234;n l&#7899;p v&#224; m&#227; l&#7899;p.</div>
    <?php elseif ($status === 'duplicate'): ?>
        <div class="alert-error">M&#227; l&#7899;p &#273;&#227; t&#7891;n t&#7841;i.</div>
    <?php endif; ?>

    <form action="/lophoc/update" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)($lopHoc['id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">

        <div class="form-group">
            <label for="tenlop">T&#234;n l&#7899;p</label>
            <input type="text" name="tenlop" id="tenlop" value="<?php echo htmlspecialchars($lopHoc['tenlop'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group">
            <label for="malop">M&#227; l&#7899;p</label>
            <input type="text" name="malop" id="malop" value="<?php echo htmlspecialchars($lopHoc['malop'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">C&#7853;p nh&#7853;t</button>
            <a class="btn-cancel" href="/lophoc/index">H&#7911;y b&#7887;</a>
        </div>
    </form>
</section>
