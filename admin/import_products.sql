-- =============================================
-- IMPORT TẤT CẢ SẢN PHẨM TĨNH VÀO DATABASE
-- Chạy trong phpMyAdmin > tab SQL
-- =============================================

-- Thêm cột content vào projects nếu chưa có
ALTER TABLE projects ADD COLUMN IF NOT EXISTS content LONGTEXT AFTER description;

-- =============================================
-- INVERTER / BIẾN TẦN
-- =============================================

-- CHISAGE HYBRID
INSERT INTO products (name, category, brand, description, image, price_label, is_active, sort_order) VALUES
('Inverter CHISAGE 6.6kW Hybrid Eris-6.6G1-LE', 'inverter', 'chisage', 'Inverter hybrid 1 pha 6.6kW áp thấp cao cấp, phù hợp hệ thống điện mặt trời gia đình.', 'https://cdn.tcenergy.vn/wp-content/uploads/san-pham/inverter/chisage/bo-bien-tan-nang-luong-mat-troi-chisage-ess-hybrid-8kw-1-phase.png', 'Liên hệ', 1, 1),
('Inverter CHISAGE Hybrid 1 Pha 5kW Áp Thấp', 'inverter', 'chisage', 'Inverter hybrid 1 pha 5kW áp thấp cao cấp.', 'https://cdn.tcenergy.vn/wp-content/uploads/san-pham/inverter/chisage/bo-bien-tan-nang-luong-mat-troi-chisage-ess-hybrid-8kw-1-phase.png', 'Liên hệ', 1, 2),
('Inverter CHISAGE Hybrid 1 Pha 6kW Áp Thấp', 'inverter', 'chisage', 'Inverter hybrid 1 pha 6kW áp thấp cao cấp.', 'https://cdn.tcenergy.vn/wp-content/uploads/san-pham/inverter/chisage/bo-bien-tan-nang-luong-mat-troi-chisage-ess-hybrid-6kw-1-phase.png', 'Liên hệ', 1, 3),
('Inverter CHISAGE Hybrid 1 Pha 8kW Áp Thấp - Jup-8G1-LE', 'inverter', 'chisage', 'Inverter hybrid 1 pha 8kW áp thấp cao cấp, model Jup-8G1-LE.', 'https://cdn.tcenergy.vn/wp-content/uploads/san-pham/inverter/chisage/bo-bien-tan-nang-luong-mat-troi-chisage-ess-hybrid-8kw-1-phase.png', 'Liên hệ', 1, 4),
('Inverter CHISAGE Hybrid 1 Pha 10kW Áp Thấp', 'inverter', 'chisage', 'Inverter hybrid 1 pha 10kW áp thấp cao cấp.', 'https://cdn.tcenergy.vn/wp-content/uploads/san-pham/inverter/chisage/bo-bien-tan-nang-luong-mat-troi-chisage-ess-hybrid-8kw-1-phase.png', 'Liên hệ', 1, 5),
('Inverter CHISAGE Hybrid 3 Pha 14kW Áp Thấp', 'inverter', 'chisage', 'Inverter hybrid 3 pha 14kW áp thấp cao cấp.', 'https://cdn.tcenergy.vn/wp-content/uploads/san-pham/inverter/chisage/bo-bien-tan-nang-luong-mat-troi-chisage-ess-hybrid-8kw-1-phase.png', 'Liên hệ', 1, 6);

-- SUNGROW
INSERT INTO products (name, category, brand, description, image, price_label, is_active, sort_order) VALUES
('Inverter Sungrow 1 Pha 5kW MG5RL', 'inverter', 'sungrow', 'Inverter on-grid 1 pha 5kW, thiết kế nhỏ gọn, hiệu suất cao.', 'https://hungvietgt.com/upload/image/sanpham/sungrow/ban-sao-cua-luu-tru-ben-bi-nam-moi-tiet-kiem-nhu-y.jpg', 'Liên hệ', 1, 10),
('Inverter Sungrow 1 Pha 6kW MG6RL', 'inverter', 'sungrow', 'Inverter on-grid 1 pha 6kW, thiết kế nhỏ gọn, hiệu suất cao.', 'https://hungvietgt.com/upload/image/sanpham/sungrow/ban-sao-cua-luu-tru-ben-bi-nam-moi-tiet-kiem-nhu-y-1.jpg', 'Liên hệ', 1, 11);

