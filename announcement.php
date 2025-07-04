<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>理财产品公告</title>
    <link rel="stylesheet" href="default.css">
    <style>
        .announcement-container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        
        .year-nav {
            display: flex;
            flex-wrap: wrap;
            border: 1px solid #e0e0e0;
            margin-bottom: 20px;
        }
        
        .year-nav a {
            padding: 8px 15px;
            color: #333;
            text-decoration: none;
            border-right: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            background-color: #f9f9f9;
            font-size: 14px;
        }
        
        .year-nav a:hover, .year-nav a.active {
            background-color: #e0e0e0;
        }
        
        .photo-upload {
            text-align: right;
            color: #666;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .announcement-list {
            width: 100%;
            border-collapse: collapse;
        }
        
        .announcement-list tr {
            border-bottom: 1px solid #eee;
        }
        
        .announcement-list td {
            padding: 12px 8px;
            font-size: 14px;
        }
        
        .announcement-list td a {
            color: #333;
            text-decoration: none;
        }
        
        .announcement-list td a:hover {
            color: #c22;
            text-decoration: underline;
        }
        
        .announcement-date {
            color: #999;
            text-align: right;
            width: 100px;
        }
        
        .pagination {
            text-align: right;
            margin-top: 20px;
            padding-right: 10px;
        }
        
        .pagination a {
            display: inline-block;
            padding: 5px 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            color: #333;
            text-decoration: none;
            margin: 0 2px;
        }
        
        .pagination a:hover, .pagination a.active {
            background-color: #c22;
            color: #fff;
            border-color: #c22;
        }
        
        .note-container {
            padding: 10px;
            border: 1px solid #ddd;
            margin: 15px 0;
        }
        
        .note-red {
            color: #c22;
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

<?php
// 包含登录检查
require_once 'check_login.php';
?>
    <div class="banner" id="banner">
        <div class="bannerBox">
            <div class="phoneTitle">客服热线：</div>
            <div class="phone">400-000-0000</div>
            <?php echo getUserControlsHTML(); ?>
        </div>
    </div>

    <div class="navigator">
        <div class="navigatorBox">
            <ul class="navUl">
                <li class="navLi"><a href="index.php" class="bar" style="padding-left: 40px;">理财首页</a></li>
                <li class="navLi"><a href="financing.php" class="bar">银行理财</a></li>
                <li class="navLi"><a href="announcement.php" class="bar" style="color: #C22; font-weight: bold;">理财公告</a></li>
            </ul>
        </div>
    </div>

    <div class="announcement-container">
        <h2>成立公告</h2>
        
        <!-- 年份导航 -->
        <div class="year-nav" id="yearNav">
            <!-- 年份将通过JavaScript动态加载 -->
        </div>
        
        
        <!-- 公告列表 -->
        <table class="announcement-list" id="announcementList">
            <tbody>
                <!-- 公告数据将通过JavaScript动态加载 -->
            </tbody>
        </table>
        
        <!-- 分页 -->
        <div class="pagination">
            <a href="#" class="active">1</a>
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
            <form id="loginForm" action="login_process.php" method="post">
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
                <div class="form-group" style="text-align: center;">
                    <a href="register.php" style="color: #c22; text-decoration: none;">没有账号？点击注册</a>
                </div>
            </form>
        </div>
    </div>

    <script src="default.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 从URL获取年份参数，如果没有则暂时设为null（稍后会从API获取可用年份）
            let currentYear = new URLSearchParams(window.location.search).get('year');
            
            // 加载年份导航
            loadYearNavigation(currentYear);
            
            // 登录按钮事件处理
            if (document.getElementById('loginBtn')) {
                document.getElementById('loginBtn').addEventListener('click', function() {
                    document.getElementById('loginModal').style.display = 'block';
                });
            }
            
            // 注册按钮点击事件
            if (document.getElementById('registerBtn')) {
                document.getElementById('registerBtn').addEventListener('click', function() {
                    window.location.href = 'register.php';
                });
            }
            
            // 关闭模态框按钮事件处理
            if (document.getElementsByClassName('close')[0]) {
                document.getElementsByClassName('close')[0].addEventListener('click', function() {
                    document.getElementById('loginModal').style.display = 'none';
                });
            }
            
            // 点击窗口外关闭模态框
            window.addEventListener('click', function(event) {
                if (event.target == document.getElementById('loginModal')) {
                    document.getElementById('loginModal').style.display = 'none';
                }
            });
        });
        
        // 加载年份导航
        function loadYearNavigation(requestedYear) {
            console.log("加载年份导航，请求年份:", requestedYear);
            
            fetch('get_announcement_years.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP 错误：${response.status}`);
                    }
                    return response.text();
                })
                .then(text => {
                    console.log("年份API原始响应:", text);
                    try {
                        return JSON.parse(text);
                    } catch(e) {
                        console.error("年份JSON解析错误:", e);
                        throw new Error("返回的年份数据不是有效的JSON格式");
                    }
                })
                .then(years => {
                    console.log("解析后的年份数据:", years);
                    const yearNav = document.getElementById('yearNav');
                    yearNav.innerHTML = '';
                    
                    if (!years || years.length === 0) {
                        yearNav.innerHTML = '<span style="color: gray;">暂无年份数据</span>';
                        // 使用默认年份
                        years = [2024, 2023, 2022]; // 使用固定年份作为备用
                    }
                    
                    // 如果没有指定年份或指定的年份不在可用年份列表中，使用最新年份
                    let currentYear = requestedYear;
                    if (!currentYear || !years.includes(parseInt(currentYear))) {
                        currentYear = years[0]; // 使用第一个（最新）年份
                    }
                    
                    years.forEach(year => {
                        const yearLink = document.createElement('a');
                        yearLink.href = `announcement.php?year=${year}`;
                        yearLink.textContent = year;
                        
                        // 设置当前年份高亮显示
                        if (year == currentYear) {
                            yearLink.classList.add('active');
                        }
                        
                        yearNav.appendChild(yearLink);
                    });
                    
                    // 无论如何都要加载公告数据
                    loadAnnouncements(currentYear);
                })
                .catch(error => {
                    console.error('获取年份列表失败:', error);
                    const yearNav = document.getElementById('yearNav');
                    yearNav.innerHTML = '<span style="color: red;">加载年份数据失败: ' + error.message + '</span>';
                    
                    // 尽管年份数据加载失败，仍然尝试加载2024年的公告数据
                    loadAnnouncements(2024);
                });
        }
        
        // 加载公告
        function loadAnnouncements(year) {
            console.log("加载公告数据，年份:", year);
            
            fetch(`get_announcements.php?year=${year}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP 错误：${response.status}`);
                    }
                    return response.text();
                })
                .then(text => {
                    console.log("公告API原始响应:", text);
                    try {
                        return JSON.parse(text);
                    } catch(e) {
                        console.error("公告JSON解析错误:", e);
                        throw new Error("返回的公告数据不是有效的JSON格式");
                    }
                })
                .then(data => {
                    console.log("解析后的公告数据:", data);
                    const announcementList = document.getElementById('announcementList');
                    announcementList.innerHTML = '';
                    
                    if (!data || !data.data || data.data.length === 0) {
                        announcementList.innerHTML = '<tr><td colspan="2" style="text-align: center; padding: 20px;">该年份暂无公告</td></tr>';
                        return;
                    }
                    
                    data.data.forEach(announcement => {
                        const row = document.createElement('tr');
                        
                        // 创建标题列
                        const titleCell = document.createElement('td');
                        const titleLink = document.createElement('a');
                        titleLink.href = `announcement_detail.php?id=${announcement.id}`;
                        titleLink.textContent = announcement.title;
                        titleCell.appendChild(titleLink);
                        
                        // 创建日期列
                        const dateCell = document.createElement('td');
                        dateCell.className = 'announcement-date';
                        dateCell.textContent = announcement.publish_date;
                        
                        // 添加到行
                        row.appendChild(titleCell);
                        row.appendChild(dateCell);
                        
                        // 添加到表格
                        announcementList.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('获取公告列表失败:', error);
                    const announcementList = document.getElementById('announcementList');
                    announcementList.innerHTML = `
                        <tr>
                            <td colspan="2" style="text-align: center; padding: 20px; color: red;">
                                加载公告列表失败: ${error.message}
                                <p>可能是数据库未初始化，<a href="db_initialize.php">点击这里</a>初始化数据库</p>
                            </td>
                        </tr>
                    `;
                });
        }
    </script>
</body>
</html> 