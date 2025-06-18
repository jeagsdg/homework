<?php
// 显示所有错误，便于调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 检查mysqli扩展是否加载
if (!extension_loaded('mysqli')) {
    // 设置HTTP状态码为500
    http_response_code(500);
    
    // 设置响应头为JSON
    header('Content-Type: application/json; charset=utf-8');
    
    // 构造详细的错误信息
    $error_response = [
        'error' => 'mysqli扩展未加载',
        'message' => 'PHP mysqli扩展未启用，无法连接到MySQL数据库',
        'solution' => '请在phpStudy控制面板中启用mysqli扩展，或修改php.ini配置文件，取消注释extension=mysqli行'
    ];
    
    // 以JSON格式输出错误并终止脚本
    die(json_encode($error_response, JSON_UNESCAPED_UNICODE));
}

// 数据库连接配置
$db_host = 'localhost'; //
$db_user = 'k'; // 
$db_pass = '123456'; // 
$db_name = 'financial_products';

// 禁用默认的错误报告，全部使用异常来捕获
// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // 在某些PHP版本中可能不可用

try {
    // 尝试连接MySQL
    $conn = new mysqli($db_host, $db_user, $db_pass);
    
    // 如果连接成功，创建数据库（如果不存在）
    $conn->query("CREATE DATABASE IF NOT EXISTS `$db_name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    
    // 选择数据库
    $conn->select_db($db_name);
    
    // 设置SQL Mode为非严格模式
    $conn->query("SET sql_mode=''");
    
    // 设置字符集
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    // 捕获数据库连接错误
    
    // 设置HTTP状态码为500
    http_response_code(500);
    
    // 设置响应头为JSON
    header('Content-Type: application/json; charset=utf-8');
    
    // 构造详细的错误信息
    $error_response = [
        'error' => '数据库连接失败',
        'message' => $e->getMessage(),
        'details' => [
            'host' => $db_host,
            'user' => $db_user,
            'password_used' => !empty($db_pass)
        ],
        'suggestion' => '请检查您的MySQL用户名和密码是否正确。在phpStudy中，MySQL 5.7的默认密码通常是 root 或为空。'
    ];
    
    // 以JSON格式输出错误并终止脚本
    die(json_encode($error_response, JSON_UNESCAPED_UNICODE));
}
?> 