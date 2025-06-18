<?php
// 显示所有错误，便于调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>创建金融理财产品数据库</h1>";

try {
    // 连接MySQL，不指定数据库名
    $conn = new mysqli('127.0.0.1', 'root', '123456');
    
    // 检查连接
    if ($conn->connect_error) {
        throw new Exception("连接失败: " . $conn->connect_error);
    } else {
        echo "<p style='color: green;'>MySQL连接成功!</p>";
    }
    
    // 显示MySQL版本
    $version_result = $conn->query("SELECT VERSION() as version");
    if ($version_result) {
        $version_row = $version_result->fetch_assoc();
        echo "<p>MySQL版本: " . $version_row['version'] . "</p>";
    }
    
    // 创建数据库
    $sql = "CREATE DATABASE IF NOT EXISTS financial_products DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>数据库 financial_products 创建成功或已存在</p>";
    } else {
        throw new Exception("创建数据库失败: " . $conn->error);
    }
    
    // 选择数据库
    $conn->select_db("financial_products");
    
    // 读取SQL文件
    $sql_file = file_get_contents('database_init.sql');
    
    // 分割SQL语句
    $queries = explode(';', $sql_file);
    
    // 执行每个SQL语句
    $success = true;
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            if ($conn->query($query)) {
                echo "<p style='color: green;'>执行成功: " . htmlspecialchars(substr($query, 0, 50)) . "...</p>";
            } else {
                echo "<p style='color: orange;'>执行警告: " . htmlspecialchars(substr($query, 0, 50)) . "... 错误信息: " . $conn->error . "</p>";
                // 不将其视为失败，继续执行
            }
        }
    }
    
    // 检查必要的表是否存在
    $required_tables = ['users', 'products', 'investments', 'banks', 'product_types', 'risk_levels', 'bank_financing_products', 'announcements'];
    $missing_tables = [];
    
    foreach ($required_tables as $table) {
        $check_table = $conn->query("SHOW TABLES LIKE '$table'");
        if ($check_table->num_rows == 0) {
            $missing_tables[] = $table;
        }
    }
    
    if (empty($missing_tables)) {
        echo "<h3 style='color: green;'>所有必要的表已成功创建!</h3>";
    } else {
        echo "<h3 style='color: red;'>以下表未能创建: " . implode(', ', $missing_tables) . "</h3>";
        $success = false;
    }
    
    // 检查测试用户是否存在
    $check_users = $conn->query("SELECT COUNT(*) as count FROM users");
    if ($check_users && $check_users->fetch_assoc()['count'] > 0) {
        echo "<p style='color: green;'>用户数据已存在</p>";
        echo "<p>测试账号：</p>";
        echo "<p>用户名: admin, 密码: 123456</p>";
        echo "<p>用户名: test, 密码: 123456</p>";
    } else {
        echo "<p style='color: red;'>用户数据未能正确创建</p>";
        $success = false;
    }
    
    if ($success) {
        echo "<h2 style='color: green;'>数据库初始化成功!</h2>";
    } else {
        echo "<h2 style='color: orange;'>数据库初始化完成，但有部分警告，请检查上面的信息。</h2>";
    }
    
    // 返回链接
    echo "<p><a href='index.php'>返回首页</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>错误: " . $e->getMessage() . "</h2>";
}

// 关闭数据库连接
if (isset($conn)) {
    $conn->close();
}
?> 