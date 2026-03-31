function validateImageSize(file, minWidth, minHeight, errorElementId, imageName) {
    return new Promise((resolve, reject) => {
        const errorElement = document.getElementById(errorElementId);
        if (!file) {
            errorElement.style.display = 'none';
            resolve(true);
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                const width = img.width;
                const height = img.height;
                
                if (width < minWidth || height < minHeight) {
                    const imageLabel = imageName || 'Ảnh';
                    errorElement.textContent = `${imageLabel}: Ảnh phải có kích thước tối thiểu ${minWidth}x${minHeight}px. Ảnh hiện tại: ${width}x${height}px.`;
                    errorElement.style.display = 'block';
                    resolve(false);
                } else {
                    errorElement.style.display = 'none';
                    resolve(true);
                }
            };
            img.onerror = function() {
                const imageLabel = imageName || 'Ảnh';
                errorElement.textContent = `${imageLabel}: Không thể đọc file ảnh. Vui lòng chọn file ảnh hợp lệ.`;
                errorElement.style.display = 'block';
                resolve(false);
            };
            img.src = e.target.result;
        };
        reader.onerror = function() {
            const imageLabel = imageName || 'Ảnh';
            errorElement.textContent = `${imageLabel}: Không thể đọc file. Vui lòng thử lại.`;
            errorElement.style.display = 'block';
            resolve(false);
        };
        reader.readAsDataURL(file);
    });
}

// Activate tab from URL query string on page load
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    if (tab) {
        const tabMap = {
            'settings': '#tab-settings',
            'decor-images': '#tab-decor-images',
            'advantages': '#tab-advantages'
        };
        const tabSelector = tabMap[tab];
        if (tabSelector) {
            // Remove active class from all tabs first
            document.querySelectorAll('.nav-link[data-bs-toggle="tab"]').forEach(link => {
                link.classList.remove('active');
            });
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active', 'show');
            });
            
            // Activate the target tab
            const tabElement = document.querySelector(`a[href="${tabSelector}"]`);
            const tabPane = document.querySelector(tabSelector);
            if (tabElement && tabPane) {
                tabElement.classList.add('active');
                tabPane.classList.add('active', 'show');
                // Also use Bootstrap's Tab API to ensure proper activation
                const tabInstance = new bootstrap.Tab(tabElement);
                tabInstance.show();
            }
        }
    }

    // Validate banner image
    const bannerInput = document.getElementById('banner_image_input');
    if (bannerInput) {
        bannerInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                validateImageSize(file, 1200, 600, 'banner_image_error', 'Ảnh Banner');
            }
        });
    }

    // Validate vision image
    const visionInput = document.getElementById('vision_image_input');
    if (visionInput) {
        visionInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                validateImageSize(file, 1200, 600, 'vision_image_error', 'Ảnh Tầm nhìn - Sứ mệnh - Giá trị');
            }
        });
    }

    // Validate decor image 
    const decorInput = document.getElementById('decor_image_file');
    if (decorInput) {
        decorInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                validateImageSize(file, 1200, 600, 'decor_image_error', 'Ảnh nội thất trang trí');
            }
        });
    }
});