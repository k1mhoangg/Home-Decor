<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Giỏ hàng của bạn | HomeDecor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/cart.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php require BASE_PATH . '/components/header.php'; ?>
    <main class="container mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6 text-center">Giỏ hàng của bạn</h1>
        <?php require BASE_PATH . '/components/cart/table.php'; ?>
    </main>
    <?php require BASE_PATH . '/components/footer.php'; ?>
    <script src="/js/main.js"></script>
</body>

</html>