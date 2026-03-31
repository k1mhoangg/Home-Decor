<?php
// Only show data if this is the active tab
$showData = ($activeTabId === 'tab-unanswered');
$tabFaqs = $showData ? ($faqs ?? []) : [];
$tabPagination = $showData ? ($pagination ?? null) : null;
$search = $search ?? (isset($_GET['search']) ? trim($_GET['search']) : '');
?>

<div class="tab-pane <?php echo $activeTabId === 'tab-unanswered' ? 'active show' : ''; ?>" id="tab-unanswered">
    <!-- Search Form -->
    <div class="mb-3">
        <form method="GET" action="/admin/faqs" class="d-flex gap-2">
            <input type="hidden" name="tab" value="unanswered">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                   class="form-control" placeholder="Tìm kiếm theo từ khóa câu hỏi...">
            <button type="submit" class="btn btn-outline-primary">Tìm kiếm</button>
            <?php if (!empty($search)): ?>
                <a href="/admin/faqs?tab=unanswered" class="btn btn-outline-secondary">Xóa</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-vcenter">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Câu hỏi</th>
                    <th>Ngày tạo</th>
                    <th class="text-end">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tabFaqs)): ?>
                    <?php foreach ($tabFaqs as $faq): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($faq->getId()); ?></td>
                            <td>
                                <div style="max-width: 400px; overflow: hidden; text-overflow: ellipsis;">
                                    <?php echo htmlspecialchars($faq->getQuestion()); ?>
                                </div>
                            </td>
                            <td><?php echo formatVietnameseDate($faq->getCreatedAt()); ?></td>
                            <td class="text-end">
                                <a href="/admin/faqs/edit?id=<?php echo urlencode($faq->getId()); ?>" 
                                   class="btn btn-sm btn-outline-primary">Trả lời</a>
                                <form method="POST" action="/admin/faqs/delete" style="display:inline-block"
                                      onsubmit="return confirm('Xóa câu hỏi này?');">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($faq->getId()); ?>">
                                    <input type="hidden" name="tab" value="unanswered">
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Không có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($tabPagination && $tabPagination->hasPages()): ?>
        <?php include __DIR__ . '/partial/pagination.php'; ?>
    <?php endif; ?>
</div>

