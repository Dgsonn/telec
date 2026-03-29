-- =============================================
-- IMPORT DỰ ÁN TĨNH VÀO DATABASE
-- Chạy trong phpMyAdmin > tab SQL
-- =============================================

-- Thêm cột content vào bảng projects (nếu chưa có)
ALTER TABLE projects ADD COLUMN IF NOT EXISTS content LONGTEXT AFTER description;

-- =============================================
-- SMARTHOME PROJECTS
-- =============================================
INSERT INTO projects (title, client_name, location, category, description, content, image, capacity, project_date, link_detail, is_active, sort_order) VALUES

('Thi công Smart Home Mr Hùng – Hà Nội',
 'Mr Hùng', 'Hà Nội', 'smarthome',
 'Hệ thống điện thông minh Smart Home cho biệt thự tại Hà Nội. Tích hợp chiếu sáng, rèm cửa, điều hòa và an ninh.',
 '<h2>1. Giới thiệu dự án</h2>
<p>Công trình được Công ty TNHH Kỹ Thuật Điện TELEC thiết kế và thi công hệ thống điện thông minh (Smart Home) nhằm mang lại không gian sống hiện đại, tiện nghi và an toàn cho gia chủ. Hệ thống cho phép điều khiển các thiết bị điện trong nhà một cách dễ dàng thông qua công tắc cảm ứng, điện thoại và từ xa.</p>
<h2>2. Giải pháp công nghệ</h2>
<ul class="solution-list">
<li><i class="fas fa-check-circle"></i> Lắp đặt hệ thống chiếu sáng thông minh điều khiển theo kịch bản.</li>
<li><i class="fas fa-check-circle"></i> Tích hợp điều khiển rèm cửa tự động.</li>
<li><i class="fas fa-check-circle"></i> Điều khiển thiết bị điện, điều hòa và bình nước nóng từ xa.</li>
<li><i class="fas fa-check-circle"></i> Thiết lập các ngữ cảnh thông minh như: về nhà, đi ngủ, tiếp khách.</li>
<li><i class="fas fa-check-circle"></i> Kết nối và điều khiển qua ứng dụng điện thoại.</li>
<li><i class="fas fa-check-circle"></i> Tích hợp hệ thống an ninh và cảnh báo.</li>
</ul>
<h2>3. Hiệu quả mang lại</h2>
<ul class="solution-list">
<li><i class="fas fa-star"></i> Tăng sự tiện nghi và hiện đại cho không gian sống.</li>
<li><i class="fas fa-star"></i> Tiết kiệm điện năng nhờ các chế độ tự động.</li>
<li><i class="fas fa-star"></i> Quản lý và điều khiển từ xa mọi lúc mọi nơi.</li>
<li><i class="fas fa-star"></i> Tăng tính an toàn và bảo mật cho ngôi nhà.</li>
</ul>
<h2>4. Hình ảnh công trình</h2>
<img src="mr_hung/z7628834552543_f388ab27941327053f812b4b58117c6a.jpg" alt="Công trình Mr Hùng" class="project-img">
<img src="mr_hung/z7628834541599_8c838355953bc57ec0b0c31101cc761d.jpg" alt="Công trình Mr Hùng" class="project-img">
<img src="mr_hung/z7628834528160_4fd7aa29339b1dc98cd42eb6b5efcb96.jpg" alt="Công trình Mr Hùng" class="project-img">',
 'img/mr-hung1.jpg', 'Smart Home', '2026-01-15', NULL, 1, 1),

