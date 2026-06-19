<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'QLSV', ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        min-height: 100vh;
        background: #f6f7fb;
        color: #172033;
        font-family: Arial, sans-serif;
    }

    a {
        transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
    }

    .site-header {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        box-shadow: 0 1px 8px rgba(15, 23, 42, 0.06);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .site-header__inner,
    .site-footer__inner {
        width: min(1120px, calc(100% - 32px));
        margin: 0 auto;
    }

    .site-header__inner {
        min-height: 72px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }

    .site-brand {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #111827;
        text-decoration: none;
    }

    .site-brand__mark {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        background: #4f46e5;
        color: #ffffff;
        font-weight: 700;
    }

    .site-brand__text {
        display: grid;
        gap: 2px;
    }

    .site-brand__text small {
        color: #64748b;
        font-size: 12px;
    }

    .site-nav {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .site-nav a,
    .site-user a {
        border-radius: 6px;
        color: #334155;
        font-size: 14px;
        font-weight: 600;
        padding: 9px 12px;
        text-decoration: none;
    }

    .site-nav a:hover,
    .site-nav a.active,
    .site-user a:hover {
        background: #eef2ff;
        color: #4338ca;
    }

    .site-user {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #475569;
        font-size: 14px;
        white-space: nowrap;
    }

    .content {
        width: min(1120px, calc(100% - 32px));
        margin: 28px auto 44px;
    }

    .site-footer {
        background: #111827;
        color: #cbd5e1;
        margin-top: 48px;
        padding: 28px 0;
    }

    .site-footer__inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }

    .site-footer strong {
        color: #ffffff;
    }

    .site-footer p {
        margin-top: 6px;
        font-size: 14px;
    }

    .site-footer__links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .site-footer__links a {
        color: #e0e7ff;
        font-size: 14px;
        text-decoration: none;
    }

    .site-footer__links a:hover {
        color: #ffffff;
        text-decoration: underline;
    }

    @media (max-width: 760px) {
        .site-header__inner,
        .site-footer__inner {
            align-items: stretch;
            flex-direction: column;
            padding: 14px 0;
        }

        .site-nav,
        .site-user {
            flex-wrap: wrap;
        }

        .site-nav a,
        .site-user a {
            background: #f1f5f9;
        }
    }
</style>
<body>
    <?php require_once __DIR__ . '/partial/header.php'; ?>

    <main class="content">
        <?php require_once __DIR__ . '/../' . $viewname . '.php'; ?>
    </main>

    <?php require_once __DIR__ . '/partial/footer.php'; ?>
</body>
</html>
