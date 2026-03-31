<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HomeDecor | Bảng giá</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Optional local CSS (kept) -->

  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/pricing.css" />

  <!-- Use official Tailwind CDN for utility classes -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <!-- HEADER -->
  <?php require BASE_PATH . '/components/header.php'; ?>

  <main class="container mx-auto py-10">
    <h1 class="text-3xl font-bold text-center mb-8">Bảng giá sản phẩm</h1>
    <?php require BASE_PATH . '/components/pricing/table.php'; ?>
    <?php require BASE_PATH . '/view/frontend/partial/pagination.php'; ?>
  </main>

  <!-- FOOTER -->
  <?php require BASE_PATH . '/components/footer.php'; ?>
  <script src="js/carousel.js"></script>
  <script src="js/main.js"></script>
</body>

</html>