('Thi công Smart Home Mrs Hiền – Hà Nội',
 'Mrs Hiền', 'Hà Nội', 'smarthome',
 'Hệ thống Smart Home cho nhà phố tại Hà Nội với giải pháp chiếu sáng thông minh và điều khiển tự động.',
 '<h2>1. Giới thiệu dự án</h2>
<p>Công trình thi công hệ thống điện thông minh cho nhà phố tại Hà Nội. TELEC đã tư vấn và triển khai giải pháp Smart Home toàn diện phù hợp với nhu cầu và không gian sống của gia đình.</p>
<h2>2. Giải pháp công nghệ</h2>
<ul class="solution-list">
<li><i class="fas fa-check-circle"></i> Hệ thống chiếu sáng thông minh điều khiển theo kịch bản.</li>
<li><i class="fas fa-check-circle"></i> Tích hợp điều khiển rèm cửa và màn hình cửa tự động.</li>
<li><i class="fas fa-check-circle"></i> Điều khiển điều hòa, bình nước nóng qua app điện thoại.</li>
<li><i class="fas fa-check-circle"></i> Hệ thống camera an ninh và chuông cửa thông minh.</li>
</ul>
<h2>3. Hiệu quả mang lại</h2>
<ul class="solution-list">
<li><i class="fas fa-star"></i> Không gian sống tiện nghi, hiện đại.</li>
<li><i class="fas fa-star"></i> Tiết kiệm điện năng đến 30% nhờ tự động hóa.</li>
<li><i class="fas fa-star"></i> An ninh được đảm bảo 24/7.</li>
</ul>
<h2>4. Hình ảnh công trình</h2>
<img src="img/z7628845913892_46febcb4ffb053d1a542deec5f15102b.jpg" alt="Công trình Mrs Hiền" class="project-img">',
 'img/z7628845913892_46febcb4ffb053d1a542deec5f15102b.jpg', 'Smart Home', '2026-02-10', NULL, 1, 2),

('Thi công Smart Home Mr Việt – Nam Định',
 'Mr Việt', 'Nam Định', 'smarthome',
 'Hệ thống điện thông minh cho biệt thự vườn tại Nam Định với giải pháp chiếu sáng và kiểm soát năng lượng.',
 '<h2>1. Giới thiệu dự án</h2>
<p>Công trình thi công hệ thống Smart Home cho biệt thự vườn tại Nam Định. TELEC đã thiết kế và triển khai hệ thống điều khiển thông minh toàn diện, tối ưu tiêu thụ điện năng cho gia đình.</p>
<h2>2. Giải pháp công nghệ</h2>
<ul class="solution-list">
<li><i class="fas fa-check-circle"></i> Hệ thống chiếu sáng LED thông minh tiết kiệm năng lượng.</li>
<li><i class="fas fa-check-circle"></i> Điều khiển tự động thiết bị điện trong nhà.</li>
<li><i class="fas fa-check-circle"></i> Hệ thống tưới cây tự động theo lịch.</li>
<li><i class="fas fa-check-circle"></i> Kết nối điều khiển từ xa qua smartphone.</li>
</ul>
<h2>3. Hiệu quả mang lại</h2>
<ul class="solution-list">
<li><i class="fas fa-star"></i> Tự động hóa toàn bộ hoạt động trong nhà.</li>
<li><i class="fas fa-star"></i> Tiết kiệm điện và nước đáng kể.</li>
<li><i class="fas fa-star"></i> Cuộc sống tiện nghi hơn mỗi ngày.</li>
</ul>
<h2>4. Hình ảnh công trình</h2>
<img src="img/z7628882793977_70c343fb3f2808dccdc90ce345e963e3.jpg" alt="Công trình Mr Việt" class="project-img">',
 'img/z7628882793977_70c343fb3f2808dccdc90ce345e963e3.jpg', 'Smart Home', '2026-02-20', NULL, 1, 3);

-- =============================================
-- SOLAR PROJECTS
-- =============================================
INSERT INTO projects (title, client_name, location, category, description, content, image, capacity, project_date, link_detail, is_active, sort_order) VALUES

