<?php
// 显示所有错误，便于调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html><html><head><title>金融系统重置与初始化</title>";
echo "<style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; max-width: 900px; margin: 0 auto; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        .card { background: #f9f9f9; border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        h1, h2, h3 { color: #333; }
        button, .btn { padding: 10px 15px; background: #0066cc; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        button:hover, .btn:hover { background: #0052a3; }
        button.danger, .btn.danger { background: #cc3300; }
        button.danger:hover, .btn.danger:hover { background: #a32900; }
        .steps { margin-left: 20px; }
        .step { margin-bottom: 10px; }
      </style>";
echo "</head><body>";

echo "<h1>金融系统数据库重置与初始化工具</h1>";
echo "<div class='card info'>";
echo "<p>这个页面提供了一站式工具来诊断和修复金融系统的数据库问题。</p>";
echo "<p><strong>如果您的理财产品或公告页面没有显示数据，请按照以下步骤操作。</strong></p>";
echo "</div>";

echo "<h2>主要操作</h2>";
echo "<div class='card'>";
echo "<div class='steps'>";
echo "<div class='step'><a href='check_database.php' class='btn'>1. 诊断数据库</a> - 检查数据库连接和表结构</div>";
echo "<div class='step'><a href='db_initialize.php' class='btn'>2. 初始化数据库</a> - 创建表并添加测试数据</div>";
echo "<div class='step'><a href='test_db_data.php' class='btn'>3. 查看数据内容</a> - 显示所有表中的数据</div>";
echo "</div>";
echo "</div>";

echo "<h2>系统检查</h2>";
echo "<div class='card'>";

// 检查PHP版本和扩展
echo "<h3>1. PHP环境检查</h3>";
echo "<p>PHP 版本: " . phpversion() . "</p>";
if (extension_loaded('mysqli')) {
    echo "<p class='success'>✓ MySQLi 扩展已加载</p>";
} else {
    echo "<p class='error'>✗ MySQLi 扩展未加载。这是连接MySQL数据库所必需的！</p>";
    echo "<p><a href='fix_mysqli.php' class='btn danger'>修复mysqli扩展问题</a></p>";
}

// 检查数据库配置
echo "<h3>2. 数据库配置</h3>";
if (file_exists('config.php')) {
    echo "<p class='success'>✓ 找到 config.php 文件</p>";
    // 读取配置但不显示密码
    include 'config.php';
    echo "<pre>";
    echo "主机: $db_host\n";
    echo "用户: $db_user\n";
    echo "数据库: $db_name\n";
    echo "</pre>";
    
    // 尝试连接数据库
    if (isset($conn) && $conn instanceof mysqli) {
        if ($conn->connect_error) {
            echo "<p class='error'>✗ 无法连接到数据库: " . htmlspecialchars($conn->connect_error) . "</p>";
            echo "<p>请确认以下信息：</p>";
            echo "<ul>";
            echo "<li>MySQL服务是否运行</li>";
            echo "<li>用户名和密码是否正确</li>";
            echo "<li>数据库名是否存在</li>";
            echo "</ul>";
        } else {
            echo "<p class='success'>✓ 成功连接到数据库</p>";
            
            // 检查是否已有数据表
            $tables = ['users', 'products', 'announcements', 'product_types', 'investments'];
            $allTablesExist = true;
            $anyHasData = false;
            
            echo "<h4>数据表状态：</h4>";
            echo "<ul>";
            foreach ($tables as $table) {
                $result = $conn->query("SHOW TABLES LIKE '$table'");
                if ($result->num_rows > 0) {
                    $countResult = $conn->query("SELECT COUNT(*) as count FROM $table");
                    $count = $countResult->fetch_assoc()['count'];
                    $anyHasData = $anyHasData || ($count > 0);
                    
                    if ($count > 0) {
                        echo "<li class='success'>表 '$table' 存在且有 $count 条数据</li>";
                    } else {
                        echo "<li class='warning'>表 '$table' 存在但没有数据</li>";
                    }
                } else {
                    echo "<li class='error'>表 '$table' 不存在</li>";
                    $allTablesExist = false;
                }
            }
            echo "</ul>";
            
            if (!$allTablesExist) {
                echo "<p class='warning'>⚠ 缺少必要的数据表，请点击 <a href='db_initialize.php'>初始化数据库</a> 创建表结构</p>";
            } else if (!$anyHasData) {
                echo "<p class='warning'>⚠ 数据表存在但没有数据，请点击 <a href='db_initialize.php'>初始化数据库</a> 添加测试数据</p>";
            } else {
                echo "<p class='success'>✓ 数据表和数据都已准备就绪</p>";
            }
        }
    } else {
        echo "<p class='warning'>⚠ 无法从配置文件获取数据库连接</p>";
    }
} else {
    echo "<p class='error'>✗ 找不到 config.php 文件，无法获取数据库信息</p>";
}

echo "</div>";

echo "<h2>前端页面检查</h2>";
echo "<div class='card'>";
echo "<ul>";
echo "<li><a href='index.php' target='_blank'>理财首页</a> - 显示单个理财产品的详细信息</li>";
echo "<li><a href='financing.php' target='_blank'>银行理财</a> - 显示所有理财产品列表</li>";
echo "<li><a href='announcement.php' target='_blank'>理财公告</a> - 显示所有公告信息</li>";
echo "</ul>";
echo "</div>";

echo "<h2>FAQ 常见问题</h2>";
echo "<div class='card'>";
echo "<h3>1. 页面上没有显示数据怎么办？</h3>";
echo "<p>首先，请点击 <a href='db_initialize.php' target='_blank'>初始化数据库</a> 来确保所有必要的表和数据都已创建。然后，刷新页面查看是否显示数据。</p>";

echo "<h3>2. 出现 JSON 解析错误怎么办？</h3>";
echo "<p>这通常说明 API 返回的不是有效的 JSON 格式。可以尝试以下步骤：</p>";
echo "<ul>";
echo "<li>检查浏览器控制台（F12）中的错误信息</li>";
echo "<li>直接访问 API 端点查看返回的原始数据：<a href='get_financing_products.php' target='_blank'>理财产品 API</a> | <a href='get_announcements.php' target='_blank'>公告 API</a></li>";
echo "<li>点击 <a href='reset_and_initialize.php' target='_blank'>重置与初始化</a> 刷新页面</li>";
echo "</ul>";

echo "<h3>3. 数据库连接失败怎么办？</h3>";
echo "<p>请检查：</p>";
echo "<ul>";
echo "<li>MySQL 服务是否正在运行</li>";
echo "<li>config.php 中的用户名和密码是否正确</li>";
echo "<li>数据库用户是否有足够的权限</li>";
echo "</ul>";
echo "</div>";

echo "<h2>系统信息</h2>";
echo "<div class='card'>";
echo "<p>操作系统: " . PHP_OS . "</p>";
echo "<p>PHP 版本: " . phpversion() . "</p>";
if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    echo "<p>MySQL 版本: " . $conn->server_info . "</p>";
    echo "<p>MySQL 连接方式: " . $conn->host_info . "</p>";
    echo "<p>字符集: " . $conn->character_set_name() . "</p>";
}
echo "</div>";

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}

echo "<div style='text-align:center; margin-top:30px;'>";
echo "<p><a href='index.php' class='btn'>返回首页</a></p>";
echo "</div>";

echo "</body></html>";
?> 