<?php
// 显示所有错误，便于调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html><html><head><title>数据库初始化</title>";
echo "<style>body { font-family: monospace, sans-serif; padding: 20px; } .success { color: green; } .error { color: red; } .info { color: blue; }</style>";
echo "</head><body><h1>数据库初始化脚本</h1>";

// 包含数据库配置文件
require_once 'config.php';

// --- 检查数据库连接 ---
if ($conn->connect_error) {
    die("<p class='error'>无法通过 config.php 连接到数据库: " . htmlspecialchars($conn->connect_error) . "</p></body></html>");
}
echo "<p class='success'>通过 config.php 成功连接到数据库 '" . htmlspecialchars($db_name) . "'</p>";
echo "<hr>";


// --- SQL语句定义 ---
// 注意：这里我们使用 IF NOT EXISTS 来避免重复创建时出错

// 1. 用户表
$sql_users = "
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
";

// 2. 产品表
$sql_products = "
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
";

// 3. 公告表
$sql_announcements = "
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
";

// 4. 产品类型表 (用于筛选)
$sql_product_types = "
CREATE TABLE IF NOT EXISTS `product_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='产品类型表';
";

// 5. 投资记录表
$sql_investments = "
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
";


$tables_to_create = [
    'users' => $sql_users,
    'products' => $sql_products,
    'announcements' => $sql_announcements,
    'product_types' => $sql_product_types,
    'investments' => $sql_investments
];

// --- 执行表创建 ---
echo "<h2>开始创建数据表...</h2>";
foreach ($tables_to_create as $table_name => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>数据表 `{$table_name}` 创建成功或已存在。</p>";
    } else {
        echo "<p class='error'>创建数据表 `{$table_name}` 失败: " . htmlspecialchars($conn->error) . "</p>";
    }
}
echo "<hr>";


// --- 插入初始数据 ---
echo "<h2>开始插入初始数据 (如果不存在)...</h2>";

// 辅助函数: 检查表中是否已有数据
function checkDataExists($conn, $table) {
    $result = $conn->query("SELECT id FROM `{$table}` LIMIT 1");
    return $result && $result->num_rows > 0;
}

// 插入用户
if (!checkDataExists($conn, 'users')) {
    $hashed_password = password_hash('123456', PASSWORD_DEFAULT);
    $sql_insert_user = "INSERT INTO `users` (`username`, `password`, `balance`) VALUES ('testuser', '{$hashed_password}', '100000.00')";
    if ($conn->query($sql_insert_user)) {
        echo "<p class='success'>初始用户 'testuser' (密码: 123456) 插入成功。</p>";
    } else {
        echo "<p class='error'>插入初始用户失败: " . htmlspecialchars($conn->error) . "</p>";
    }
} else {
    echo "<p class='info'>`users` 表中已存在数据，跳过插入。</p>";
}


// 插入产品
if (!checkDataExists($conn, 'products')) {
    $sql_insert_products = "
    INSERT INTO `products` (`product_code`, `product_name`, `product_type`, `annual_rate`, `investment_period`, `min_investment`, `increment_amount`, `start_date`, `end_date`, `total_amount`, `remaining_amount`, `deadline`, `is_active`) VALUES
    ('P202401', '稳健增利一号', '稳健型', '4.50', 90, 1000.00, 100.00, '2024-01-01', '2024-03-31', 500000.00, 200000.00, '2024-01-15 23:59:59', 1),
    ('P202402', '高收益混合二号', '进取型', '7.80', 180, 5000.00, 500.00, '2024-01-10', '2024-07-08', 1000000.00, 800000.00, '2024-02-01 23:59:59', 1),
    ('P202403', '平衡理财三号', '平衡型', '5.60', 120, 3000.00, 300.00, '2024-02-01', '2024-06-01', 800000.00, 400000.00, '2024-02-15 23:59:59', 1),
    ('P202404', '短期理财四号', '稳健型', '3.80', 30, 1000.00, 100.00, '2024-02-15', '2024-03-15', 300000.00, 100000.00, '2024-02-10 23:59:59', 1),
    ('200110', '安富 200110期', '稳健型', '3.07', 90, 10000.00, 1000.00, '2024-03-25', '2024-06-25', 1000000.00, 300000.00, '2024-03-20 23:59:59', 1);
    ";
    if ($conn->query($sql_insert_products)) {
        echo "<p class='success'>初始产品数据插入成功。</p>";
    } else {
        echo "<p class='error'>插入初始产品失败: " . htmlspecialchars($conn->error) . "</p>";
    }
} else {
    echo "<p class='info'>`products` 表中已存在数据，跳过插入。</p>";
}

// 插入公告
if (!checkDataExists($conn, 'announcements')) {
    $sql_insert_announcements = "
    INSERT INTO `announcements` (`title`, `content`, `publish_date`, `year`, `reference_code`, `is_active`) VALUES
    ('关于2024年春节假期系统维护的公告', '尊敬的客户：为了提供更优质的服务，我司将于2024年春节期间对系统进行维护升级。维护时间为2024年2月10日凌晨00:00至06:00，期间将暂停所有在线交易服务。感谢您的理解与支持！', '2024-02-01', 2024, 'AN-2024-001', 1),
    ('2023年度报告发布', '2023年度报告已发布，请各位投资者查阅。报告内容包括2023年度的经营状况、财务指标以及2024年的投资策略和规划。', '2024-03-15', 2024, 'AN-2024-002', 1),
    ('关于新增线上理财产品的通知', '我行将于2023年12月1日起新增三款线上理财产品，包括\'安富宝\'、\'稳利盈\'和\'智选理财\'，满足不同风险偏好投资者的需求。', '2023-11-28', 2023, 'AN-2023-045', 1),
    ('银行网点春节营业安排', '尊敬的客户：本行各营业网点在2023年春节期间（1月21日至1月27日）将调整营业时间，详情请查看各网点公告。', '2023-01-15', 2023, 'AN-2023-002', 1),
    ('系统升级维护通知', '为提升用户体验，我们将于2022年12月18日晚间22:00至次日凌晨2:00进行系统升级维护，期间网上银行、手机银行可能出现短暂不稳定情况。', '2022-12-15', 2022, 'AN-2022-098', 1)
    ";
    if ($conn->query($sql_insert_announcements)) {
        echo "<p class='success'>初始公告数据插入成功。</p>";
    } else {
        echo "<p class='error'>插入初始公告失败: " . htmlspecialchars($conn->error) . "</p>";
    }
} else {
    echo "<p class='info'>`announcements` 表中已存在数据，跳过插入。</p>";
}

// 插入产品类型
if (!checkDataExists($conn, 'product_types')) {
    $sql_insert_types = "INSERT INTO `product_types` (`type_name`) VALUES ('稳健型'), ('平衡型'), ('进取型')";
     if ($conn->query($sql_insert_types)) {
        echo "<p class='success'>初始产品类型数据插入成功。</p>";
    } else {
        echo "<p class='error'>插入初始产品类型失败: " . htmlspecialchars($conn->error) . "</p>";
    }
} else {
    echo "<p class='info'>`product_types` 表中已存在数据，跳过插入。</p>";
}


echo "<hr>";
echo "<h2>初始化完成！</h2>";
echo "<p>所有必要的数据表和初始数据都已设置完毕。</p>";
echo "<p>现在您可以 <a href='index.php'>返回首页</a> 或访问其他页面，它们应该可以正常工作了。</p>";

$conn->close();

echo "</body></html>";
?> 