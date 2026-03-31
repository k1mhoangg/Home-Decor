<div class="tab-pane <?php echo $activeTabId === 'tab-decor-images' ? 'active show' : ''; ?>" id="tab-decor-images">
    <div class="mb-3">
        <a href="/admin/about/decor/create" class="btn btn-outline-primary">
            <i class="ti ti-plus"></i> Thêm ảnh mới
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-vcenter">
            <thead>
                <tr>
                    <th>Thứ tự</th>
                    <th>Ảnh</th>
                    <th>URL</th>
                    <th class="text-end">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($decorImages)): ?>
                    <?php foreach ($decorImages as $img): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($img['display_order']); ?></td>
                            <td>
                                <img src="<?php echo htmlspecialchars($img['image_url_thumb'] ?? $img['image_url']); ?>"
                                     style="width:100px;height:60px;object-fit:cover;border-radius:4px">
                            </td>
                            <td><small><?php echo htmlspecialchars($img['image_url']); ?></small></td>
                            <td class="text-end">
                                <a href="/admin/about/decor/edit?id=<?php echo urlencode($img['id']); ?>"
                                   class="btn btn-sm btn-outline-secondary">Sửa</a>
                                <form method="POST" action="/admin/about/delete-decor-image" style="display:inline-block"
                                      onsubmit="return confirm('Xóa ảnh này?');">
                                    <input type="hidden" name="tab" value="decor-images">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($img['id']); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Chưa có ảnh (cần ít nhất 2 ảnh)</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