('Hệ thống năng lượng mặt trời 30kW Hybrid – Mr Thắng – Ninh Bình',
 'Mr Thắng', 'Ninh Bình', 'solar',
 'Hệ thống điện năng lượng mặt trời 30kW Hybrid lắp đặt trên mái nhà, giúp tối ưu chi phí điện năng.',
 '<h2>1. Thông tin dự án</h2>
<p>Công trình được Công ty TNHH Kỹ Thuật Điện TELEC triển khai thiết kế và thi công hệ thống điện năng lượng mặt trời cho công trình với mục tiêu tối ưu chi phí điện năng và hướng tới giải pháp sử dụng năng lượng sạch, bền vững.</p>
<p>Hệ thống được lắp đặt trên mái nhà với các tấm pin năng lượng mặt trời chất lượng cao, giúp tận dụng tối đa nguồn ánh sáng tự nhiên để tạo ra điện năng phục vụ sinh hoạt và sản xuất.</p>
<h2>2. Quy trình triển khai</h2>
<ul class="solution-list">
<li><i class="fas fa-check-circle"></i> Khảo sát và tư vấn giải pháp phù hợp với công trình</li>
<li><i class="fas fa-check-circle"></i> Thiết kế hệ thống điện năng lượng mặt trời tối ưu</li>
<li><i class="fas fa-check-circle"></i> Lắp đặt khung giá đỡ và hệ thống tấm pin</li>
<li><i class="fas fa-check-circle"></i> Lắp đặt inverter và hệ thống đấu nối điện</li>
<li><i class="fas fa-check-circle"></i> Kiểm tra vận hành và bàn giao hệ thống</li>
<li><i class="fas fa-check-circle"></i> Vệ sinh và bảo trì hệ thống định kỳ hằng năm</li>
</ul>
<h2>3. Hiệu quả mang lại</h2>
<ul class="solution-list">
<li><i class="fas fa-star"></i> Tiết kiệm chi phí điện hàng tháng</li>
<li><i class="fas fa-star"></i> Tận dụng nguồn năng lượng sạch từ thiên nhiên</li>
<li><i class="fas fa-star"></i> Giảm phát thải khí CO₂, góp phần bảo vệ môi trường</li>
<li><i class="fas fa-star"></i> Tuổi thọ hệ thống cao, vận hành ổn định</li>
</ul>
<h2>4. Hình ảnh công trình</h2>
<img src="mr_thang/z7628893770531_7d56f1693e1d3bd79114d763d7845633.jpg" alt="Công trình Mr Thắng" class="project-img">
<img src="mr_thang/z7628893774629_dec86c59605a481da8d00249b4f44caa.jpg" alt="Công trình Mr Thắng" class="project-img">
<img src="mr_thang/z7628893796791_f880a861dc342428c47d629084de83c7.jpg" alt="Công trình Mr Thắng" class="project-img">',
 'img/z7628849502887_fc48529b4c55a578a2e8812f149a884d.jpg', '30kW Hybrid', '2026-01-10', NULL, 1, 4),

('Hệ thống điện mặt trời – Mr Trung – Giảng Võ Hà Nội',
 'Mr Trung', 'Giảng Võ, Hà Nội', 'solar',
 'Lắp đặt hệ thống điện năng lượng mặt trời áp mái cho nhà phố tại Giảng Võ, Hà Nội.',
 '<h2>1. Thông tin dự án</h2>
<p>Công trình lắp đặt hệ thống điện mặt trời áp mái cho nhà phố tại Giảng Võ, Hà Nội. TELEC đã tư vấn và thiết kế hệ thống tối ưu phù hợp với diện tích mái và nhu cầu sử dụng điện của gia đình.</p>
<h2>2. Quy trình triển khai</h2>
<ul class="solution-list">
<li><i class="fas fa-check-circle"></i> Khảo sát hiện trạng mái và tư vấn giải pháp tối ưu</li>
<li><i class="fas fa-check-circle"></i> Thiết kế hệ thống, chọn thiết bị phù hợp</li>
<li><i class="fas fa-check-circle"></i> Lắp đặt tấm pin và inverter</li>
<li><i class="fas fa-check-circle"></i> Đấu nối hệ thống và kiểm tra an toàn</li>
<li><i class="fas fa-check-circle"></i> Bàn giao và hướng dẫn vận hành</li>
</ul>
<h2>3. Hiệu quả mang lại</h2>
<ul class="solution-list">
<li><i class="fas fa-star"></i> Giảm tiền điện hàng tháng đáng kể</li>
<li><i class="fas fa-star"></i> Hệ thống hoạt động ổn định, bền vững</li>
<li><i class="fas fa-star"></i> Được bảo hành và hỗ trợ bởi TELEC</li>
</ul>
<h2>4. Hình ảnh công trình</h2>
<img src="img/z7628849502887_fc48529b4c55a578a2e8812f149a884d.jpg" alt="Công trình Mr Trung" class="project-img">',
 'img/z7628849502887_fc48529b4c55a578a2e8812f149a884d.jpg', NULL, '2025-11-20', NULL, 1, 5),

