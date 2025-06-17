<?php
// 数据库连接配置
$db_host = 'localhost';
$db_user = 'root@localhost';
$db_pass = '123456';
$db_name = 'financial_products';

// 创建数据库连接
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 设置字符集
$conn->set_charset("utf8");
?> 