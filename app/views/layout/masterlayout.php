<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
</head>
<style>
    *{
        margin: 0;
        padding: 0;
    }
    .content{
        width : 60%;
        margin: auto;
    }
</style>
<body>
   <div>
    <?php require_once __DIR__ . '/partial/header.php'; ?>
   </div>

   
    <div class="content">
        <?php require_once __DIR__ . '/../' . $viewname . '.php'; ?>
    </div>
    <div>
        <?php require_once __DIR__ . '/partial/footer.php'; ?>
    </div>
</body>
</html>