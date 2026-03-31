<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HomeDecor | Giới thiệu</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Optional local CSS (kept) -->
  <link rel="stylesheet" href="css/style.css" />

  <!-- Use official Tailwind CDN for utility classes -->
  <script src="https://cdn.tailwindcss.com"></script>


</head>

<body>
  <!-- HEADER -->
  <?php require BASE_PATH . '/components/header.php'; ?>

  <?php require BASE_PATH . '/components/about/banner.php'; ?>

  <?php require BASE_PATH . '/components/about/intro.php'; ?>

  <?php require BASE_PATH . '/components/about/vision-mission.php'; ?>

  <?php require BASE_PATH . '/components/about/decor.php'; ?>

  <?php require BASE_PATH . '/components/about/advantage.php'; ?>

  <?php require BASE_PATH . '/components/about/statistics.php'; ?>

  <!-- FOOTER -->
  <?php require BASE_PATH . '/components/footer.php'; ?>

  <script src="js/carousel.js"></script>
  <script src="js/count-up-animation.js"></script>
  <script src="js/main.js"></script>
</body>

</html>