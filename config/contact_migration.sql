-- Bảng cài đặt trang liên hệ
CREATE TABLE IF NOT EXISTS contact_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Thông tin công ty
    company_name VARCHAR(255) DEFAULT 'Công Ty Cổ Phần Kiến Trúc Nội Thất Home Decor',
    company_short_name VARCHAR(100) DEFAULT 'HOME DECOR VIỆT NAM',
    company_slogan VARCHAR(255) DEFAULT 'Homedecorvn',
    
    -- Nội dung giới thiệu
    intro_content TEXT,
    activities_content TEXT,
    services_content TEXT,
    
    -- Thông tin liên hệ
    hotline VARCHAR(50) DEFAULT '0904706666',
    hotline_2 VARCHAR(50) DEFAULT '0904830050',
    email VARCHAR(100) DEFAULT 'homedecor0383@gmail.com',
    address TEXT DEFAULT 'Trường Đại học Bách Khoa – ĐHQG‑HCM, 268 Lý Thường Kiệt, Phường 14, Quận 10, TP. Hồ Chí Minh',
    
    -- Social links
    facebook_url VARCHAR(255),
    google_url VARCHAR(255),
    pinterest_url VARCHAR(255),
    
    -- Working hours
    working_hours VARCHAR(255) DEFAULT '8:00 - 17:30 (Thứ 2 - Thứ 7)',
    
    -- Map - địa chỉ và embed tự động tạo
    map_address VARCHAR(500) DEFAULT 'Trường Đại học Bách Khoa – ĐHQG‑HCM, 268 Lý Thường Kiệt, Phường 14, Quận 10, TP. Hồ Chí Minh',
    map_embed TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert dữ liệu mặc định
INSERT INTO contact_settings (id, intro_content, activities_content, services_content, address, map_address) VALUES (1, 
'Công ty cổ phần kiến trúc nội thất Home Decor là một trong số những công ty nội thất hàng đầu Việt Nam hoạt động trên những lĩnh vực chính như: Thiết kế kiến trúc, thiết kế nội thất, xây dựng và trang trí nội thất, được khách hàng tin tưởng và luôn được đánh giá là địa chỉ vàng cho những dịch vụ xây dựng nơi đem lại chất lượng tốt nhất và phục vụ khách hàng chu đáo nhất, đáp ứng mọi nhu cầu của khách hàng.

HomeDecorvn được thành lập từ năm 2011 với lòng yêu nghề và tâm huyết của những kĩ sư trẻ và đội ngũ nhân viên lành nghề. Kiến trúc Homedecor đã không ngừng đổi mới, phát triển, nhanh chóng khẳng định được thương hiệu công ty nội thất uy tín và phong cách thiết kế của riêng mình.

Cho đến nay, kiến trúc Home Decor được biết đến trên thị trường như một trong những công ty nội thất uy tín trong lĩnh vực thiết kế và thi công nội thất hàng đầu tại Việt Nam.

Đến với HOMEDECORVN, quý khách hàng sẽ được cung cấp một gói dịch vụ toàn diện trong lĩnh vực thiết kế và trang trí nội thất nhà ở, văn phòng, nội thất khách sạn, hay các showroom.',

'Tư vấn thiết kế nội thất, ngoại thất, cảnh quan xung quanh công trình.
Tư vấn, thiết kế hạ tầng kĩ thuật đô thị, xây dựng các công trình cấp thoát nước, công trình thủy điện, hệ thống điện dân dụng và công nghiệp.
Tư vấn thiết kế kiến trúc, kết cấu các công trình dân dụng và công nghiệp.
Tư vấn, cung cấp đồ dùng nội thất, vật liệu xây dựng.
Thi công xây dựng công trình dân dụng và công nghiệp.
Tư vấn, khảo sát địa chất công trình.',

'Tư vấn quản lý dự án, quản lý xây dựng (tư vấn giám sát) công trình dân dụng và công nghiệp.
Môi giới, đấu giá bất động sản; Kinh doanh nhà ở, bất động sản.
Sản xuất và buôn bán vật liệu xây dựng, trang thiết bị nội ngoại thất.
Cho thuê thiết bị xây dựng hoặc thiết bị cho các công trình xây dựng, phá dỡ có người điều khiển.
Sản xuất và buôn bán giường, tủ, bàn ghế, sản xuất các sản phẩm đồ gỗ khác.',

'Trường Đại học Bách Khoa – ĐHQG‑HCM, 268 Lý Thường Kiệt, Phường 14, Quận 10, TP. Hồ Chí Minh',

'Trường Đại học Bách Khoa – ĐHQG‑HCM, 268 Lý Thường Kiệt, Phường 14, Quận 10, TP. Hồ Chí Minh'
);
