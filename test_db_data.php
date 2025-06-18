<?php
// 显示所有错误，便于调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>数据库内容测试</h1>";

// 包含数据库配置文件
require_once 'config.php';

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
echo "<p>数据库连接成功。连接信息：</p>";
echo "<pre>";
echo "主机: " . $db_host . "\n";
echo "用户: " . $db_user . "\n";
echo "数据库: " . $db_name . "\n";
echo "</pre>";

// 检查products表
echo "<h2>产品表数据</h2>";
$result = $conn->query("SELECT * FROM products");
if (!$result) {
    echo "<p>查询失败: " . $conn->error . "</p>";
} else {
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>产品代码</th><th>产品名称</th><th>类型</th><th>年化利率</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["product_code"] . "</td>";
            echo "<td>" . $row["product_name"] . "</td>";
            echo "<td>" . $row["product_type"] . "</td>";
            echo "<td>" . $row["annual_rate"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>产品表中没有数据</p>";
    }
}

// 检查announcements表
echo "<h2>公告表数据</h2>";
$result = $conn->query("SELECT * FROM announcements");
if (!$result) {
    echo "<p>查询失败: " . $conn->error . "</p>";
} else {
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>标题</th><th>发布日期</th><th>年份</th><th>参考代码</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["title"] . "</td>";
            echo "<td>" . $row["publish_date"] . "</td>";
            echo "<td>" . $row["year"] . "</td>";
            echo "<td>" . $row["reference_code"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>公告表中没有数据</p>";
    }
}

// 检查product_types表
echo "<h2>产品类型表数据</h2>";
$result = $conn->query("SELECT * FROM product_types");
if (!$result) {
    echo "<p>查询失败: " . $conn->error . "</p>";
} else {
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>类型名称</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["type_name"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>产品类型表中没有数据</p>";
    }
}

// 检查API返回值
echo "<h2>API返回测试</h2>";

echo "<h3>测试 get_financing_products.php</h3>";
echo '<pre id="financing_result">加载中...</pre>';

echo "<h3>测试 get_announcements.php</h3>";
echo '<pre id="announcements_result">加载中...</pre>';

echo "<h3>测试 get_product_types.php</h3>";
echo '<pre id="product_types_result">加载中...</pre>';

echo "<script>
// 测试获取理财产品API
fetch('get_financing_products.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById('financing_result').textContent = data;
    })
    .catch(error => {
        document.getElementById('financing_result').textContent = '错误: ' + error;
    });

// 测试获取公告API
fetch('get_announcements.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById('announcements_result').textContent = data;
    })
    .catch(error => {
        document.getElementById('announcements_result').textContent = '错误: ' + error;
    });

// 测试获取产品类型API
fetch('get_product_types.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById('product_types_result').textContent = data;
    })
    .catch(error => {
        document.getElementById('product_types_result').textContent = '错误: ' + error;
    });
</script>";

$conn->close();
?> 