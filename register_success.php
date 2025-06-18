<?php
// 启动会话（如果尚未启动）
session_start();

// 获取注册成功的用户名
$username = isset($_SESSION['registered_username']) ? $_SESSION['registered_username'] : '您的';

// 清除会话中的注册用户名
unset($_SESSION['registered_username']);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册成功</title>
    <link rel="stylesheet" href="default.css">
    <style>
        .success-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 30px;
            text-align: center;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .success-title {
            color: #389e0d;
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        .success-message {
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
    <div class="success-container">
        <h1 class="success-title">注册成功</h1>
        <div class="success-message">
            <strong><?php echo htmlspecialchars($username); ?></strong> 用户已经注册成功
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