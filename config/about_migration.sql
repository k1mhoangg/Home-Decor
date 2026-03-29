-- ==========================================
-- TABLE: about_settings (chỉ 1 record)
-- ==========================================
CREATE TABLE IF NOT EXISTS about_settings (
    id INT PRIMARY KEY DEFAULT 1,
    banner_image VARCHAR(255) DEFAULT 'images/sample.jpg',
    banner_image_thumb VARCHAR(255) NULL,
    intro_content TEXT,
    vision_image VARCHAR(255) DEFAULT 'images/sample.jpg',
    vision_image_thumb VARCHAR(255) NULL,
    vision_content TEXT,
    mission_content TEXT,
    values_content TEXT,
    decor_content TEXT,
    customers_target INT DEFAULT 986,
    years_target INT DEFAULT 10,
    projects_target INT DEFAULT 300,
    loyal_customers_target INT DEFAULT 50,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_single_record CHECK (id = 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chèn hoặc cập nhật record mặc định
INSERT INTO about_settings 
(id, intro_content, vision_content, mission_content, values_content, decor_content)
VALUES
(1, 
'HomeDecor là công ty nội thất hoạt động chuyên nghiệp trong lĩnh vực thiết kế nội thất và thi công nội thất trọn gói khắp từ Bắc tới Nam bao gồm cả Hà Nội, TP. Hồ Chí Minh và các tỉnh thành khác trên cả nước. Các lĩnh vực chúng tôi thiết kế và thi công bao gồm văn phòng, nhà chung cư, nhà phố, liền kề, biệt thự, trường học… Công ty nội thất HomeDecor luôn nỗ lực không ngừng để tạo ra các công trình đảm bảo thẩm mỹ, tối ưu không gian, tiết kiệm chi phí, bền vững và hài hòa về phong thủy; tạo ra các sản phẩm nội thất sáng tạo và tinh tế đáp ứng mọi yêu cầu của quý khách. Với đội ngũ kiến trúc sư giàu kinh nghiệm, yêu nghề cùng đội ngũ thợ thi công lành nghề và trách nhiệm, chúng tôi chắc chắn sẽ khiến bạn hài lòng khi sở hữu một công trình nội thất hiện đại, cấu trúc thông minh hoàn hảo và chi phí đầu tư hợp lý nhất.',
'Trở thành đơn vị tư vấn, cung cấp giải pháp Kiến Trúc – Nội Thất tổng thể hàng đầu trong tâm trí khách hàng ở phân khúc Biệt Thự, Dinh thự cao cấp. Phát triển, lan toả sản phẩm tới các Tỉnh thành lớn của cả nước và định hướng Xuất khẩu tới các nước phát triển trên Thế Giới.',
'Cung cấp giải pháp đồng bộ từ Thiết Kế – Thi Công tới phân Khúc Nôị Thất Cao Cấp đặc biệt Villa, dinh thự. Cung cấp các sản phẩm Nôị Thất độc bản, giới hạn tới các Công trình tiêu biểu. Biến ngôi nhà thành tổ ấm và không gian SỐNG ý nghĩa – NHÀ là nơi để gia đình sum vầy.',
'Lấy con người làm trung tâm để kiến tạo những sản phẩm khác biệt và hoàn hảo nhất cho việc phát triển Doanh nghiệp bền vững. Sự chân thành, tâm huyết, chỉn chu, đạo đức làm nghề và tính sáng tạo của tập thể HomeDecor làm thước đo để phục vụ khách hàng.',
'HomeDecor mang đến những giải pháp nội thất trang trí hiện đại, tinh tế và phù hợp với từng không gian sống. Từ phòng khách, phòng ngủ đến phòng bếp, từng chi tiết đều được chăm chút để tạo nên tổng thể hài hòa và thẩm mỹ. Chúng tôi kết hợp giữa sự sáng tạo trong thiết kế và chất lượng thi công cao cấp, giúp biến những ý tưởng của khách hàng thành hiện thực sống động. Nội thất không chỉ là tiện nghi mà còn là trải nghiệm, tạo cảm giác ấm cúng và sang trọng cho ngôi nhà. Với HomeDecor, mỗi sản phẩm nội thất là một tác phẩm riêng biệt, thể hiện phong cách cá nhân của chủ nhân. Chúng tôi cam kết đem đến sự bền bỉ, tính thẩm mỹ và sự tiện nghi, đồng thời mang đến không gian sống trọn vẹn, tinh tế và đầy cảm hứng.')
ON DUPLICATE KEY UPDATE id = id;

-- ==========================================
-- TABLE: about_decor_images (carousel)
-- ==========================================
CREATE TABLE IF NOT EXISTS about_decor_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_url VARCHAR(255) NOT NULL,
    image_url_thumb VARCHAR(255) NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chèn ảnh mặc định
INSERT INTO about_decor_images (image_url, display_order) VALUES
('images/sample.jpg', 1),
('images/sample.jpg', 2),
('images/sample.jpg', 3);

-- ==========================================
-- TABLE: about_advantages (ưu thế)
-- ==========================================
CREATE TABLE IF NOT EXISTS about_advantages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chèn ưu thế mặc định
INSERT INTO about_advantages (content, display_order) VALUES
('Tư vấn, thiết kế nội thất hợp phong thủy.', 1),
('Tư vấn thiết kế nội thất tối ưu không gian – tối ưu tới từng xentimet.', 2),
('Tối ưu hóa chi phí & chỉnh sửa thiết kế cho tới khi bạn hài lòng.', 3),
('Đội ngũ kỹ thuật & thợ lành nghề, kinh nghiệm từ 2013. Thi công gần 1000 công trình.', 4),
('Nhà máy sản xuất nội thất quy mô 5000m².', 5),
('Máy móc hiện đại, nhập khẩu từ châu Âu.', 6);

-- ==========================================
-- ALTER TABLE: add thumbnail columns (nếu chưa có)
-- ==========================================
ALTER TABLE about_settings 
ADD COLUMN IF NOT EXISTS banner_image_thumb VARCHAR(255) NULL AFTER banner_image,
ADD COLUMN IF NOT EXISTS vision_image_thumb VARCHAR(255) NULL AFTER vision_image;

ALTER TABLE about_decor_images 
ADD COLUMN IF NOT EXISTS image_url_thumb VARCHAR(255) NULL AFTER image_url;
