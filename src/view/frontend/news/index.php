<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tin tức | HomeDecor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="/css/style.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php require BASE_PATH . '/components/header.php'; ?>
    <main class="container mx-auto py-10">
        <h1 class="text-3xl font-bold text-center mb-8">Tin tức mới nhất</h1>
        <form method="get" action="/news" class="max-w-xl mx-auto mb-8 flex gap-2">
            <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                class="form-input flex-1 px-4 py-2 border rounded" placeholder="Tìm theo tiêu đề...">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Tìm kiếm</button>
        </form>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($newsList as $news): ?>
                <div class="bg-white rounded-lg shadow p-5 flex flex-col">
                    <a href="/news/<?= htmlspecialchars($news['id']) ?>" class="block mb-3">
                        <?php if (!empty($news['thumbnail'])): ?>
                            <img src="/<?= htmlspecialchars($news['thumbnail']) ?>"
                                alt="<?= htmlspecialchars($news['title']) ?>" class="w-full h-40 object-cover rounded mb-3"
                                loading="lazy">
                        <?php endif; ?>
                        <h2 class="text-xl font-semibold text-gray-800 hover:text-blue-600 transition-colors line-clamp-2">
                            <?= htmlspecialchars($news['title']) ?>
                        </h2>
                    </a>
                    <div class="text-gray-600 text-sm flex-1 mb-2 line-clamp-3">
                        <?= htmlspecialchars($news['summary'] ?? '') ?>
                    </div>
                    <div class="text-gray-400 text-xs mt-auto">Ngày đăng: <?= htmlspecialchars($news['created_at']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php require BASE_PATH . '/components/footer.php'; ?>
</body>

</html>