-- HUAWEI
INSERT INTO products (name, category, brand, description, image, price_label, is_active, sort_order) VALUES
('Inverter Huawei 3 pha 150kW SUN2000-150K-MG0', 'inverter', 'huawei', 'Inverter công suất lớn 3 pha 150kW, phù hợp hệ thống thương mại quy mô lớn.', 'https://hungvietgt.com/upload/image/sanpham/huawei/z6957527572739924b1dc7fb84bbe442b4a40ce09d3575.jpg', 'Liên hệ', 1, 20),
('Inverter Huawei 3 pha 115kW SUN2000-115KTL-M2', 'inverter', 'huawei', 'Inverter 3 pha 115kW công nghệ tiên tiến.', 'https://hungvietgt.com/upload/image/sanpham/huawei/z6957527572739924b1dc7fb84bbe442b4a40ce09d3575.jpg', 'Liên hệ', 1, 21),
('Inverter Huawei 3 pha 50kW SUN2000-50K-MC0', 'inverter', 'huawei', 'Inverter 3 pha 50kW hiệu suất cao.', 'https://hungvietgt.com/upload/SANPHAM/huawei/%E1%BA%A3nh_s%E1%BA%A3n_ph%E1%BA%A9m/bi%E1%BA%BFn_t%E1%BA%A7n/zhizhenanquan.png', 'Liên hệ', 1, 22),
('Inverter Huawei 3 pha 40kW SUN2000-40K-MC0', 'inverter', 'huawei', 'Inverter 3 pha 40kW hiệu suất cao.', 'https://hungvietgt.com/upload/SANPHAM/huawei/%E1%BA%A3nh_s%E1%BA%A3n_ph%E1%BA%A9m/bi%E1%BA%BFn_t%E1%BA%A7n/zhizhenanquan.png', 'Liên hệ', 1, 23),
('Inverter Huawei 3 pha 30kW SUN2000-30K-MC0', 'inverter', 'huawei', 'Inverter 3 pha 30kW hiệu suất cao.', 'https://hungvietgt.com/upload/SANPHAM/huawei/%E1%BA%A3nh_s%E1%BA%A3n_ph%E1%BA%A9m/bi%E1%BA%BFn_t%E1%BA%A7n/zhizhenanquan.png', 'Liên hệ', 1, 24),
('Inverter Huawei 3 pha 40kW SUN2000-40KTL-M3', 'inverter', 'huawei', 'Inverter 3 pha 40kW model M3 thế hệ mới.', 'https://hungvietgt.com/upload/image/sanpham/huawei/sun2000-50ktl-m3.png', 'Liên hệ', 1, 25),
('Inverter Huawei 3 pha 30kW SUN2000-30KTL-M3', 'inverter', 'huawei', 'Inverter 3 pha 30kW model M3 thế hệ mới.', 'https://hungvietgt.com/upload/image/sanpham/huawei/sun2000-50ktl-m3.png', 'Liên hệ', 1, 26),
('Inverter Hybrid Huawei 12kW SUN2000-12K-MAP0', 'inverter', 'huawei', 'Inverter hybrid 1 pha 12kW, tích hợp quản lý lưu trữ thông minh.', 'https://hungvietgt.com/upload/image/sanpham/huawei/sun2000-8-10-12k-map0.png', 'Liên hệ', 1, 27),
('Inverter Hybrid Huawei 10kW SUN2000-10K-MAP0', 'inverter', 'huawei', 'Inverter hybrid 1 pha 10kW, tích hợp quản lý lưu trữ thông minh.', 'https://hungvietgt.com/upload/image/sanpham/huawei/sun2000-8-10-12k-map0.png', 'Liên hệ', 1, 28),
('Inverter Hybrid Huawei 8kW SUN2000-8K-MAP0', 'inverter', 'huawei', 'Inverter hybrid 1 pha 8kW, tích hợp quản lý lưu trữ thông minh.', 'https://hungvietgt.com/upload/image/sanpham/huawei/sun2000-8-10-12k-map0.png', 'Liên hệ', 1, 29),
('Inverter Hybrid Huawei 10kW SUN2000-10K-LC0', 'inverter', 'huawei', 'Inverter hybrid 1 pha 10kW model LC0.', 'https://hungvietgt.com/upload/image/sanpham/huawei/sun2000-8-10k-lc0.png', 'Liên hệ', 1, 30),
('Inverter Hybrid Huawei 8kW SUN2000-8K-LC0', 'inverter', 'huawei', 'Inverter hybrid 1 pha 8kW model LC0.', 'https://hungvietgt.com/upload/image/sanpham/huawei/sun2000-8-10k-lc0.png', 'Liên hệ', 1, 31),
('Inverter Hybrid Huawei 6kW SUN2000-6KTL-LB0', 'inverter', 'huawei', 'Inverter hybrid 1 pha 6kW model LB0, phù hợp hệ thống nhỏ.', 'https://hungvietgt.com/upload/image/sanpham/huawei/sun20005-6k-lb0.png', 'Liên hệ', 1, 32),
('Inverter Hybrid Huawei 5kW SUN2000-5KTL-LB0', 'inverter', 'huawei', 'Inverter hybrid 1 pha 5kW model LB0, phù hợp hệ thống nhỏ.', 'https://hungvietgt.com/upload/image/sanpham/huawei/sun20005-6k-lb0.png', 'Liên hệ', 1, 33);