('Hệ thống điện mặt trời – Mrs Liên – Phú Diễn Hà Nội',
 'Mrs Liên', 'Phú Diễn, Hà Nội', 'solar',
 'Lắp đặt hệ thống năng lượng mặt trời áp mái cho gia đình tại Phú Diễn, Hà Nội.',
 '<h2>1. Thông tin dự án</h2>
<p>Hệ thống điện mặt trời áp mái được TELEC thiết kế và lắp đặt cho gia đình tại Phú Diễn, Hà Nội. Hệ thống giúp chủ đầu tư tiết kiệm chi phí điện năng và góp phần sử dụng năng lượng xanh.</p>
<h2>2. Quy trình triển khai</h2>
<ul class="solution-list">
<li><i class="fas fa-check-circle"></i> Khảo sát và thiết kế hệ thống phù hợp</li>
<li><i class="fas fa-check-circle"></i> Lắp đặt tấm pin, khung giá đỡ</li>
<li><i class="fas fa-check-circle"></i> Lắp đặt inverter và tủ điện</li>
<li><i class="fas fa-check-circle"></i> Bàn giao và hỗ trợ giám sát từ xa</li>
</ul>
<h2>3. Hiệu quả mang lại</h2>
<ul class="solution-list">
<li><i class="fas fa-star"></i> Tiết kiệm chi phí điện hàng tháng</li>
<li><i class="fas fa-star"></i> Góp phần bảo vệ môi trường</li>
<li><i class="fas fa-star"></i> Hệ thống vận hành ổn định lâu dài</li>
</ul>',
 'img/z7628849502887_fc48529b4c55a578a2e8812f149a884d.jpg', NULL, '2025-12-05', NULL, 1, 6),

('Hệ thống điện mặt trời – Mr Tuấn – Nghệ An',
 'Mr Tuấn', 'Nghệ An', 'solar',
 'Lắp đặt hệ thống năng lượng mặt trời cho công trình tại Nghệ An với công nghệ hiện đại.',
 '<h2>1. Thông tin dự án</h2>
<p>Công trình lắp đặt hệ thống điện năng lượng mặt trời tại Nghệ An. TELEC đã triển khai hệ thống với thiết bị chất lượng cao, phù hợp với điều kiện khí hậu và nhu cầu sử dụng điện tại địa phương.</p>
<h2>2. Quy trình triển khai</h2>
<ul class="solution-list">
<li><i class="fas fa-check-circle"></i> Khảo sát địa hình và tư vấn giải pháp</li>
<li><i class="fas fa-check-circle"></i> Thiết kế hệ thống tối ưu cho điều kiện địa phương</li>
<li><i class="fas fa-check-circle"></i> Lắp đặt hoàn thiện và kiểm tra vận hành</li>
<li><i class="fas fa-check-circle"></i> Bàn giao và hỗ trợ bảo trì định kỳ</li>
</ul>
<h2>3. Hiệu quả mang lại</h2>
<ul class="solution-list">
<li><i class="fas fa-star"></i> Tiết kiệm chi phí điện năng đáng kể</li>
<li><i class="fas fa-star"></i> Hệ thống bền vững, tuổi thọ cao</li>
<li><i class="fas fa-star"></i> Giảm phát thải carbon, bảo vệ môi trường</li>
</ul>',
 'img/z7628849502887_fc48529b4c55a578a2e8812f149a884d.jpg', NULL, '2025-10-15', NULL, 1, 7),

