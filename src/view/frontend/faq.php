<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HomeDecor | Hỏi/Đáp</title>

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

    <!-- DISPLAY FLASH MESSAGES -->
    <?php
    use Core\Session;

    Session::start();
    $flash = Session::getFlash();
    if ($flash) {
        $cls = ($flash['type'] === 'success') ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300';
        echo '<div class="max-w-5xl mx-auto mt-6 px-6"><div class="p-4 rounded-lg border ' . $cls . '">' . htmlspecialchars($flash['message']) . '</div></div>';
    }
    ?>

    <?php require BASE_PATH . '/components/faq/tab.php'; ?>

    <!-- Tab Content -->
    <?php if ($currentTab === 'ask'): ?>
        <?php require BASE_PATH . '/components/faq/faq-form.php'; ?>
    <?php else: ?>
        <?php
        $isMyQuestionsTab = isset($isMyQuestions) && $isMyQuestions;
        require BASE_PATH . '/components/faq/faq-list.php';
        ?>
    <?php endif; ?>

    <?php require BASE_PATH . '/components/footer.php'; ?>

    <script src="js/main.js"></script>
</body>

</html>