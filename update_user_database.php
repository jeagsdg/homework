<?php
// 导入用户表的执行脚本

// 包含数据库配置文件
require_once 'config.php';

// 读取用户表SQL文件
$sql = file_get_contents('init_user_tables.sql');

// 分割SQL语句
$queries = explode(';', $sql);

// 执行每个SQL语句
foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if ($conn->query($query)) {
            echo "执行成功: " . substr($query, 0, 50) . "...<br>";
        } else {
            echo "执行失败: " . substr($query, 0, 50) . "... 错误信息: " . $conn->error . "<br>";
        }
    }
}

echo "<p>用户数据表导入完成！</p>";
echo "<p>测试账号：<br>用户名: admin 密码: 123456<br>用户名: test 密码: 123456</p>";
echo "<p><a href='index.php'>前往首页</a></p>";

$conn->close();
?> 