-- =============================================
-- TẤM PIN SOLAR & PIN LITHIUM
-- =============================================

-- JA SOLAR
INSERT INTO products (name, category, brand, description, image, price_label, is_active, sort_order) VALUES
('Tấm pin JA Solar 620Wp Ntype hai mặt kính - JAM66D45 620/LB', 'solar', 'jasolar', 'Tấm pin mặt trời JA Solar 620Wp công nghệ N-type hai mặt kính, hiệu suất cao.', 'https://hungvietgt.com/upload/image/sanpham/tam-pin-ja-solar/ban-sao-cua-luu-tru-ben-bi-nam-moi-tiet-kiem-nhu-y-1.png', 'Liên hệ', 1, 40),
('Tấm pin JA Solar 625Wp Ntype hai mặt kính - JAM66D45 625/LB', 'solar', 'jasolar', 'Tấm pin mặt trời JA Solar 625Wp công nghệ N-type hai mặt kính.', 'https://hungvietgt.com/upload/image/sanpham/tam-pin-ja-solar/ban-sao-cua-luu-tru-ben-bi-nam-moi-tiet-kiem-nhu-y-1.png', 'Liên hệ', 1, 41),
('Tấm pin JA Solar 635Wp Ntype hai mặt kính - JAM72D42-635/LB', 'solar', 'jasolar', 'Tấm pin mặt trời JA Solar 635Wp công nghệ N-type hai mặt kính, công suất cao.', 'https://hungvietgt.com/upload/SANPHAM/tam-pin/ja_solar/z7453151087764_10edd6068e7d7fa7af292d1ad00adee7.jpg', 'Liên hệ', 1, 42),
('Tấm pin JA Solar 640Wp Ntype hai mặt kính - JAM72D42-640/LB', 'solar', 'jasolar', 'Tấm pin mặt trời JA Solar 640Wp công nghệ N-type hai mặt kính, công suất cao.', 'https://hungvietgt.com/upload/SANPHAM/tam-pin/ja_solar/z7453151087764_10edd6068e7d7fa7af292d1ad00adee7.jpg', 'Liên hệ', 1, 43);

