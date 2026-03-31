<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ | HomeDecor</title>

    <!-- External stylesheets -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="">
    <!-- Navigation header component -->
    <?php include '../components/header.php'; ?>

    <?php
    // Hiển thị flash message nếu có (PRG)
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        $cls = ($f['type'] === 'success') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        echo '<div class="max-w-4xl mx-auto mt-4 p-3 rounded ' . $cls . '">' . htmlspecialchars($f['message']) . '</div>';
        unset($_SESSION['flash']);
    }
    ?>

    <!-- Contact information section -->
    <?php include '../components/contact/info.php'; ?>

    <!-- Contact form section -->
    <?php include '../components/contact/form.php'; ?>

    <!-- Website footer component -->
    <?php include '../components/footer.php'; ?>

</body>

</html>