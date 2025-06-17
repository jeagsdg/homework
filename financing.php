<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>银行理财产品</title>
    <link rel="stylesheet" href="default.css">
    <style>
        .search-container {
            padding: 20px;
            background-color: #fff;
            margin: 20px auto;
            max-width: 1200px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .search-container select, .search-container input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .search-btn {
            background-color: #c22;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .search-btn:hover {
            background-color: #a11;
        }
        
        .products-table {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto 40px auto;
            border-collapse: collapse;
        }
        
        .products-table th, .products-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }
        
        .products-table th {
            background-color: #f2f2f2;
            color: #333;
        }
        
        .products-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .consult-btn {
            background-color: #c22;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .branch-btn {
            display: block;
            color: #0066cc;
            text-decoration: none;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .consult-btn:hover {
            background-color: #a11;
        }
        
        .branch-btn:hover {
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
                <li class="navLi"><a href="financing.php" class="bar" style="color: #C22; font-weight: bold;">银行理财</a></li>
                <li class="navLi"><a href="announcement.php" class="bar">理财公告</a></li>
            </ul>
        </div>
    </div>

    <div class="search-container">
        <select id="productType">
            <option value="">理财</option>
        </select>
        
        <select id="productCode">
            <option value="">产品代码</option>
        </select>
        
        <input type="text" id="searchInput" placeholder="请输入产品代码">
        
        <button class="search-btn" id="searchBtn">查询</button>
    </div>

    <table class="products-table">
        <thead>
            <tr>
                <th>序号</th>
                <th>产品代码</th>
                <th>产品名称</th>
                <th>产品类别</th>
                <th>发行方</th>
                <th>存续方式</th>
                <th>销售区域</th>
                <th>风险等级</th>
                <th>产品状态</th>
                <th>产品净值</th>
                <th>最新净值日期</th>
                <th>产品详情</th>
            </tr>
        </thead>
        <tbody id="productsTableBody">
            <!-- 数据将通过JavaScript动态加载 -->
        </tbody>
    </table>

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
            // 登录按钮点击事件
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
            
            // 关闭模态框
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
            // 加载产品类型下拉列表
            fetch('get_product_types.php')
                .then(response => response.json())
                .then(data => {
                    const productTypeSelect = document.getElementById('productType');
                    productTypeSelect.innerHTML = '<option value="">理财</option>';
                    
                    data.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = type.type_name;
                        productTypeSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('获取产品类型失败:', error));
            
            // 加载所有理财产品
            loadProducts();
            
            // 搜索按钮点击事件
            document.getElementById('searchBtn').addEventListener('click', function() {
                loadProducts();
            });
        });
        
        // 加载理财产品数据
        function loadProducts() {
            const productType = document.getElementById('productType').value;
            const productCode = document.getElementById('productCode').value;
            const searchText = document.getElementById('searchInput').value;
            
            let url = `get_financing_products.php?`;
            if (productType) url += `product_type=${productType}&`;
            if (productCode) url += `product_code=${productCode}&`;
            if (searchText) url += `search_text=${searchText}&`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const productsTableBody = document.getElementById('productsTableBody');
                    productsTableBody.innerHTML = '';
                    
                    if (data.data.length === 0) {
                        productsTableBody.innerHTML = '<tr><td colspan="12" style="text-align: center;">没有找到匹配的产品</td></tr>';
                        return;
                    }
                    
                    data.data.forEach(product => {
                        const row = document.createElement('tr');
                        
                        // 添加产品数据到行
                        row.innerHTML = `
                            <td>${product.序号}</td>
                            <td>${product.产品代码}</td>
                            <td>${product.产品名称}</td>
                            <td>${product.产品类别}</td>
                            <td>${product.发行方}</td>
                            <td>${product.存续方式}</td>
                            <td>${product.销售区域}</td>
                            <td>${product.风险等级}</td>
                            <td>${product.产品状态}</td>
                            <td>${product.产品净值}</td>
                            <td>${product.最新净值日期}</td>
                            <td>
                                <button class="consult-btn">咨询</button>
                                <a href="#" class="branch-btn">浏览业务网点</a>
                            </td>
                        `;
                        
                        productsTableBody.appendChild(row);
                    });
                })
                .catch(error => console.error('获取理财产品失败:', error));
        }
    </script>
</body>
</html> 