<?php include __DIR__ . '/partial/header.php'; ?>

<?php
if (class_exists(\Core\Session::class))
    $flash = \Core\Session::getFlash();
else {
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    $flash = $_SESSION['flash'] ?? null;
    if ($flash)
        unset($_SESSION['flash']);
}

if (empty($faq)) {
    echo "<div class='container-xl'><div class='alert alert-danger'>Không tìm thấy câu hỏi.</div></div>";
    include __DIR__ . '/partial/footer.php';
    return;
}

$isAnswering = isset($isAnswering) ? $isAnswering : false;
?>

<?php include __DIR__ . '/partial/navbar.php'; ?>

<div class="page">
    <div class="container-xl">
        <div class="row mb-3 page-header">
            <div class="col">
                <h2 class="page-title"><?php echo $isAnswering ? 'Trả lời câu hỏi' : 'Chỉnh sửa câu hỏi/đáp'; ?></h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="/admin/faqs" class="btn btn-outline-secondary">Quay lại</a>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="<?php echo ($flash['type'] === 'success') ? 'alert alert-success' : 'alert alert-danger'; ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/faqs/save">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($faq->getId()); ?>">

                    <div class="mb-3">
                        <label class="form-label">Câu hỏi</label>
                        <textarea name="question" rows="4" class="form-control" required><?php echo htmlspecialchars($faq->getQuestion()); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Câu trả lời</label>
                        <textarea name="answer" rows="8" class="form-control" <?php echo $isAnswering ? 'required' : ''; ?>><?php echo htmlspecialchars($faq->getAnswer() ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái hiển thị</label>
                        <select name="is_published" class="form-select">
                            <option value="1" <?php echo $faq->getIsPublished() ? 'selected' : ''; ?>>Hiển thị</option>
                            <option value="0" <?php echo !$faq->getIsPublished() ? 'selected' : ''; ?>>Không hiển thị</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <a href="/admin/faqs" class="btn btn-link">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/partial/footer.php'; ?>

