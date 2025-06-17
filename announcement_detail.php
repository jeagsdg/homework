<?php
// 包含数据库配置文件
require_once 'config.php';

// 获取公告ID
$announcement_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 获取公告详细信息
$sql = "SELECT * FROM announcements WHERE id = ? AND is_active = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $announcement_id);
$stmt->execute();
$result = $stmt->get_result();

// 如果没有找到公告，重定向到公告列表页
if ($result->num_rows === 0) {
    header('Location: announcement.php');
    exit;
}

// 获取公告数据
$announcement = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($announcement['title']); ?> - 理财产品公告</title>
    <link rel="stylesheet" href="default.css">
    <style>
        .announcement-container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .announcement-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .announcement-meta {
            color: #666;
            margin-bottom: 30px;
            text-align: center;
            font-size: 14px;
        }
        
        .announcement-content {
            line-height: 1.8;
            color: #333;
            font-size: 16px;
        }
        
        .back-link {
            display: block;
            margin: 30px auto;
            text-align: center;
        }
        
        .back-link a {
            color: #c22;
            text-decoration: none;
        }
        
        .back-link a:hover {
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
            <?php 
            require_once 'check_login.php';
            echo getUserControlsHTML(); 
            ?>
        </div>
    </div>

    <div class="navigator">
        <div class="navigatorBox">
            <ul class="navUl">
                <li class="navLi"><a href="index.html" class="bar" style="padding-left: 40px;">理财首页</a></li>
                <li class="navLi"><a href="financing.php" class="bar">银行理财</a></li>
                <li class="navLi"><a href="announcement.php" class="bar" style="color: #C22; font-weight: bold;">理财公告</a></li>
            </ul>
        </div>
    </div>

    <div class="announcement-container">
        <h1 class="announcement-title"><?php echo htmlspecialchars($announcement['title']); ?></h1>
        <div class="announcement-meta">
            发布日期: <?php echo date('Y-m-d', strtotime($announcement['publish_date'])); ?>
            <?php if (!empty($announcement['reference_code'])): ?>
                | 参考代码: <?php echo htmlspecialchars($announcement['reference_code']); ?>
            <?php endif; ?>
        </div>
        <div class="announcement-content">
            <?php echo nl2br(htmlspecialchars($announcement['content'])); ?>
        </div>
        <div class="back-link">
            <a href="announcement.php">返回公告列表</a>
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

    <!-- 登录模态框 -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>用户登录</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label for="username">用户名:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">密码:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn-submit">登录</button>
                </div>
            </form>
        </div>
    </div>

    <script src="default.js"></script>
</body>
</html> 