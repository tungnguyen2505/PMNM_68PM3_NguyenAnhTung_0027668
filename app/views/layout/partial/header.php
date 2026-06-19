<?php
$currentPath = '/' . trim((string)($_GET['url'] ?? 'home/index'), '/');
$username = $_SESSION['username'] ?? '';
$isSinhVienListPage = $currentPath === '/sinhvien/index' || $currentPath === '/sinhvien' || $currentPath === '/home/index';
$isLopHocPage = strpos($currentPath, '/lophoc') === 0;
?>

<header class="site-header">
    <div class="site-header__inner">
        <a class="site-brand" href="/sinhvien/index">
            <span class="site-brand__mark">SV</span>
            <span class="site-brand__text">
                <strong>QLSV</strong>
                <small>Qu&#7843;n l&#253; sinh vi&#234;n</small>
            </span>
        </a>

        <nav class="site-nav" aria-label="&#272;i&#7873;u h&#432;&#7899;ng ch&#237;nh">
            <a class="<?php echo $isSinhVienListPage ? 'active' : ''; ?>" href="/sinhvien/index">Danh s&#225;ch</a>
            <a class="<?php echo $currentPath === '/sinhvien/create' ? 'active' : ''; ?>" href="/sinhvien/create">Th&#234;m sinh vi&#234;n</a>
            <a class="<?php echo $isLopHocPage ? 'active' : ''; ?>" href="/lophoc/index">L&#7899;p h&#7885;c</a>
        </nav>

        <div class="site-user">
            <?php if ($username !== ''): ?>
                <span><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span>
                <a href="/auth/logout">&#272;&#259;ng xu&#7845;t</a>
            <?php else: ?>
                <a href="/home/login">&#272;&#259;ng nh&#7853;p</a>
            <?php endif; ?>
        </div>
    </div>
</header>
