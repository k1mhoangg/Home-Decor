<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($news['title']) ?> | HomeDecor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="/css/style.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php require BASE_PATH . '/components/header.php'; ?>
    <main class="container mx-auto py-10 max-w-3xl">
        <!-- Add return button -->
        <a href="/news" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
        <?php if (!empty($news['thumbnail'])): ?>
            <div class="mb-6">
                <img src="/<?= htmlspecialchars($news['thumbnail']) ?>" alt="<?= htmlspecialchars($news['title']) ?>"
                    class="w-full h-64 object-cover rounded mb-4" loading="lazy">
            </div>
        <?php endif; ?>
        <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($news['title']) ?></h1>
        <div class="text-gray-500 text-sm mb-6">Ngày đăng: <?= htmlspecialchars($news['created_at']) ?></div>
        <div class="mb-8 text-base leading-relaxed"><?= nl2br(htmlspecialchars($news['content'])) ?></div>

        <section class="mt-10">
            <h2 class="text-xl font-semibold mb-4">Bình luận</h2>
            <?php if (!empty($comments)): ?>
                <div class="space-y-4 mb-6">
                    <?php foreach ($comments as $c): ?>
                        <div class="bg-gray-100 rounded p-3">
                            <div class="font-semibold text-gray-700 mb-1"><?= htmlspecialchars($c['author']) ?></div>
                            <div class="text-gray-600 text-sm mb-1"><?= nl2br(htmlspecialchars($c['content'])) ?></div>
                            <div class="text-xs text-gray-400"><?= htmlspecialchars($c['created_at']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-gray-400 mb-4">Chưa có bình luận nào.</div>
            <?php endif; ?>
            <?php if (isset($_SESSION['user'])): ?>
                <form id="commentForm" class="bg-white rounded shadow p-4">
                    <div class="mb-2">
                        <textarea name="content" class="form-control w-full" placeholder="Nội dung bình luận"
                            required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi bình luận</button>
                </form>
            <?php else: ?>
                <div class="text-red-500 mb-4">Bạn cần <a href="/login" class="underline">đăng nhập</a> để bình luận.</div>
            <?php endif; ?>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var form = document.getElementById('commentForm');
                    if (form) {
                        form.addEventListener('submit', function (e) {
                            e.preventDefault();
                            var content = form.content.value.trim();
                            if (!content) return;
                            var btn = form.querySelector('button[type="submit"]');
                            btn.disabled = true;
                            fetch('/news/<?= (int) $news['id'] ?>/comment', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                body: 'content=' + encodeURIComponent(content)
                            })
                                .then(res => res.json())
                                .then(data => {
                                    btn.disabled = false;
                                    if (data.success && data.author && data.content && data.created_at) {
                                        var commentsDiv = document.querySelector('.space-y-4');
                                        if (!commentsDiv) {
                                            commentsDiv = document.createElement('div');
                                            commentsDiv.className = 'space-y-4 mb-6';
                                            form.parentNode.insertBefore(commentsDiv, form);
                                        }
                                        var c = document.createElement('div');
                                        c.className = 'bg-gray-100 rounded p-3';
                                        c.innerHTML = `<div class="font-semibold text-gray-700 mb-1">${data.author}</div><div class="text-gray-600 text-sm mb-1">${data.content.replace(/\n/g, '<br>')}</div><div class="text-xs text-gray-400">${data.created_at}</div>`;
                                        if (commentsDiv.firstChild) {
                                            commentsDiv.insertBefore(c, commentsDiv.firstChild);
                                        } else {
                                            commentsDiv.appendChild(c);
                                        }
                                        form.reset();
                                    } else {
                                        alert(data.message || 'Có lỗi xảy ra.');
                                    }
                                })
                                .catch(() => { btn.disabled = false; alert('Có lỗi xảy ra.'); });
                        });
                    }
                });
            </script>
        </section>
    </main>
    <?php require BASE_PATH . '/components/footer.php'; ?>
</body>

</html>