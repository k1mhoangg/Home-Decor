<div class="tab-pane <?php echo $activeTabId === 'tab-settings' ? 'active show' : ''; ?>" id="tab-settings">
    <form method="POST" action="/admin/about/save-settings" enctype="multipart/form-data">
        <input type="hidden" name="tab" value="settings">
        <!-- Banner Image -->
        <div class="mb-3">
            <small class="form-hint d-block mb-2 text-danger">
                <!-- <p><strong>Chú ý:</strong> Tách đoạn bằng dấu xuống dòng</p> -->
                <p><strong>Yêu cầu:</strong> Ảnh phải có kích thước tối thiểu 1200x600px</p>
            </small>
            <label class="form-label">Ảnh Banner</label>
            <?php if (!empty($settings['banner_image'])): ?>
                <div class="mb-2">
                    <img src="<?php echo htmlspecialchars($settings['banner_image_thumb'] ?? $settings['banner_image']); ?>"
                         style="max-width:300px;max-height:200px;object-fit:cover;border-radius:6px">
                </div>
            <?php endif; ?>
            <input type="file" name="banner_image" id="banner_image_input" accept="image/*" class="form-control" />
            <div id="banner_image_error" class="text-danger mt-2" style="display:none;"></div>
        </div>

        <!-- Intro Content -->
        <div class="mb-3">
            <label class="form-label">Nội dung phần Intro</label>
            <textarea name="intro_content" rows="12" class="form-control" required><?php echo htmlspecialchars($settings['intro_content'] ?? ''); ?></textarea>
        </div>

        <!-- Vision Image -->
        <div class="mb-3">
            <label class="form-label">Ảnh Tầm nhìn - Sứ mệnh - Giá trị</label>
            <?php if (!empty($settings['vision_image'])): ?>
                <div class="mb-2">
                    <img src="<?php echo htmlspecialchars($settings['vision_image_thumb'] ?? $settings['vision_image']); ?>"
                         style="max-width:300px;max-height:200px;object-fit:cover;border-radius:6px">
                </div>
            <?php endif; ?>
            <input type="file" name="vision_image" id="vision_image_input" accept="image/*" class="form-control" />
            <div id="vision_image_error" class="text-danger mt-2" style="display:none;"></div>
        </div>

        <!-- Vision Content -->
        <div class="mb-3">
            <label class="form-label">Nội dung Tầm nhìn</label>
            <textarea name="vision_content" rows="4" class="form-control" required><?php echo htmlspecialchars($settings['vision_content'] ?? ''); ?></textarea>
        </div>

        <!-- Mission Content -->
        <div class="mb-3">
            <label class="form-label">Nội dung Sứ mệnh</label>
            <textarea name="mission_content" rows="4" class="form-control" required><?php echo htmlspecialchars($settings['mission_content'] ?? ''); ?></textarea>
        </div>

        <!-- Values Content -->
        <div class="mb-3">
            <label class="form-label">Nội dung Giá trị cốt lõi</label>
            <textarea name="values_content" rows="4" class="form-control" required><?php echo htmlspecialchars($settings['values_content'] ?? ''); ?></textarea>
        </div>

        <!-- Decor Content -->
        <div class="mb-3">
            <label class="form-label">Nội dung Nội thất trang trí</label>
            <textarea name="decor_content" rows="12" class="form-control" required><?php echo htmlspecialchars($settings['decor_content'] ?? ''); ?></textarea>
        </div>

        <!-- Statistics -->
        <h4 class="mt-4 mb-3">Thống kê</h4>
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Khách hàng đã tư vấn</label>
                <input type="number" name="customers_target" value="<?php echo htmlspecialchars($settings['customers_target'] ?? 986); ?>" class="form-control" required />
            </div>
            <div class="col-md-3">
                <label class="form-label">Năm kinh nghiệm</label>
                <input type="number" name="years_target" value="<?php echo htmlspecialchars($settings['years_target'] ?? 10); ?>" class="form-control" required />
            </div>
            <div class="col-md-3">
                <label class="form-label">Dự án đã triển khai</label>
                <input type="number" name="projects_target" value="<?php echo htmlspecialchars($settings['projects_target'] ?? 300); ?>" class="form-control" required />
            </div>
            <div class="col-md-3">
                <label class="form-label">Khách hàng thân thiết</label>
                <input type="number" name="loyal_customers_target" value="<?php echo htmlspecialchars($settings['loyal_customers_target'] ?? 50); ?>" class="form-control" required />
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Lưu cài đặt</button>
        </div>
    </form>
</div>


