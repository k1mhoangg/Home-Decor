<div class="tab-pane <?php echo $activeTabId === 'tab-advantages' ? 'active show' : ''; ?>" id="tab-advantages">
    <div class="mb-3">
        <a href="/admin/about/advantages/create" class="btn btn-outline-primary">
            <i class="ti ti-plus"></i> Thêm ưu thế mới
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-vcenter">
            <thead>
                <tr>
                    <th>Thứ tự</th>
                    <th>Nội dung</th>
                    <th class="text-end">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($advantages)): ?>
                    <?php foreach ($advantages as $adv): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($adv['display_order']); ?></td>
                            <td><?php echo htmlspecialchars($adv['content']); ?></td>
                            <td class="text-end">
                                <a href="/admin/about/advantages/edit?id=<?php echo urlencode($adv['id']); ?>"
                                   class="btn btn-sm btn-outline-secondary">Sửa</a>
                                <form method="POST" action="/admin/about/delete-advantage" style="display:inline-block"
                                      onsubmit="return confirm('Xóa ưu thế này?');">
                                    <input type="hidden" name="tab" value="advantages">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($adv['id']); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Chưa có ưu thế</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


