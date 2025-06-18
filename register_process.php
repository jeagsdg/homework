<?php
// 包含数据库配置文件
require_once 'config.php';
require_once 'user_api.php';

// 检查是否为POST请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // 验证输入
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = '请填写所有必填字段';
    } elseif ($password !== $confirm_password) {
        $error = '两次输入的密码不匹配';
    } else {
        // 检查用户名是否已存在
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = '该用户名已被使用，请选择其他用户名';
        } else {
            // 密码加密
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // 插入新用户
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);
            
            if ($stmt->execute()) {
                // 注册成功，保存用户名到session用于显示成功消息
                session_start();
                $_SESSION['registered_username'] = $username;
                
                // 重定向到注册成功页面
                header('Location: register_success.php');
                exit;
            } else {
                $error = '注册失败，请稍后再试';
            }
        }
    }
    
    // 注册失败，重定向回注册页面并显示错误
    header('Location: register.php?error=' . urlencode($error));
    exit;
}

// 如果不是POST请求，重定向到注册页面
header('Location: register.php');
exit;
?> 