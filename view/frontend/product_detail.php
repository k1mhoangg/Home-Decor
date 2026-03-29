<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chi tiết sản phẩm | HomeDecor</title>
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="/css/product_detail.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php require BASE_PATH . '/components/header.php'; ?>
    <main class="container mx-auto py-10">
        <a href="/pricing" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
        <?php require BASE_PATH . '/components/product/detail_card.php'; ?>
    </main>
    <?php require BASE_PATH . '/components/footer.php'; ?>
    <script src="/js/main.js"></script>
</body>

</html>