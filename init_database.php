<?php
// 显示所有错误，便于调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>创建金融理财产品数据库 (PDO版)</h1>";

try {
    // 数据库连接配置
    $db_host = '127.0.0.1';
    $db_user = 'root';
    $db_pass = '123456';
    $db_name = 'financial_products';

    // 连接MySQL，不指定数据库名
    echo "<p>尝试连接MySQL服务器...</p>";
    $pdo_no_db = new PDO("mysql:host=$db_host", $db_user, $db_pass, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ));
    
    echo "<p style='color: green;'>MySQL连接成功!</p>";
    
    // 获取MySQL版本
    $version = $pdo_no_db->query("SELECT VERSION() as version")->fetch();
    echo "<p>MySQL版本: " . $version['version'] . "</p>";
    
    // 创建数据库
    echo "<p>尝试创建数据库...</p>";
    $pdo_no_db->exec("CREATE DATABASE IF NOT EXISTS `$db_name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p style='color: green;'>数据库 financial_products 创建成功或已存在</p>";
    
    // 连接到指定数据库
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ));
    
    // 创建用户表
    echo "<p>创建用户表...</p>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE COMMENT '用户名',
        password VARCHAR(255) NOT NULL COMMENT '密码哈希',
        balance DECIMAL(15,2) DEFAULT 50000.00 COMMENT '账户余额',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
        last_login TIMESTAMP NULL COMMENT '最后登录时间'
    )");
    
    // 创建产品表
    echo "<p>创建产品表...</p>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
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
    )");
    
    // 创建投资记录表
    echo "<p>创建投资记录表...</p>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS investments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL COMMENT '用户ID',
        product_id INT NOT NULL COMMENT '产品ID',
        amount DECIMAL(15,2) NOT NULL COMMENT '投资金额',
        expected_revenue DECIMAL(15,2) NOT NULL COMMENT '预期收益',
        invest_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '投资日期',
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");
    
    // 创建银行表
    echo "<p>创建银行表...</p>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS banks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        bank_name VARCHAR(100) NOT NULL COMMENT '银行名称',
        bank_code VARCHAR(20) NOT NULL UNIQUE COMMENT '银行代码',
        logo_url VARCHAR(255) DEFAULT NULL COMMENT '银行logo地址',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
    )");
    
    // 创建产品类型表
    echo "<p>创建产品类型表...</p>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_types (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type_name VARCHAR(50) NOT NULL COMMENT '类型名称',
        type_code VARCHAR(20) NOT NULL UNIQUE COMMENT '类型代码',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
    )");
    
    // 创建风险等级表
    echo "<p>创建风险等级表...</p>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS risk_levels (
        id INT AUTO_INCREMENT PRIMARY KEY,
        level_name VARCHAR(50) NOT NULL COMMENT '等级名称',
        level_code VARCHAR(20) NOT NULL UNIQUE COMMENT '等级代码',
        description TEXT DEFAULT NULL COMMENT '等级描述',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
    )");
    
    // 创建银行理财产品表
    echo "<p>创建银行理财产品表...</p>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS bank_financing_products (
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
    )");
    
    // 创建公告表
    echo "<p>创建公告表...</p>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS announcements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL COMMENT '公告标题',
        content TEXT NOT NULL COMMENT '公告内容',
        publish_date DATE NOT NULL COMMENT '发布日期',
        year INT NOT NULL COMMENT '发布年份',
        reference_code VARCHAR(30) DEFAULT NULL COMMENT '相关代码（如：A2044A5671）',
        is_active TINYINT(1) DEFAULT 1 COMMENT '是否激活',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
    )");
    
    // 插入测试数据
    echo "<p>插入测试数据...</p>";
    
    // 检查是否已有用户数据
    $check_users = $pdo->query("SELECT COUNT(*) as count FROM users")->fetch();
    if ($check_users['count'] == 0) {
        // 插入测试用户，密码为123456
        $pdo->exec("INSERT INTO users (username, password, balance) VALUES
            ('admin', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 100000.00),
            ('test', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 50000.00)");
        echo "<p style='color: green;'>插入测试用户成功</p>";
    } else {
        echo "<p>用户数据已存在，跳过插入</p>";
    }
    
    // 检查是否已有银行数据
    $check_banks = $pdo->query("SELECT COUNT(*) as count FROM banks")->fetch();
    if ($check_banks['count'] == 0) {
        // 插入示例银行数据
        $pdo->exec("INSERT INTO banks (bank_name, bank_code) VALUES
            ('中国工商银行', 'ICBC'),
            ('中国建设银行', 'CCB'),
            ('中国农业银行', 'ABC'),
            ('中国银行', 'BOC'),
            ('交通银行', 'BOCOM'),
            ('招商银行', 'CMB'),
            ('中信银行', 'CITIC'),
            ('兴业银行', 'CIB'),
            ('浦发银行', 'SPDB'),
            ('广发银行', 'CGB')");
        echo "<p style='color: green;'>插入银行数据成功</p>";
    } else {
        echo "<p>银行数据已存在，跳过插入</p>";
    }
    
    // 检查是否已有产品类型数据
    $check_product_types = $pdo->query("SELECT COUNT(*) as count FROM product_types")->fetch();
    if ($check_product_types['count'] == 0) {
        // 插入产品类型数据
        $pdo->exec("INSERT INTO product_types (type_name, type_code) VALUES
            ('固定收益类', 'FIXED'),
            ('浮动收益类', 'FLOAT'),
            ('净值型产品', 'NET'),
            ('结构性存款', 'STRUCTURED'),
            ('货币市场类', 'MONEY')");
        echo "<p style='color: green;'>插入产品类型数据成功</p>";
    } else {
        echo "<p>产品类型数据已存在，跳过插入</p>";
    }
    
    // 检查是否已有风险等级数据
    $check_risk_levels = $pdo->query("SELECT COUNT(*) as count FROM risk_levels")->fetch();
    if ($check_risk_levels['count'] == 0) {
        // 插入风险等级数据
        $pdo->exec("INSERT INTO risk_levels (level_name, level_code, description) VALUES
            ('谨慎型', 'R1', '风险极低，适合极度厌恶风险的投资者'),
            ('稳健型', 'R2', '风险较低，适合对风险较为敏感的投资者'),
            ('平衡型', 'R3', '风险中等，适合风险偏好中等的投资者'),
            ('进取型', 'R4', '风险较高，适合能够承担一定风险的投资者'),
            ('激进型', 'R5', '风险高，适合能够承担高风险的投资者')");
        echo "<p style='color: green;'>插入风险等级数据成功</p>";
    } else {
        echo "<p>风险等级数据已存在，跳过插入</p>";
    }
    
    // 检查是否已有银行理财产品数据
    $check_bank_products = $pdo->query("SELECT COUNT(*) as count FROM bank_financing_products")->fetch();
    if ($check_bank_products['count'] == 0) {
        try {
            // 插入示例银行理财产品数据
            $pdo->exec("INSERT INTO bank_financing_products (product_code, product_name, bank_id, product_type_id, risk_level_id, annual_rate, min_annual_rate, max_annual_rate, investment_period, min_investment, max_investment, increment_amount, total_amount, remaining_amount, start_date, end_date, sale_start_date, sale_end_date, product_status, is_featured) VALUES
                ('GS2023001', '工银财富升级版2023第1期', 1, 1, 2, 3.85, 3.50, 4.10, 180, 50000.00, 5000000.00, 1000.00, 10000000.00, 3500000.00, '2023-06-01', '2023-11-28', '2023-05-15', '2023-05-30', '已结束', 1),
                ('CCB20230621', '乾元-安鑫（按日）开放式净值型产品', 2, 3, 2, 3.65, 3.30, 3.90, 90, 10000.00, NULL, 1000.00, 50000000.00, 12000000.00, '2023-06-25', '2023-09-23', '2023-06-10', '2023-06-24', '已结束', 0),
                ('ABC-ANXIN-2307', '农银安鑫90天人民币理财产品', 3, 1, 1, 3.35, 3.10, 3.50, 90, 5000.00, NULL, 1000.00, 100000000.00, 42000000.00, '2023-07-10', '2023-10-08', '2023-07-01', '2023-07-08', '已结束', 0),
                ('BOC-ZSY-2309', '中银日积月累-收益累进', 4, 2, 3, 3.90, 3.60, 4.10, 365, 100000.00, NULL, 10000.00, 20000000.00, 8000000.00, '2023-09-15', '2024-09-14', '2023-09-01', '2023-09-14', '募集中', 1),
                ('BOCOM-PJT-2310', '蕴通财富稳得利', 5, 1, 2, 3.55, 3.30, 3.70, 180, 50000.00, 3000000.00, 10000.00, 30000000.00, 15000000.00, '2023-10-20', '2024-04-18', '2023-10-08', '2023-10-18', '募集中', 0)");
            echo "<p style='color: green;'>插入银行理财产品数据成功</p>";
        } catch (PDOException $e) {
            echo "<p style='color: orange;'>插入银行理财产品数据警告: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>银行理财产品数据已存在，跳过插入</p>";
    }
    
    // 检查是否已有产品数据
    $check_products = $pdo->query("SELECT COUNT(*) as count FROM products")->fetch();
    if ($check_products['count'] == 0) {
        // 插入示例产品数据
        $pdo->exec("INSERT INTO products (product_code, product_name, annual_rate, investment_period, min_investment, increment_amount, start_date, end_date, total_amount, remaining_amount, deadline, is_active) VALUES
            ('200110', '安富 200110期', 3.07, 90, 10000.00, 1000.00, '2020-03-25', '2020-06-25', 1000000.00, 300000.00, '2020-03-20 23:59:59', 1)");
        echo "<p style='color: green;'>插入产品数据成功</p>";
    } else {
        echo "<p>产品数据已存在，跳过插入</p>";
    }
    
    // 检查是否已有公告数据
    $check_announcements = $pdo->query("SELECT COUNT(*) as count FROM announcements")->fetch();
    if ($check_announcements['count'] == 0) {
        // 插入示例公告数据
        $pdo->exec("INSERT INTO announcements (title, content, publish_date, year, reference_code) VALUES
            ('中信理财之双鑫港债净值型理财产品（理财代码：A2044A5671）成立公告', '本公告为该理财产品的成立公告，包含产品基本信息、风险提示等内容...', '2021-04-06', 2021, 'A2044A5671'),
            ('华澳理财之双鑫港债净值型理财产品（理财代码：A204A5670）成立公告', '本公告为该理财产品的成立公告，包含产品基本信息、风险提示等内容...', '2021-03-09', 2021, 'A204A5670'),
            ('中信理财之双鑫港债净值型理财产品（理财代码：A204A5669）成立公告', '本公告为该理财产品的成立公告，包含产品基本信息、风险提示等内容...', '2021-01-13', 2021, 'A204A5669'),
            ('招商银行理财产品发行公告（2020年第1期）', '招商银行发行2020年第1期理财产品，详细信息如下...', '2020-12-25', 2020, 'CMBC20201225'),
            ('工商银行理财产品说明书（2020-ICBC-112）', '工商银行理财产品说明书，产品代码：2020-ICBC-112...', '2020-11-20', 2020, '2020-ICBC-112'),
            ('中国银行理财产品指数发布公告', '中国银行关于发布理财产品指数的公告...', '2010-12-10', 2010, 'BOC-INDEX-2010'),
            ('交通银行理财产品投资组合调整公告', '交通银行关于调整理财产品投资组合的公告...', '2010-09-25', 2010, 'BOCOM-PORT-2010'),
            ('中信银行理财产品违约处理规则', '中信银行理财产品违约情况处理规则及投资者保护措施...', '2010-06-18', 2010, 'CITIC-DEFAULT-2010')");
        echo "<p style='color: green;'>插入公告数据成功</p>";
    } else {
        echo "<p>公告数据已存在，跳过插入</p>";
    }
    
    // 检查必要的表是否存在
    $required_tables = ['users', 'products', 'investments', 'banks', 'product_types', 'risk_levels', 'bank_financing_products', 'announcements'];
    $missing_tables = [];
    
    foreach ($required_tables as $table) {
        $check_table = $pdo->query("SHOW TABLES LIKE '$table'")->fetchAll();
        if (count($check_table) == 0) {
            $missing_tables[] = $table;
        }
    }
    
    if (empty($missing_tables)) {
        echo "<h3 style='color: green;'>所有必要的表已成功创建!</h3>";
    } else {
        echo "<h3 style='color: red;'>以下表未能创建: " . implode(', ', $missing_tables) . "</h3>";
    }
    
    echo "<h2 style='color: green;'>数据库初始化成功!</h2>";
    echo "<p>测试账号：</p>";
    echo "<p>用户名: admin, 密码: 123456</p>";
    echo "<p>用户名: test, 密码: 123456</p>";
    
    echo "<p><a href='index.php'>返回首页</a></p>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>错误: " . $e->getMessage() . "</h2>";
    echo "<p>请确保MySQL服务已启动，且用户名和密码正确。</p>";
}
?> 