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

if (empty($contact)) {
    echo "<div class='container-xl'><div class='alert alert-danger'>Không tìm thấy liên hệ.</div></div>";
    include __DIR__ . '/partial/footer.php';
    return;
}
?>

<?php include __DIR__ . '/partial/navbar.php'; ?>

<div class="page mt-3">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Liên hệ #<?php echo htmlspecialchars($contact->getId()); ?></h3>
                        <div class="card-actions">
                            <a href="/admin/contacts" class="btn btn-sm">Quay lại</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p><strong>Tên:</strong> <?php echo htmlspecialchars($contact->getName()); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($contact->getEmail()); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($contact->getPhone()); ?></p>
                        <p><strong>Subject:</strong> <?php echo htmlspecialchars($contact->getSubject()); ?></p>
                        <hr>
                        <div><?php echo nl2br(htmlspecialchars($contact->getMessage())); ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Trạng thái</h3>
                    </div>
                    <div class="card-body">
                        <!-- Reply form -->
                        <form method="POST" action="/admin/contacts/reply">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($contact->getId()); ?>">
                            <div class="mb-2">
                                <label class="form-label">Gửi trả lời tới:
                                    <?php echo htmlspecialchars($contact->getEmail() ?: '—'); ?></label>
                                <textarea name="reply_message" rows="6" class="form-control"
                                    placeholder="Nội dung trả lời" required></textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary">Gửi trả lời</button>
                                <a href="/admin/contacts" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </form>

                        <hr>

                        <!-- status update -->
                        <form method="POST" action="/admin/contacts/status" class="mt-3">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($contact->getId()); ?>">
                            <div class="mb-2">
                                <select name="status" class="form-select">
                                    <?php foreach (['pending', 'replied', 'closed'] as $s): ?>
                                        <option value="<?php echo $s; ?>" <?php echo ($contact->getStatus() === $s) ? 'selected' : ''; ?>><?php echo $s; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button class="btn btn-outline-primary">Cập nhật trạng thái</button>
                        </form>

                        <hr>

                        <form method="POST" action="/admin/contacts/delete" onsubmit="return confirm('Xóa liên hệ?');">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($contact->getId()); ?>">
                            <button class="btn btn-danger">Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/partial/footer.php'; ?>