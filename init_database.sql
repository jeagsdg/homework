-- 创建数据库
CREATE DATABASE IF NOT EXISTS financial_products DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE financial_products;

-- 创建金融产品表
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_code VARCHAR(20) NOT NULL COMMENT '产品编号',
    product_name VARCHAR(100) NOT NULL COMMENT '产品名称',
    annual_rate DECIMAL(5,2) NOT NULL COMMENT '预期年化利率(%)',
    investment_period INT NOT NULL COMMENT '投资期限(天)',
    min_investment DECIMAL(12,2) NOT NULL COMMENT '起投金额(元)',
    increment_amount DECIMAL(12,2) NOT NULL COMMENT '递增金额(元)',
    start_date DATE NOT NULL COMMENT '项目起息日',
    end_date DATE NOT NULL COMMENT '项目到期日',
    total_amount DECIMAL(12,2) NOT NULL COMMENT '总可投金额(元)',
    remaining_amount DECIMAL(12,2) NOT NULL COMMENT '剩余可投金额(元)',
    deadline DATETIME NOT NULL COMMENT '投资截止时间',
    is_active TINYINT(1) DEFAULT 1 COMMENT '是否激活'
);

-- 创建用户表
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE COMMENT '用户名',
    password VARCHAR(255) NOT NULL COMMENT '密码',
    balance DECIMAL(12,2) DEFAULT 0.00 COMMENT '账户余额',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
);

-- 创建投资记录表
CREATE TABLE IF NOT EXISTS investments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT '用户ID',
    product_id INT NOT NULL COMMENT '产品ID',
    amount DECIMAL(12,2) NOT NULL COMMENT '投资金额',
    expected_revenue DECIMAL(12,2) NOT NULL COMMENT '预计收益',
    invest_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '投资时间',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- 创建银行表
CREATE TABLE IF NOT EXISTS banks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bank_name VARCHAR(100) NOT NULL COMMENT '银行名称',
    bank_code VARCHAR(20) NOT NULL UNIQUE COMMENT '银行代码',
    logo_url VARCHAR(255) DEFAULT NULL COMMENT '银行logo地址',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
);

-- 创建产品类型表
CREATE TABLE IF NOT EXISTS product_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL COMMENT '类型名称',
    type_code VARCHAR(20) NOT NULL UNIQUE COMMENT '类型代码',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
);

-- 创建风险等级表
CREATE TABLE IF NOT EXISTS risk_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level_name VARCHAR(50) NOT NULL COMMENT '等级名称',
    level_code VARCHAR(20) NOT NULL UNIQUE COMMENT '等级代码',
    description TEXT DEFAULT NULL COMMENT '等级描述',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
);

-- 创建银行理财产品表
CREATE TABLE IF NOT EXISTS bank_financing_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_code VARCHAR(20) NOT NULL UNIQUE COMMENT '产品编号',
    product_name VARCHAR(100) NOT NULL COMMENT '产品名称',
    bank_id INT NOT NULL COMMENT '所属银行ID',
    product_type_id INT NOT NULL COMMENT '产品类型ID',
    risk_level_id INT NOT NULL COMMENT '风险等级ID',
    annual_rate DECIMAL(5,2) NOT NULL COMMENT '预期年化利率(%)',
    min_annual_rate DECIMAL(5,2) DEFAULT NULL COMMENT '最低预期年化利率(%)',
    max_annual_rate DECIMAL(5,2) DEFAULT NULL COMMENT '最高预期年化利率(%)',
    investment_period INT NOT NULL COMMENT '投资期限(天)',
    min_investment DECIMAL(12,2) NOT NULL COMMENT '起投金额(元)',
    max_investment DECIMAL(12,2) DEFAULT NULL COMMENT '最高投资金额(元)',
    increment_amount DECIMAL(12,2) NOT NULL COMMENT '递增金额(元)',
    total_amount DECIMAL(12,2) NOT NULL COMMENT '总募集金额(元)',
    remaining_amount DECIMAL(12,2) NOT NULL COMMENT '剩余可投金额(元)',
    start_date DATE NOT NULL COMMENT '起息日',
    end_date DATE NOT NULL COMMENT '到期日',
    sale_start_date DATE NOT NULL COMMENT '发售起始日',
    sale_end_date DATE NOT NULL COMMENT '发售截止日',
    product_status ENUM('预售中', '募集中', '已结束', '已售罄') DEFAULT '募集中' COMMENT '产品状态',
    is_featured TINYINT(1) DEFAULT 0 COMMENT '是否推荐',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    FOREIGN KEY (bank_id) REFERENCES banks(id),
    FOREIGN KEY (product_type_id) REFERENCES product_types(id),
    FOREIGN KEY (risk_level_id) REFERENCES risk_levels(id)
);

-- 插入示例银行数据
INSERT INTO banks (bank_name, bank_code) VALUES
('中国工商银行', 'ICBC'),
('中国建设银行', 'CCB'),
('中国农业银行', 'ABC'),
('中国银行', 'BOC'),
('交通银行', 'BOCOM'),
('招商银行', 'CMB'),
('中信银行', 'CITIC'),
('兴业银行', 'CIB'),
('浦发银行', 'SPDB'),
('广发银行', 'CGB');

-- 插入产品类型数据
INSERT INTO product_types (type_name, type_code) VALUES
('固定收益类', 'FIXED'),
('浮动收益类', 'FLOAT'),
('净值型产品', 'NET'),
('结构性存款', 'STRUCTURED'),
('货币市场类', 'MONEY');

