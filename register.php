<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户注册</title>
    <link rel="stylesheet" href="default.css">
    <style>
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .register-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .btn-submit {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #c22;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        
        .btn-submit:hover {
            background-color: #a11;
        }
        
        .error-message {
            color: #c22;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: #c22;
            text-decoration: none;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="icon_box">
            <div class="icon_circleBox">
                <div class="icon_qq"></div>
            </div>
        </div>
        <div class="icon_box">
            <div class="icon_circleBox">
                <div class="icon_weibo_small"></div>
            </div>
        </div>
        <div class="icon_box">
            <div class="icon_circleBox" style="background-color: #2F2F2F;">
                <div class="icon_up"><a href="#banner"></a></div>
            </div>
        </div>
    </div>

    <div class="banner" id="banner">
        <div class="bannerBox">
            <div class="phoneTitle">客服热线：</div>
            <div class="phone">400-000-0000</div>
            <div class="Btn">
                <button class="login" onclick="window.location.href='index.html'">返回首页</button>
            </div>
        </div>
    </div>

    <div class="register-container">
        <h2 class="register-title">用户注册</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <form action="register_process.php" method="post">
            <div class="form-group">
                <label for="username">用户名:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">密码:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">确认密码:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-submit">注册</button>
            </div>
        </form>
        
        <div class="login-link">
            已有账号? <a href="index.html">返回登录</a>
        </div>
    </div>

    <div class="footer">
        <div class="footerBox">
            <div class="footerInfoBox">
                <div class="footerTextBox">
                    <div class="footerBoxTitle">关于我们</div>
                    <div class="footerBoxText">网站地图</div>
                    <div class="footerBoxText">网站声明</div>
                    <div class="footerBoxText">官方渠道</div>
                    <div class="footerBoxText">联系我们</div>
                </div>
            </div>
            <div class="footerInfoBox">
                <div class="footerBoxTitle">帮助中心</div>
                <div class="footerBoxText">新手指南</div>
                <div class="footerBoxText">常见问题</div>
                <div class="footerBoxText">网银工具</div>
                <div class="footerBoxText">收费标准</div>
            </div>
            <div class="footerInfoBox" style="border: none;">
                <div class="footerBoxTitle">关注我们</div>
                <div class="icon_weixin"></div>
                <div class="icon_weibo"></div>
            </div>
            <div class="footerOtherInfoBox">
                <div class="footerOtherInfoContent">
                    <div style="color: #444444; font-size: 14px;">客服热线（工作日 08:00-20:00）</div>
                    <div style="font-weight: bold; font-size: 30px; color: #444444; margin-top: 5px;">400-xx-xxxxx</div>
                    <div style="color: #444444; font-size: 16px;font-style: italic;">service@xxxxx.com</div>
                    <div style="color: #444444; font-size: 14px; margin-top: 10px;">Copyright 2015 xxxx.com All Rights Reserved</div>
                    <div style="color: #444444; font-size: 14px; margin-top: 10px;"><a href="#" style="text-decoration: none; color: #444444;">粤ICP备xxxxxxx号-x</a></div>
                </div>
            </div>
        </div>
    </div>

    <script src="default.js"></script>
</body>
</html> 