('Hệ thống điện mặt trời – Mr Luận – Nguyễn Xiển Hà Nội',
 'Mr Luận', 'Nguyễn Xiển, Hà Nội', 'solar',
 'Lắp đặt hệ thống năng lượng mặt trời áp mái tại Nguyễn Xiển, Hà Nội.',
 '<h2>1. Thông tin dự án</h2>
<p>Hệ thống điện mặt trời áp mái được TELEC thiết kế và thi công cho công trình tại Nguyễn Xiển, Hà Nội. Hệ thống tích hợp inverter hybrid giúp lưu trữ điện và sử dụng linh hoạt.</p>
<h2>2. Quy trình triển khai</h2>
<ul class="solution-list">
<li><i class="fas fa-check-circle"></i> Khảo sát và tư vấn giải pháp tối ưu</li>
<li><i class="fas fa-check-circle"></i> Thiết kế hệ thống inverter hybrid</li>
<li><i class="fas fa-check-circle"></i> Lắp đặt tấm pin và hệ thống điện</li>
<li><i class="fas fa-check-circle"></i> Kiểm tra vận hành và bàn giao</li>
</ul>
<h2>3. Hiệu quả mang lại</h2>
<ul class="solution-list">
<li><i class="fas fa-star"></i> Chủ động nguồn điện, giảm phụ thuộc điện lưới</li>
<li><i class="fas fa-star"></i> Tiết kiệm chi phí điện dài hạn</li>
<li><i class="fas fa-star"></i> Hệ thống giám sát từ xa tiện lợi</li>
</ul>',
 'img/z7628849502887_fc48529b4c55a578a2e8812f149a884d.jpg', NULL, '2025-09-20', NULL, 1, 8),

('Hệ thống điện mặt trời – Mr Quỳnh – Nam Định',
 'Mr Quỳnh', 'Nam Định', 'solar',
 'Lắp đặt hệ thống năng lượng mặt trời cho nhà ở tại Nam Định.',
 '<h2>1. Thông tin dự án</h2>
<p>Công trình lắp đặt hệ thống điện năng lượng mặt trời cho nhà ở tại Nam Định. TELEC đã tư vấn và thiết kế hệ thống phù hợp với nhu cầu và ngân sách của gia đình.</p>
<h2>2. Quy trình triển khai</h2>
<ul class="solution-list">
<li><i class="fas fa-check-circle"></i> Tư vấn và lựa chọn thiết bị phù hợp</li>
<li><i class="fas fa-check-circle"></i> Lắp đặt tấm pin chất lượng cao</li>
<li><i class="fas fa-check-circle"></i> Đấu nối hệ thống an toàn</li>
<li><i class="fas fa-check-circle"></i> Hỗ trợ giám sát và bảo trì</li>
</ul>
<h2>3. Hiệu quả mang lại</h2>
<ul class="solution-list">
<li><i class="fas fa-star"></i> Giảm tiền điện hàng tháng</li>
<li><i class="fas fa-star"></i> Nguồn điện ổn định, tin cậy</li>
<li><i class="fas fa-star"></i> Sử dụng năng lượng tái tạo sạch</li>
</ul>',
 'img/z7628849502887_fc48529b4c55a578a2e8812f149a884d.jpg', NULL, '2025-08-10', NULL, 1, 9),

('Hệ thống điện mặt trời – Mr Tuấn Anh – Nam Định',
 'Mr Tuấn Anh', 'Nam Định', 'solar',
 'Lắp đặt hệ thống năng lượng mặt trời kết hợp lưu trữ pin Lithium tại Nam Định.',
 '<h2>1. Thông tin dự án</h2>
<p>Công trình lắp đặt hệ thống điện mặt trời kết hợp pin lưu trữ Lithium tại Nam Định. Đây là giải pháp toàn diện giúp gia đình chủ động nguồn điện, giảm phụ thuộc vào điện lưới quốc gia.</p>
<h2>2. Quy trình triển khai</h2>
<ul class="solution-list">
<li><i class="fas fa-check-circle"></i> Khảo sát nhu cầu và thiết kế hệ thống hybrid</li>
<li><i class="fas fa-check-circle"></i> Lắp đặt tấm pin và inverter hybrid</li>
<li><i class="fas fa-check-circle"></i> Tích hợp hệ thống pin lưu trữ Lithium</li>
<li><i class="fas fa-check-circle"></i> Kiểm tra, bàn giao và hướng dẫn sử dụng</li>
</ul>
<h2>3. Hiệu quả mang lại</h2>
<ul class="solution-list">
<li><i class="fas fa-star"></i> Chủ động nguồn điện 24/7, kể cả ban đêm</li>
<li><i class="fas fa-star"></i> Tiết kiệm chi phí điện tối đa</li>
<li><i class="fas fa-star"></i> Giám sát hệ thống từ xa qua ứng dụng</li>
</ul>',
 'img/z7628849502887_fc48529b4c55a578a2e8812f149a884d.jpg', NULL, '2025-07-15', NULL, 1, 10);
