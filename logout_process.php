<?php
// 包含用户API
require_once 'user_api.php';

// 执行登出
logout();

// 清除cookies
setcookie('user_logged_in', '', time() - 3600, '/');
setcookie('username', '', time() - 3600, '/');

// 重定向到首页
header('Location: index.html');
exit;
?> 