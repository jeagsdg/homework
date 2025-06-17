<?php
// 包含数据库配置文件和用户API
require_once 'config.php';
require_once 'user_api.php';

// 检查是否为POST请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // 验证输入
    if (empty($username) || empty($password)) {
        $error = '请输入用户名和密码';
    } else {
        // 尝试登录
        $result = login($username, $password);
        
        if ($result['success']) {
            // 登录成功，设置cookie（7天过期）
            setcookie('user_logged_in', 'true', time() + (86400 * 7), '/');
            setcookie('username', $username, time() + (86400 * 7), '/');
            
            // 显示欢迎页面
            header('Location: welcome.php');
            exit;
        } else {
            $error = '用户名或密码错误';
        }
    }
    
    // 登录失败，重定向回登录页面并显示错误
    header('Location: index.html?error=' . urlencode($error));
    exit;
}

// 如果不是POST请求，重定向到首页
header('Location: index.html');
exit;
?> 