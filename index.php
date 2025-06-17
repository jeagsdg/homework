<?php
// 包含登录检查
require_once 'check_login.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>金融理财产品</title>
    <link rel="stylesheet" href="default.css">
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
            <?php echo getUserControlsHTML(); ?>
        </div>
    </div>

    <div class="navigator">
        <div class="navigatorBox">
            <ul class="navUl">
                <li class="navLi"><a href="index.php" class="bar" style="padding-left: 40px; color: #C22; font-weight: bold;">理财首页</a></li>
                <li class="navLi"><a href="financing.php" class="bar">银行理财</a></li>
                <li class="navLi"><a href="announcement.php" class="bar">理财公告</a></li>
            </ul>
        </div>
    </div>

    <div class="fundInfo">
        <div class="fundNameBox">
            <div class="fundName" id="productName">安富 200110期</div>
            <div class="icon_safe"></div>
        </div>
        <div class="fundDetailBox">
            <div class="fundDetailBoxLeft">
                <div class="fundDetail">
                    <div class="infoBox">
                        <div class="infoBoxTitle">预期年化利率</div>
                        <div class="infoBoxContent" id="annualRate">3.07</div>%
                    </div>
                    <div style="width: 1px; background-color: #CCCCCC;"></div>
                    <div class="infoBox">
                        <div class="infoBoxTitle">投资期限（天）</div>
                        <div class="infoBoxContent" id="investmentPeriod">90</div>天
                    </div>
                    <div style="width: 1px; background-color: #CCCCCC;"></div>
                    <div class="infoBox">
                        <div class="infoBoxTitle">起投金额（元）</div>
                        <div class="infoBoxContent" id="minInvestment">10000.00</div>
                    </div>
                </div>
                <div class="textBox">
                    <div class="textLineBox">
                        <div class="textLine">项目起息日</div>
                        <div class="textLine" style="color: black;" id="startDate">2020-03-25</div>
                        <div class="textLine">项目到期日</div>
                        <div class="textLine" style="color: black;" id="endDate">2020-06-25</div>
                    </div>
                    <div class="textLineBox">
                        <div class="textLine">递增金额</div>
                        <div class="textLine" style="color: black;" id="incrementAmount">1000元</div>
                        <div class="textLine">投资截止时间</div>
                        <div class="textLine" style="color: black;" id="timer">7天7时12分31秒</div>
                    </div>
                    <div class="progressBarBox">
                        <div class="textLine">投资进度</div>
                        <progress value="70" max="100" class="progressBar1" id="progressBar1"></progress>
                        <div class="percentage" id="percentage">70%</div>
                    </div>
                </div>
            </div>
            <div class="fundDetailBoxRight">
                <div class="fundDetailRight">
                    <span style="display: block;">剩余可投金额</span>
                    <span class="infoBoxContent" id="remaininginvestableAmount">300000</span><div class="infoBoxContent" style="margin-left: 10px;">元</div>
                    <span style="display: block; padding-top: 10px; color: #535257; font-size: 15px;" id="balanceContainer">
                        账户余额: 
                        <div id="accountbalance" style="display: inline-block;">
                            <?php echo isUserLoggedIn() ? '50000.00' : '请登录查看'; ?>
                        </div>元
                    </span>
                    <span style="display: block; padding-top: 10px; color: #535257; font-size: 15px;">预计收益: <div id="projectedrevenue" style="display: inline-block; color: #B72B2B;">0.0</div>元</span>
                    <input type="text" class="touziInput" id="investmentAmount" placeholder="请输入投资金额">
                    <button class="touziBtn" id="investBtn">立 即 投 资</button>
                </div>
            </div>
        </div>
    </div>

    <div class="productInfo">
        <div class="productInfoUlBox">
            <ul class="productInfoUl">
                <li class="productInfoLl" onclick="showContent(0)">产品说明</li>
                <li class="productInfoLl" onclick="showContent(1)">风险揭示</li>
                <li class="productInfoLl" onclick="showContent(2)">权益须知</li>
            </ul>
        </div>
        <div class="productInfoContent">
            <div class="productInfoContentText">
                <div class="productInfoContentText_info" style="font-size: 13px; margin-left: 30px; color: #59555B;">
                    <p style="font-weight: bold; font-size: 16px; text-align: center;">XX银行人民币理财产品客户权益须知</p>
                    <div style="margin-left: 20px;">
                        <p>尊敬的客户：</p>
                        <p>理财非存款、产品有风险、投资须谨慎。为了保护您的合法权益，请在投资前认真阅读以下内容：</p>
                        <p>一、客户购买产品流程</p>
                        <p>如客户前往网点购买理财产品：</p>
                        <p>（一）资料清单</p>
                        <p>1、购买本理财产品的个人客户需要提供的资料清单:</p>
                        <p>（1）本人有效身份证件。</p>
                        <p>（2）本人的广发行理财通卡或存折。</p>
                        <p>...</p>
                    </div>
                </div>
            </div>
            <div class="productInfoContentText">
                <div class="productInfoContentText_info" style="font-size: 13px; margin-left: 30px; color: #59555B;">
                    <p style="font-weight: bold; font-size: 16px; text-align: center;">XX银行人民币理财产品风险揭示</p>
                </div>
            </div>
            <div class="productInfoContentText">
                <div class="productInfoContentText_info" style="font-size: 13px; margin-left: 30px; color: #59555B;">
                    <p style="font-weight: bold; font-size: 16px; text-align: center;">XX银行人民币理财产品客户权益须知</p>
                </div>
            </div>
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
        // 当用户已登录时，不显示登录模态框
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isUserLoggedIn()): ?>
            // 用户已登录
            document.getElementById('investBtn').addEventListener('click', function() {
                const amount = document.getElementById('investmentAmount').value;
                if (!amount) {
                    alert('请输入投资金额');
                    return;
                }
                // 此处可添加投资逻辑
                alert('投资请求已提交，金额: ' + amount + '元');
            });
            <?php else: ?>
            // 用户未登录，点击投资按钮显示登录框
            document.getElementById('investBtn').addEventListener('click', function() {
                document.getElementById('loginModal').style.display = 'block';
            });
            
            // 登录按钮点击事件
            document.getElementById('loginBtn').addEventListener('click', function() {
                document.getElementById('loginModal').style.display = 'block';
            });
            
            // 注册按钮点击事件
            document.getElementById('registerBtn').addEventListener('click', function() {
                window.location.href = 'register.php';
            });
            
            // 关闭模态框按钮事件处理
            document.getElementsByClassName('close')[0].addEventListener('click', function() {
                document.getElementById('loginModal').style.display = 'none';
            });
            
            // 点击窗口外关闭模态框
            window.addEventListener('click', function(event) {
                if (event.target == document.getElementById('loginModal')) {
                    document.getElementById('loginModal').style.display = 'none';
                }
            });
            <?php endif; ?>
            
            // 显示错误信息（如果有）
            <?php if (isset($_GET['error'])): ?>
            alert('<?php echo htmlspecialchars($_GET['error']); ?>');
            <?php endif; ?>
        });
        
        // 显示产品信息内容
        function showContent(index) {
            const contentElements = document.querySelectorAll('.productInfoContentText');
            for (let i = 0; i < contentElements.length; i++) {
                contentElements[i].style.display = i === index ? 'block' : 'none';
            }
            
            const tabElements = document.querySelectorAll('.productInfoLl');
            for (let i = 0; i < tabElements.length; i++) {
                tabElements[i].style.color = i === index ? '#C22' : '';
            }
        }
        
        // 默认显示第一个标签内容
        showContent(0);
    </script>
</body>
</html> 