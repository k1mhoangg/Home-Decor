-- Create FAQs table with CASCADE
CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NULL,
    user_id INT NOT NULL,
    is_published BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_is_published (is_published),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample FAQs (published)
INSERT INTO faqs (question, answer, user_id, is_published) VALUES
('Thời gian thi công một dự án nội thất thường mất bao lâu?', 'Thời gian thi công phụ thuộc vào quy mô và mức độ phức tạp của dự án. Thông thường, một căn hộ sẽ mất khoảng 20–35 ngày làm việc kể từ khi chốt thiết kế và ký hợp đồng thi công.', 1, 1),
('HomeDecor có hỗ trợ tư vấn và thiết kế miễn phí không?', 'Chúng tôi hỗ trợ tư vấn, khảo sát hiện trạng và đề xuất phương án thiết kế hoàn toàn miễn phí. Phí thiết kế chi tiết sẽ được khấu trừ một phần hoặc toàn bộ vào giá trị hợp đồng thi công (tùy theo gói dịch vụ bạn lựa chọn).', 4, 1),
('Vật liệu nội thất HomeDecor sử dụng có đảm bảo chất lượng không?', 'HomeDecor sử dụng vật liệu từ các thương hiệu uy tín, có chứng nhận nguồn gốc rõ ràng, tiêu chuẩn an toàn và độ bền cao. Bạn sẽ được tư vấn chi tiết từng loại vật liệu phù hợp với ngân sách và nhu cầu sử dụng.', 4, 1),
('HomeDecor có chính sách bảo hành như thế nào?', 'Tùy theo từng hạng mục, sản phẩm nội thất sẽ được bảo hành từ 12–36 tháng. Trong thời gian bảo hành, chúng tôi sẽ hỗ trợ sửa chữa, bảo trì miễn phí theo điều khoản ghi rõ trong hợp đồng.', 4, 1),
('Tôi cần chuẩn bị gì trước khi làm việc với HomeDecor?', 'Bạn chỉ cần cung cấp mặt bằng hiện trạng (bản vẽ nếu có), nhu cầu sử dụng, phong cách yêu thích và mức ngân sách dự kiến. Đội ngũ thiết kế của chúng tôi sẽ đề xuất phương án tối ưu nhất cho không gian sống của bạn.', 4, 1),
('HomeDecor có thiết kế theo yêu cầu không?', 'Có, chúng tôi chuyên thiết kế nội thất theo yêu cầu riêng của từng khách hàng. Đội ngũ kiến trúc sư sẽ làm việc trực tiếp với bạn để tạo ra không gian phù hợp với phong cách và nhu cầu của bạn.', 4, 1),
('Chi phí thiết kế và thi công nội thất được tính như thế nào?', 'Chi phí được tính dựa trên diện tích, độ phức tạp của thiết kế, loại vật liệu và hạng mục thi công. Chúng tôi sẽ cung cấp báo giá chi tiết sau khi khảo sát và trao đổi với bạn về nhu cầu cụ thể.', 4, 1),
('HomeDecor có hỗ trợ thi công tại các tỉnh thành khác không?', 'Có, HomeDecor hoạt động trên toàn quốc, bao gồm Hà Nội, TP. Hồ Chí Minh và các tỉnh thành khác. Chúng tôi có đội ngũ thi công tại nhiều khu vực để đảm bảo chất lượng và tiến độ công trình.', 3, 1),
('Thiết kế nội thất có gồm luôn bản vẽ 3D không?', 'Gói thiết kế bao gồm bản vẽ bố trí mặt bằng, bản vẽ kỹ thuật và bản vẽ 3D chi tiết để khách hàng xem trước tổng thể không gian.', 2, 1),
('HomeDecor có hỗ trợ cải tạo nhà cũ không?', 'Có, chúng tôi nhận cải tạo, nâng cấp, thay đổi công năng và làm mới nội thất cho nhà cũ theo yêu cầu của khách hàng.', 3, 1),
('Thời gian bảo trì sau thi công kéo dài bao lâu?', 'HomeDecor hỗ trợ bảo trì miễn phí 12 tháng đầu và cung cấp gói bảo trì mở rộng nếu khách hàng có nhu cầu.', 4, 1),
('Tôi có thể chọn chất liệu khác so với tư vấn được không?', 'Hoàn toàn được. Bạn có thể chọn bất kỳ vật liệu nào phù hợp với sở thích và ngân sách, đội ngũ sẽ điều chỉnh lại báo giá.', 2, 1),
('HomeDecor có thi công phần thô không?', 'Có, chúng tôi nhận thi công phần thô, điện nước, xây trát và hoàn thiện nội thất trọn gói.', 3, 1),
('Khi nào tôi nhận được báo giá sau khảo sát?', 'Thông thường báo giá sẽ được gửi trong vòng 24–48 giờ sau khi khảo sát thực tế.', 4, 1),
('Có hỗ trợ xin phép cải tạo căn hộ chung cư không?', 'Chúng tôi hỗ trợ chuẩn bị hồ sơ và làm việc với ban quản lý tòa nhà để xin phép thi công.', 3, 1),
('Thiết kế có thể điều chỉnh bao nhiêu lần?', 'HomeDecor hỗ trợ chỉnh sửa thiết kế miễn phí tối đa 2–3 lần theo phản hồi của khách hàng.', 2, 1),
('HomeDecor có cam kết tiến độ thi công không?', 'Có. Mỗi hợp đồng sẽ có tiến độ rõ ràng. Chúng tôi luôn đảm bảo hoàn thành đúng thời hạn đã cam kết.', 3, 1),
('Có thể tham quan công trình mẫu của HomeDecor không?', 'Khách hàng có thể tham quan các công trình thực tế đã thi công nếu phù hợp lịch và địa điểm.', 4, 1),
('Đội ngũ thi công có đạt chuẩn kỹ thuật không?', 'Đội thợ của HomeDecor đều được tuyển chọn, đào tạo bài bản và tuân thủ các tiêu chuẩn kỹ thuật trong quá trình thi công.', 2, 1),
('Thiết kế nội thất có phù hợp phong thủy theo yêu cầu không?', 'Nếu khách hàng mong muốn, chúng tôi sẽ điều chỉnh bố trí nội thất, màu sắc và chất liệu theo nguyên tắc phong thủy.', 4, 1);

