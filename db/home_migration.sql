-- Migration: Tạo bảng home_settings, home_slides, home_banners
USE HomeDecor;

-- Bảng cài đặt trang chủ
CREATE TABLE IF NOT EXISTS home_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Hero section
    hero_title VARCHAR(255) DEFAULT 'Thiết Kế Nội Thất Cao Cấp',
    hero_subtitle TEXT DEFAULT 'Mang đến không gian sống hoàn hảo cho gia đình bạn',
    hero_button_text VARCHAR(100) DEFAULT 'Khám phá ngay',
    hero_button_link VARCHAR(255) DEFAULT '/about',
    
    -- Carousel section
    show_carousel TINYINT(1) DEFAULT 1,
    
    -- Featured products section
    featured_title VARCHAR(255) DEFAULT 'SẢN PHẨM NỔI BẬT',
    featured_subtitle TEXT,
    show_featured TINYINT(1) DEFAULT 1,
    featured_product_ids TEXT DEFAULT NULL,
    
    -- Recent products section
    recent_title VARCHAR(255) DEFAULT 'SẢN PHẨM MỚI NHẤT',
    recent_subtitle TEXT,
    show_recent TINYINT(1) DEFAULT 1,
    recent_product_ids TEXT DEFAULT NULL,
    
    -- Banner section
    show_banner TINYINT(1) DEFAULT 1,
    
    -- Categories section
    show_categories TINYINT(1) DEFAULT 1,
    categories_title VARCHAR(255) DEFAULT 'DANH MỤC SẢN PHẨM',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng slides carousel
CREATE TABLE IF NOT EXISTS home_slides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NULL,
    image_url VARCHAR(500) NOT NULL,
    title VARCHAR(255) NULL,
    subtitle VARCHAR(255) NULL,
    button_text VARCHAR(100) NULL,
    button_link VARCHAR(255) NULL,
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng banner panels
CREATE TABLE IF NOT EXISTS home_banners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NULL,
    image_url VARCHAR(500) NOT NULL,
    title VARCHAR(255) NULL,
    link VARCHAR(255) DEFAULT '/',
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings if not exists
INSERT INTO home_settings (id) 
SELECT 1 FROM DUAL 
WHERE NOT EXISTS (SELECT 1 FROM home_settings WHERE id = 1);

-- Insert sample slides if empty
INSERT INTO home_slides (image_url, title, subtitle, display_order, is_active)
SELECT * FROM (
    SELECT 'images/living_room.jpg' as image_url, 'Thiết kế phòng khách' as title, 'Phong cách hiện đại, tối giản' as subtitle, 1 as display_order, 1 as is_active
    UNION ALL
    SELECT 'images/bed_room.jpg', 'Phòng ngủ ấm cúng', 'Tối ưu ánh sáng và tiện nghi', 2, 1
    UNION ALL
    SELECT 'images/dining_room.jpg', 'Khu vực ăn uống', 'Trang trí tinh tế cho bữa cơm gia đình', 3, 1
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM home_slides);

-- Insert sample banners if empty
INSERT INTO home_banners (image_url, title, link, display_order, is_active)
SELECT * FROM (
    SELECT 'images/living_room.jpg' as image_url, 'Living Room' as title, '/pricing' as link, 1 as display_order, 1 as is_active
    UNION ALL
    SELECT 'images/bed_room.jpg', 'Bedroom', '/pricing', 2, 1
    UNION ALL
    SELECT 'images/dining_room.jpg', 'Dining Area', '/pricing', 3, 1
    UNION ALL
    SELECT 'images/workspace.jpg', 'Workspace', '/pricing', 4, 1
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM home_banners);
