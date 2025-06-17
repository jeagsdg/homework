<?php
// 包含用户API
require_once 'user_api.php';

// 检查用户是否已登录
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // 未登录，重定向到首页
    header('Location: index.html');
    exit;
}

// 获取用户名
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录成功</title>
    <link rel="stylesheet" href="default.css">
    <style>
        .welcome-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 30px;
            text-align: center;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .welcome-title {
            color: #c22;
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        .welcome-message {
            font-size: 18px;
            color: #333;
            margin-bottom: 30px;
        }
        
        .redirect-message {
            color: #666;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
    <meta http-equiv="refresh" content="3;url=index.html">
</head>
<body>
    <div class="welcome-container">
        <h1 class="welcome-title">登录成功</h1>
        <div class="welcome-message">
            欢迎 <strong><?php echo htmlspecialchars($username); ?></strong> 用户
        </div>
        <div class="redirect-message">
            3秒后自动跳转到首页...
        </div>
    </div>

    <script>
        var counter = 3;
        setInterval(function() {
            counter--;
            if (counter >= 0) {
                document.querySelector('.redirect-message').innerHTML = counter + '秒后自动跳转到首页...';
            }
            if (counter === 0) {
                window.location.href = 'index.html';
            }
        }, 1000);
    </script>
</body>
</html> 