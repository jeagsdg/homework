<?php
// 项目初始化和数据库设置脚本

// 显示所有错误，便于调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>金融理财产品系统初始化</h1>";

// 检查配置文件
if (!file_exists('config.php')) {
    echo "<div style='color: red; font-weight: bold;'>错误：找不到config.php配置文件，请先创建该文件。</div>";
    exit;
}

// 包含数据库配置文件
require_once 'config.php';

// 显示数据库连接信息
echo "<h2>数据库连接信息</h2>";
echo "<p>主机: {$db_host}</p>";
echo "<p>用户: {$db_user}</p>";
echo "<p>数据库名: {$db_name}</p>";

// 测试数据库连接
echo "<h2>测试数据库连接</h2>";
if ($conn->connect_error) {
    echo "<div style='color: red; font-weight: bold;'>连接失败: {$conn->connect_error}</div>";
    exit;
} else {
    echo "<div style='color: green;'>数据库连接成功!</div>";
}

// 询问用户是否初始化数据库
echo "<h2>数据库初始化</h2>";
echo "<p>此操作将创建所需的表结构并导入示例数据。</p>";
echo "<form method='post'>";
echo "<input type='hidden' name='action' value='init_db'>";
echo "<input type='submit' value='初始化数据库' style='padding: 8px 16px; background-color: #4CAF50; color: white; border: none; cursor: pointer;'>";
echo "</form>";

// 处理数据库初始化请求
if (isset($_POST['action']) && $_POST['action'] === 'init_db') {
    echo "<h3>正在初始化数据库...</h3>";
    
    // 读取SQL文件
    $sql = file_get_contents('database_init.sql');
    
    // 分割SQL语句
    $queries = explode(';', $sql);
    
    // 执行每个SQL语句
    $success = true;
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            if ($conn->query($query)) {
                echo "<p style='color: green;'>执行成功: " . htmlspecialchars(substr($query, 0, 50)) . "...</p>";
            } else {
                echo "<p style='color: red;'>执行失败: " . htmlspecialchars(substr($query, 0, 50)) . "... 错误信息: " . $conn->error . "</p>";
                $success = false;
            }
        }
    }
    
    if ($success) {
        echo "<h3 style='color: green;'>数据库初始化成功!</h3>";
        echo "<p>测试账号：</p>";
        echo "<p>用户名: admin, 密码: 123456</p>";
        echo "<p>用户名: test, 密码: 123456</p>";
    } else {
        echo "<h3 style='color: red;'>数据库初始化过程中出现错误，请检查上面的错误信息。</h3>";
    }
}

// 检查必要文件是否存在
echo "<h2>文件检查</h2>";
$requiredFiles = [
    'index.php',
    'financing.php',
    'announcement.php',
    'announcement_detail.php',
    'config.php',
    'user_api.php',
    'check_login.php',
    'login_process.php',
    'logout_process.php',
    'register_process.php',
    'register.php',
    'welcome.php',
    'register_success.php',
    'get_product_data.php',
    'get_product_types.php',
    'get_financing_products.php',
    'get_announcements.php',
    'get_announcement_years.php',
    'default.css',
    'default.js'
];

$missingFiles = [];
foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        $missingFiles[] = $file;
    }
}

if (empty($missingFiles)) {
    echo "<p style='color: green;'>所有必要文件均已存在!</p>";
} else {
    echo "<p style='color: red;'>以下文件缺失：</p><ul>";
    foreach ($missingFiles as $file) {
        echo "<li>" . htmlspecialchars($file) . "</li>";
    }
    echo "</ul>";
}

// 显示访问链接
echo "<h2>访问项目</h2>";
$host = $_SERVER['HTTP_HOST'];
$folder = dirname($_SERVER['PHP_SELF']);
$baseUrl = "http://{$host}{$folder}";
if (substr($baseUrl, -1) !== '/') {
    $baseUrl .= '/';
}

echo "<p>初始化完成后，您可以通过以下链接访问项目：</p>";
echo "<ul>";
echo "<li><a href='{$baseUrl}index.php' target='_blank'>首页</a></li>";
echo "<li><a href='{$baseUrl}financing.php' target='_blank'>银行理财页面</a></li>";
echo "<li><a href='{$baseUrl}announcement.php' target='_blank'>理财公告页面</a></li>";
echo "</ul>";
echo "<p style='color: orange;'>注意：确保使用index.php而不是index.html来访问首页，以确保用户登录功能正常工作。</p>";

// 关闭数据库连接
$conn->close();
?> 