-- 插入风险等级数据
INSERT INTO risk_levels (level_name, level_code, description) VALUES
('谨慎型', 'R1', '风险极低，适合极度厌恶风险的投资者'),
('稳健型', 'R2', '风险较低，适合对风险较为敏感的投资者'),
('平衡型', 'R3', '风险中等，适合风险偏好中等的投资者'),
('进取型', 'R4', '风险较高，适合能够承担一定风险的投资者'),
('激进型', 'R5', '风险高，适合能够承担高风险的投资者');

-- 插入示例银行理财产品数据
INSERT INTO bank_financing_products (product_code, product_name, bank_id, product_type_id, risk_level_id, annual_rate, min_annual_rate, max_annual_rate, investment_period, min_investment, max_investment, increment_amount, total_amount, remaining_amount, start_date, end_date, sale_start_date, sale_end_date, product_status, is_featured) VALUES
('GS2023001', '工银财富升级版2023第1期', 1, 1, 2, 3.85, 3.50, 4.10, 180, 50000.00, 5000000.00, 1000.00, 10000000.00, 3500000.00, '2023-06-01', '2023-11-28', '2023-05-15', '2023-05-30', '已结束', 1),
('CCB20230621', '乾元-安鑫（按日）开放式净值型产品', 2, 3, 2, 3.65, 3.30, 3.90, 90, 10000.00, NULL, 1000.00, 50000000.00, 12000000.00, '2023-06-25', '2023-09-23', '2023-06-10', '2023-06-24', '已结束', 0),
('ABC-ANXIN-2307', '农银安鑫90天人民币理财产品', 3, 1, 1, 3.35, 3.10, 3.50, 90, 5000.00, NULL, 1000.00, 100000000.00, 42000000.00, '2023-07-10', '2023-10-08', '2023-07-01', '2023-07-08', '已结束', 0),
('BOC-ZSY-2309', '中银日积月累-收益累进', 4, 2, 3, 3.90, 3.60, 4.10, 365, 100000.00, NULL, 10000.00, 20000000.00, 8000000.00, '2023-09-15', '2024-09-14', '2023-09-01', '2023-09-14', '募集中', 1),
('BOCOM-PJT-2310', '蕴通财富稳得利', 5, 1, 2, 3.55, 3.30, 3.70, 180, 50000.00, 3000000.00, 10000.00, 30000000.00, 15000000.00, '2023-10-20', '2024-04-18', '2023-10-08', '2023-10-18', '募集中', 0),
('CMB-YJB-2311', '朝朝盈开放式净值型理财计划', 6, 3, 3, 4.10, 3.80, 4.30, 240, 50000.00, NULL, 1000.00, 100000000.00, 60000000.00, '2023-11-15', '2024-07-12', '2023-11-01', '2023-11-14', '募集中', 1),
('CITIC-JX-2311', '信银理财安逸系列', 7, 2, 2, 3.75, 3.50, 3.90, 120, 10000.00, NULL, 1000.00, 50000000.00, 20000000.00, '2023-11-20', '2024-03-19', '2023-11-10', '2023-11-19', '募集中', 0),
('CIB-JZ-2312', '兴业银行添利90天净值型理财产品', 8, 3, 2, 3.50, 3.30, 3.70, 90, 10000.00, NULL, 1000.00, 80000000.00, 25000000.00, '2023-12-10', '2024-03-09', '2023-12-01', '2023-12-09', '预售中', 0),
('SPDB-SR-2312', '浦发安盈利系列', 9, 1, 1, 3.30, 3.00, 3.50, 180, 5000.00, NULL, 1000.00, 150000000.00, 90000000.00, '2023-12-25', '2024-06-22', '2023-12-15', '2023-12-24', '预售中', 1),
('CGB-BL-2401', '广银理财乐盈定开理财产品', 10, 2, 3, 4.00, 3.70, 4.20, 365, 100000.00, 5000000.00, 10000.00, 200000000.00, 200000000.00, '2024-01-10', '2025-01-09', '2023-12-25', '2024-01-09', '预售中', 0),
('ICBC-XL-2312', '工银理财"随心存"', 1, 2, 2, 3.70, 3.50, 3.90, 180, 30000.00, NULL, 5000.00, 120000000.00, 70000000.00, '2023-12-15', '2024-06-12', '2023-12-01', '2023-12-14', '募集中', 1),
('CCB-FF-2312', '建信理财"福满金"', 2, 1, 1, 3.40, 3.20, 3.60, 120, 10000.00, NULL, 1000.00, 100000000.00, 40000000.00, '2023-12-20', '2024-04-18', '2023-12-05', '2023-12-19', '募集中', 0),
('ABC-HY-2312', '农银"汇盈"系列理财产品', 3, 3, 2, 3.60, 3.40, 3.80, 180, 50000.00, NULL, 10000.00, 80000000.00, 30000000.00, '2023-12-25', '2024-06-22', '2023-12-10', '2023-12-24', '募集中', 1);

-- 插入示例产品数据
INSERT INTO products (product_code, product_name, annual_rate, investment_period, min_investment, increment_amount, start_date, end_date, total_amount, remaining_amount, deadline, is_active) VALUES
('200110', '安富 200110期', 3.07, 90, 10000.00, 1000.00, '2020-03-25', '2020-06-25', 1000000.00, 300000.00, '2020-03-20 23:59:59', 1);

-- 插入示例用户数据
INSERT INTO users (username, password, balance) VALUES
('test', '$2y$10$YCj5klnlLV9M8hOG4fvmReMUaXBB2UmGZUQxaIVE1a5JR5qE4JdCy', 50000.00); -- 密码为"password" 