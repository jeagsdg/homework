-- 创建数据库（如果不存在）
CREATE DATABASE IF NOT EXISTS `financial_products` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- 使用该数据库
USE `financial_products`;

-- 创建用户表
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` decimal(15,2) DEFAULT '50000.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户表';

-- 创建产品表
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '稳健型',
  `annual_rate` decimal(5,2) NOT NULL,
  `investment_period` int(11) NOT NULL,
  `min_investment` decimal(12,2) NOT NULL,
  `increment_amount` decimal(12,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `remaining_amount` decimal(12,2) NOT NULL,
  `deadline` datetime NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='理财产品表';

-- 创建公告表
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `publish_date` date NOT NULL,
  `year` int(4) NOT NULL,
  `reference_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='公告表';

-- 创建产品类型表
CREATE TABLE IF NOT EXISTS `product_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='产品类型表';

-- 创建投资记录表
CREATE TABLE IF NOT EXISTS `investments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `amount` decimal(15,2) NOT NULL COMMENT '投资金额',
  `expected_revenue` decimal(15,2) NOT NULL COMMENT '预期收益',
  `investment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '投资时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='投资记录表';

-- 插入测试用户(testqq/123456)
INSERT INTO `users` (`username`, `password`, `balance`) 
VALUES ('testqq', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '10000.00');

-- 插入产品数据
INSERT INTO `products` (`product_code`, `product_name`, `product_type`, `annual_rate`, `investment_period`, `min_investment`, `increment_amount`, `start_date`, `end_date`, `total_amount`, `remaining_amount`, `deadline`, `is_active`) VALUES
('P202401', '稳健增利一号', '稳健型', 4.50, 90, 1000.00, 100.00, '2024-01-01', '2024-03-31', 500000.00, 200000.00, '2024-01-15 23:59:59', 1),
('P202402', '高收益混合二号', '进取型', 7.80, 180, 5000.00, 500.00, '2024-01-10', '2024-07-08', 1000000.00, 800000.00, '2024-02-01 23:59:59', 1),
('P202403', '平衡理财三号', '平衡型', 5.60, 120, 3000.00, 300.00, '2024-02-01', '2024-06-01', 800000.00, 400000.00, '2024-02-15 23:59:59', 1),
('P202404', '短期理财四号', '稳健型', 3.80, 30, 1000.00, 100.00, '2024-02-15', '2024-03-15', 300000.00, 100000.00, '2024-02-10 23:59:59', 1),
('200110', '安富 200110期', '稳健型', 3.07, 90, 10000.00, 1000.00, '2024-03-25', '2024-06-25', 1000000.00, 300000.00, '2024-03-20 23:59:59', 1);

-- 插入公告数据
INSERT INTO `announcements` (`title`, `content`, `publish_date`, `year`, `reference_code`, `is_active`) VALUES
('关于2024年春节假期系统维护的公告', '尊敬的客户：为了提供更优质的服务，我司将于2024年春节期间对系统进行维护升级。维护时间为2024年2月10日凌晨00:00至06:00，期间将暂停所有在线交易服务。感谢您的理解与支持！', '2024-02-01', 2024, 'AN-2024-001', 1),
('2023年度报告发布', '2023年度报告已发布，请各位投资者查阅。报告内容包括2023年度的经营状况、财务指标以及2024年的投资策略和规划。', '2024-03-15', 2024, 'AN-2024-002', 1),
('关于新增线上理财产品的通知', '我行将于2023年12月1日起新增三款线上理财产品，包括安富宝、稳利盈和智选理财，满足不同风险偏好投资者的需求。', '2023-11-28', 2023, 'AN-2023-045', 1),
('银行网点春节营业安排', '尊敬的客户：本行各营业网点在2023年春节期间（1月21日至1月27日）将调整营业时间，详情请查看各网点公告。', '2023-01-15', 2023, 'AN-2023-002', 1),
('系统升级维护通知', '为提升用户体验，我们将于2022年12月18日晚间22:00至次日凌晨2:00进行系统升级维护，期间网上银行、手机银行可能出现短暂不稳定情况。', '2022-12-15', 2022, 'AN-2022-098', 1);

-- 插入产品类型数据
INSERT INTO `product_types` (`type_name`) VALUES
('稳健型'),
('平衡型'),
('进取型'),
('创新型'); 