<?php
// 仅显示文本，不进行数据库连接
echo "<h1>MySQL 8.0认证修复指南</h1>";
echo "<p>您的MySQL服务器使用了PHP不支持的认证方式(caching_sha2_password)</p>";
echo "<p>请按照以下步骤修复：</p>";
echo "<ol>";
echo "<li>打开MySQL命令行客户端（使用管理员权限）</li>";
echo "<li>输入以下命令登录MySQL（使用您的密码）：<br><code>mysql -u root -p</code></li>";
echo "<li>输入以下命令更改root用户认证方式：<br><code>ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '123456';</code></li>";
echo "<li>如果上面的命令不起作用，尝试使用IP地址：<br><code>ALTER USER 'root'@'127.0.0.1' IDENTIFIED WITH mysql_native_password BY '123456';</code></li>";
echo "<li>刷新权限：<br><code>FLUSH PRIVILEGES;</code></li>";
echo "<li>退出MySQL：<br><code>EXIT;</code></li>";
echo "</ol>";
echo "<p>完成后，请刷新您的应用页面。</p>";

// 另一种选择：创建新用户
echo "<h2>或者创建新用户（备选方案）</h2>";
echo "<ol>";
echo "<li>打开MySQL命令行客户端（使用管理员权限）</li>";
echo "<li>输入以下命令登录MySQL（使用您的密码）：<br><code>mysql -u root -p</code></li>";
echo "<li>创建新用户：<br><code>CREATE USER 'finance'@'localhost' IDENTIFIED WITH mysql_native_password BY '123456';</code></li>";
echo "<li>授予权限：<br><code>GRANT ALL PRIVILEGES ON financial_products.* TO 'finance'@'localhost';</code></li>";
echo "<li>刷新权限：<br><code>FLUSH PRIVILEGES;</code></li>";
echo "<li>退出MySQL：<br><code>EXIT;</code></li>";
echo "</ol>";
echo "<p>然后修改config.php文件，将用户名从root改为finance</p>";
?> 