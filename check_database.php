<?php
// 显示所有错误，便于调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><title>数据库诊断</title>";
echo "<style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        pre { background: #f7f7f7; padding: 10px; border: 1px solid #ddd; overflow: auto; }
        table { border-collapse: collapse; width: 100%; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
      </style>";
echo "</head><body>";

echo "<h1>数据库连接诊断工具</h1>";

// 1. 检查数据库扩展和版本
echo "<h2>1. PHP 环境检查</h2>";
echo "<p>PHP 版本: " . phpversion() . "</p>";
if (extension_loaded('mysqli')) {
    echo "<p class='success'>✓ MySQLi 扩展已加载</p>";
    echo "<p>MySQLi 客户端版本: " . mysqli_get_client_info() . "</p>";
} else {
    echo "<p class='error'>✗ MySQLi 扩展未加载。请在 php.ini 中启用它</p>";
}

// 2. 检查数据库配置
echo "<h2>2. 数据库配置检查</h2>";
if (file_exists('config.php')) {
    echo "<p class='success'>✓ 找到 config.php 文件</p>";
    // 读取配置但不显示密码
    include 'config.php';
    echo "<pre>";
    echo "主机: $db_host\n";
    echo "用户: $db_user\n";
    echo "数据库名: $db_name\n";
    echo "</pre>";
} else {
    echo "<p class='error'>✗ 找不到 config.php 文件</p>";
    die("</body></html>");
}

// 3. 测试数据库连接
echo "<h2>3. 数据库连接测试</h2>";
try {
    if (isset($conn) && $conn instanceof mysqli) {
        if ($conn->connect_error) {
            throw new Exception("连接错误: " . $conn->connect_error);
        }
        echo "<p class='success'>✓ 成功连接到数据库</p>";
    } else {
        // 如果 $conn 未在 config.php 中定义，我们尝试手动连接
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($conn->connect_error) {
            throw new Exception("连接错误: " . $conn->connect_error);
        }
        echo "<p class='success'>✓ 成功连接到数据库</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ 数据库连接失败: " . $e->getMessage() . "</p>";
    echo "<p>请检查：</p>";
    echo "<ul>";
    echo "<li>MySQL 服务是否正在运行</li>";
    echo "<li>用户名和密码是否正确</li>";
    echo "<li>数据库名是否存在</li>";
    echo "<li>主机名是否正确</li>";
    echo "</ul>";
    die("</body></html>");
}

// 4. 获取数据库信息
echo "<h2>4. 数据库信息</h2>";
echo "<p>服务器信息: " . $conn->server_info . "</p>";
echo "<p>主机信息: " . $conn->host_info . "</p>";
echo "<p>协议版本: " . $conn->protocol_version . "</p>";

// 5. 检查表是否存在及数据
echo "<h2>5. 数据表检查</h2>";
$required_tables = [
    'users' => '用户表',
    'products' => '理财产品表',
    'announcements' => '公告表',
    'product_types' => '产品类型表',
    'investments' => '投资记录表'
];

echo "<table>";
echo "<tr><th>表名</th><th>状态</th><th>记录数</th></tr>";

foreach ($required_tables as $table => $description) {
    $check_table = $conn->query("SHOW TABLES LIKE '$table'");
    if ($check_table->num_rows > 0) {
        $result = $conn->query("SELECT COUNT(*) as count FROM $table");
        $count = $result->fetch_assoc()['count'];
        $status_class = $count > 0 ? 'success' : 'warning';
        $status = $count > 0 ? '✓ 存在且有数据' : '⚠ 存在但无数据';
        echo "<tr><td>$table ($description)</td><td class='$status_class'>$status</td><td>$count</td></tr>";
    } else {
        echo "<tr><td>$table ($description)</td><td class='error'>✗ 不存在</td><td>-</td></tr>";
    }
}
echo "</table>";

// 6. 打印一些表结构
echo "<h2>6. 表结构</h2>";
foreach ($required_tables as $table => $description) {
    $check_table = $conn->query("SHOW TABLES LIKE '$table'");
    if ($check_table->num_rows > 0) {
        echo "<h3>$table 表结构</h3>";
        $result = $conn->query("DESCRIBE $table");
        if ($result) {
            echo "<table>";
            echo "<tr><th>字段名</th><th>类型</th><th>Null</th><th>键</th><th>默认值</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['Field'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Null'] . "</td>";
                echo "<td>" . $row['Key'] . "</td>";
                echo "<td>" . ($row['Default'] === NULL ? "NULL" : $row['Default']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }
}

// 7. API返回值测试
echo "<h2>7. API返回测试</h2>";

// 添加一个测试API函数
function testAPI($url, $description) {
    global $conn;
    echo "<h3>测试 $description ($url)</h3>";
    
    // 创建一个cURL句柄
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/$url");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    
    $output = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "<p>HTTP 状态码: $httpCode</p>";
    
    if ($httpCode == 200) {
        echo "<p class='success'>✓ API 请求成功</p>";
        echo "<pre>" . htmlspecialchars(substr($output, 0, 1000)) . (strlen($output) > 1000 ? "..." : "") . "</pre>";
        
        // 尝试解析JSON
        $decoded = json_decode($output, true);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            echo "<p class='error'>✗ JSON 解析错误: " . json_last_error_msg() . "</p>";
        } else {
            echo "<p class='success'>✓ 成功解析 JSON</p>";
        }
    } else {
        echo "<p class='error'>✗ API 请求失败或超时</p>";
    }
}

// 测试各个API
testAPI("get_financing_products.php", "理财产品API");
testAPI("get_announcements.php", "公告API");
testAPI("get_product_types.php", "产品类型API");

// 8. 修复建议
echo "<h2>8. 潜在问题与修复建议</h2>";

echo "<p class='info'>如果数据表存在但没有数据，请运行 <a href='db_initialize.php'>db_initialize.php</a> 来初始化数据。</p>";
echo "<p class='info'>如果数据表不存在，请检查数据库初始化流程，可能需要重新创建表结构。</p>";
echo "<p class='info'>如果 API 请求失败，请检查对应的 PHP 文件是否有语法错误或逻辑问题。</p>";

// 建议解决方案
echo "<h2>9. 常见问题解决方案</h2>";
echo "<ol>";
echo "<li>确认 MySQL 服务正在运行</li>";
echo "<li>检查数据库用户权限</li>";
echo "<li>确保 PHP 可以连接到 MySQL (检查防火墙设置)</li>";
echo "<li>如果以上都正确，尝试重新初始化数据库：<a href='db_initialize.php'>db_initialize.php</a></li>";
echo "</ol>";

// 关闭连接
$conn->close();

echo "</body></html>";
?> 