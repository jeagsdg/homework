<?php
// 显示所有错误，便于调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 包含数据库配置文件和用户API
require_once 'config.php';
require_once 'user_api.php';

// 检查是否为POST请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
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
                $error = '登录失败: ' . $result['message'];
            }
        }
        
        // 登录失败，重定向回登录页面并显示错误
        header('Location: index.html?error=' . urlencode($error));
    } catch (Exception $e) {
        // 记录错误
        error_log("登录处理错误: " . $e->getMessage());
        
        // 显示错误
        header('Location: index.html?error=' . urlencode('登录过程中发生错误: ' . $e->getMessage()));
    }
    exit;
}

// 如果不是POST请求，重定向到首页
header('Location: index.html');
exit;
?> 