-- LONGI SOLAR
INSERT INTO products (name, category, brand, description, image, price_label, is_active, sort_order) VALUES
('Tấm pin LONGi 445Wp Hi-MO', 'solar', 'longi', 'Tấm pin năng lượng mặt trời LONGi 445Wp, hiệu suất cao, độ bền tốt.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 50),
('Tấm pin LONGi 450Wp Hi-MO', 'solar', 'longi', 'Tấm pin năng lượng mặt trời LONGi 450Wp.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 51),
('Tấm pin LONGi 535Wp Hi-MO', 'solar', 'longi', 'Tấm pin năng lượng mặt trời LONGi 535Wp.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 52),
('Tấm pin LONGi 540Wp Hi-MO', 'solar', 'longi', 'Tấm pin năng lượng mặt trời LONGi 540Wp.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 53),
('Tấm pin LONGi 545Wp Hi-MO', 'solar', 'longi', 'Tấm pin năng lượng mặt trời LONGi 545Wp.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 54),
('Tấm pin LONGi 2 mặt kính 545Wp - LR5-72HBD-545M', 'solar', 'longi', 'Tấm pin LONGi 545Wp hai mặt kính, hấp thụ ánh sáng từ cả hai phía.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 55),
('Tấm pin LONGi 550Wp Hi-MO 6', 'solar', 'longi', 'Tấm pin LONGi Hi-MO 6 550Wp hiệu suất cao.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 56),
('Tấm pin LONGi 560Wp Hi-MO', 'solar', 'longi', 'Tấm pin năng lượng mặt trời LONGi 560Wp.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 57),
('Tấm pin LONGi 575Wp Hi-MO 6', 'solar', 'longi', 'Tấm pin LONGi Hi-MO 6 575Wp.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 58),
('Tấm pin LONGi 580Wp Hi-MO 6', 'solar', 'longi', 'Tấm pin LONGi Hi-MO 6 580Wp.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 59),
('Tấm pin LONGi 610Wp Hi-MO 7', 'solar', 'longi', 'Tấm pin LONGi Hi-MO 7 610Wp thế hệ mới.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 60),
('Tấm pin LONGi 620Wp - 625Wp Hi-MO X10', 'solar', 'longi', 'Tấm pin LONGi Hi-MO X10 620-625Wp công nghệ N-type.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 61),
('Tấm pin LONGi 640Wp - 645Wp Hi-MO X10', 'solar', 'longi', 'Tấm pin LONGi Hi-MO X10 640-645Wp công nghệ N-type.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 62),
('Tấm pin LONGi 650Wp Hi-MO X10', 'solar', 'longi', 'Tấm pin LONGi Hi-MO X10 650Wp công nghệ N-type hiệu suất cao nhất.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 63),
('Tấm pin LONGi 670Wp Hi-MO X10', 'solar', 'longi', 'Tấm pin LONGi Hi-MO X10 670Wp, công suất cao nhất dòng Hi-MO X10.', 'https://static.longi.com/module_2_500889fb15.png', 'Liên hệ', 1, 64);

-- EASYWAY LITHIUM
INSERT INTO products (name, category, brand, description, image, price_label, is_active, sort_order) VALUES
('Pin Lithium 5kWh Easyway UNIV-5200(II)', 'solar', 'easyway', 'Pin lưu trữ Lithium 5kWh 51.2V, dòng UNIV thế hệ II của Easyway.', 'https://hungvietgt.com/upload/SANPHAM/pin-lithium/da_chinh_to_len.png', 'Liên hệ', 1, 70),
('Pin Lithium 10kWh Easyway UNIV-10kWhFS 51.2V200Ah', 'solar', 'easyway', 'Pin lưu trữ Lithium 10kWh 51.2V 200Ah, phù hợp hệ thống inverter hybrid.', 'https://hungvietgt.com/upload/SANPHAM/pin-lithium/da_chinh_to_len.png', 'Liên hệ', 1, 71),
('Pin Lithium 16kWh Easyway UNIV-16kWh 51.2V314Ah', 'solar', 'easyway', 'Pin lưu trữ Lithium 16kWh 51.2V 314Ah dung lượng lớn.', 'https://hungvietgt.com/upload/SANPHAM/pin-lithium/da_chinh_to_len.png', 'Liên hệ', 1, 72),
('Pin Lithium Điện Áp Cao 7.6kWh Easyway 76.8V100Ah', 'solar', 'easyway', 'Pin lưu trữ Lithium điện áp cao 7.6kWh 76.8V 100Ah.', 'https://hungvietgt.com/upload/SANPHAM/pin-lithium/da_chinh_to_len.png', 'Liên hệ', 1, 73),
('Pin Lithium Điện Áp Cao 23kWh Easyway UNIV-HV ST23k', 'solar', 'easyway', 'Pin lưu trữ Lithium điện áp cao 23kWh.', 'https://hungvietgt.com/upload/SANPHAM/pin-lithium/da_chinh_to_len.png', 'Liên hệ', 1, 74),
('Pin Lithium Điện Áp Cao 31kWh Easyway UNIV-HV ST31k', 'solar', 'easyway', 'Pin lưu trữ Lithium điện áp cao 31kWh.', 'https://hungvietgt.com/upload/SANPHAM/pin-lithium/da_chinh_to_len.png', 'Liên hệ', 1, 75),
('Pin Lithium Điện Áp Cao 38kWh Easyway UNIV-HV ST38k', 'solar', 'easyway', 'Pin lưu trữ Lithium điện áp cao 38kWh.', 'https://hungvietgt.com/upload/SANPHAM/pin-lithium/da_chinh_to_len.png', 'Liên hệ', 1, 76),
('Pin Lithium Điện Áp Cao 46kWh Easyway UNIV-HV ST46k', 'solar', 'easyway', 'Pin lưu trữ Lithium điện áp cao 46kWh.', 'https://hungvietgt.com/upload/SANPHAM/pin-lithium/da_chinh_to_len.png', 'Liên hệ', 1, 77),
('Pin Lithium Điện Áp Cao 54kWh Easyway UNIV-HV ST54k', 'solar', 'easyway', 'Pin lưu trữ Lithium điện áp cao 54kWh.', 'https://hungvietgt.com/upload/SANPHAM/pin-lithium/da_chinh_to_len.png', 'Liên hệ', 1, 78),
('Pin Lithium Điện Áp Cao 61kWh Easyway UNIV-HV ST61k', 'solar', 'easyway', 'Pin lưu trữ Lithium điện áp cao 61kWh.', 'https://hungvietgt.com/upload/SANPHAM/pin-lithium/da_chinh_to_len.png', 'Liên hệ', 1, 79),
('Tủ pin Lithium 100kWh Easyway ESS 100kWh/50kW', 'solar', 'easyway', 'Tủ điện pin Lithium công suất lớn 100kWh/50kW, phù hợp thương mại.', 'https://hungvietgt.com/upload/SANPHAM/pin-lithium/da_chinh_to_len.png', 'Liên hệ', 1, 80);

-- DYNESS
INSERT INTO products (name, category, brand, description, image, price_label, is_active, sort_order) VALUES
('Pin Lithium 5.12kWh Dyness DL5.0C', 'solar', 'dyness', 'Pin lưu trữ Lithium Dyness 5.12kWh, thiết kế nhỏ gọn.', 'https://hungvietgt.com/upload/image/sanpham/thiet-ke-chua-co-ten.png', 'Liên hệ', 1, 85),
('Pin Lithium 14.3kWh Dyness PowerBrick', 'solar', 'dyness', 'Pin lưu trữ Lithium Dyness PowerBrick 14.3kWh.', 'https://hungvietgt.com/upload/image/sanpham/thiet-ke-chua-co-ten.png', 'Liên hệ', 1, 86),
('Pin Lithium Điện Áp Cao 7.10kWh Dyness Tower T7', 'solar', 'dyness', 'Pin lưu trữ Dyness Tower T7 7.10kWh điện áp cao.', 'https://hungvietgt.com/upload/image/sanpham/thiet-ke-chua-co-ten.png', 'Liên hệ', 1, 87),
('Pin Lithium Điện Áp Cao 10.66kWh Dyness Tower T10', 'solar', 'dyness', 'Pin lưu trữ Dyness Tower T10 10.66kWh điện áp cao.', 'https://hungvietgt.com/upload/image/sanpham/thiet-ke-chua-co-ten.png', 'Liên hệ', 1, 88),
('Pin Lithium Điện Áp Cao 14.21kWh Dyness Tower T14', 'solar', 'dyness', 'Pin lưu trữ Dyness Tower T14 14.21kWh điện áp cao.', 'https://hungvietgt.com/upload/image/sanpham/thiet-ke-chua-co-ten.png', 'Liên hệ', 1, 89),
('Pin Lithium Điện Áp Cao 17.76kWh Dyness Tower T17', 'solar', 'dyness', 'Pin lưu trữ Dyness Tower T17 17.76kWh điện áp cao.', 'https://hungvietgt.com/upload/image/sanpham/thiet-ke-chua-co-ten.png', 'Liên hệ', 1, 90),
('Pin Lithium Điện Áp Cao 21.31kWh Dyness Tower T21', 'solar', 'dyness', 'Pin lưu trữ Dyness Tower T21 21.31kWh điện áp cao.', 'https://hungvietgt.com/upload/image/sanpham/thiet-ke-chua-co-ten.png', 'Liên hệ', 1, 91),
('Pin Lithium Điện Áp Cao 14.336kWh Dyness STACK280', 'solar', 'dyness', 'Pin lưu trữ Dyness STACK280 14.336kWh hệ thống stack.', 'https://hungvietgt.com/upload/image/sanpham/thiet-ke-chua-co-ten.png', 'Liên hệ', 1, 92),
('Pin Lithium Điện Áp Cao 15.36-76.8kWh Dyness STACK100', 'solar', 'dyness', 'Pin lưu trữ Dyness STACK100 công suất từ 15.36 đến 76.8kWh.', 'https://hungvietgt.com/upload/image/sanpham/thiet-ke-chua-co-ten.png', 'Liên hệ', 1, 93);

-- =============================================
-- SMART HOME
-- =============================================
INSERT INTO products (name, category, brand, description, image, price_label, is_active, sort_order) VALUES
('Cong tac cam ung Luto vien nhom', 'smarthome', 'switch', 'Công tắc cảm ứng Luto viền nhôm sang trọng, điều khiển cảm ứng nhẹ tay.', '', 'Liên hệ', 1, 100),
('Cong tac co thong minh Lumes', 'smarthome', 'switch', 'Công tắc cơ thông minh Lumes, tương thích hệ thống Smart Home.', '', 'Liên hệ', 1, 101),
('Den LED Am tran Tunable White', 'smarthome', 'lamp', 'Đèn LED âm trần Tunable White, điều chỉnh màu sắc ánh sáng theo kịch bản.', '', 'Liên hệ', 1, 110),
('He thong Den ray nam cham', 'smarthome', 'lamp', 'Hệ thống đèn ray nam châm linh hoạt, dễ điều chỉnh góc chiếu.', '', 'Liên hệ', 1, 111),
('Khoa thong minh Lumi Luvit', 'smarthome', 'luvit', 'Khóa cửa thông minh Lumi Luvit, mở khóa bằng vân tay, thẻ từ, mật mã.', '', 'Liên hệ', 1, 120),
('Khoa thong minh Lumi nhom Xingfa', 'smarthome', 'luvit', 'Khóa thông minh Lumi với cửa nhôm Xingfa, chống nước tốt.', '', 'Liên hệ', 1, 121),
('Cam bien cua thong minh', 'smarthome', 'sensor', 'Cảm biến cửa thông minh, báo động khi cửa mở bất thường.', '', 'Liên hệ', 1, 130),
('Cam bien chuyen dong PIR', 'smarthome', 'sensor', 'Cảm biến chuyển động PIR, phát hiện người và kích hoạt tự động.', '', 'Liên hệ', 1, 131),
('Bo dieu khien trung tam HC Premium', 'smarthome', 'hc', 'Bộ điều khiển trung tâm HC Premium, quản lý toàn bộ thiết bị Smart Home.', '', 'Liên hệ', 1, 140),
('Bo dieu khien trung tam HC Standard', 'smarthome', 'hc', 'Bộ điều khiển trung tâm HC Standard, phù hợp gia đình vừa và nhỏ.', '', 'Liên hệ', 1, 141),
('Dong co rem vai thong minh', 'smarthome', 'motor', 'Động cơ rèm vải thông minh, điều khiển tự động theo lịch và kịch bản.', '', 'Liên hệ', 1, 150),
('Module dieu khien cong tu dong', 'smarthome', 'motor', 'Module điều khiển cổng tự động, tích hợp hệ thống Smart Home.', '', 'Liên hệ', 1, 151);
