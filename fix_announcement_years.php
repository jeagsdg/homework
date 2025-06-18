<?php
// 显示所有错误，便于调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 包含数据库配置文件
require_once 'config.php';

// 获取当前年份
$currentYear = date('Y');

try {
    // 检查数据库连接
    if ($conn->connect_error) {
        throw new Exception("数据库连接失败: " . $conn->connect_error);
    }

    echo "<h2>修复公告年份</h2>";

    // 更新announcements表中的年份为正确的历史年份
    $sql = "UPDATE announcements SET 
            year = CASE 
                WHEN publish_date < '2020-01-01' THEN YEAR(publish_date)
                ELSE $currentYear - 1
            END";
    
    if ($conn->query($sql)) {
        echo "<p>✅ 公告年份已更新</p>";
    } else {
        echo "<p>❌ 公告年份更新失败: " . $conn->error . "</p>";
    }

    // 查询更新后的年份
    $result = $conn->query("SELECT DISTINCT year FROM announcements ORDER BY year DESC");
    
    if ($result && $result->num_rows > 0) {
        echo "<p>当前可用年份: ";
        $years = [];
        while ($row = $result->fetch_assoc()) {
            $years[] = $row['year'];
        }
        echo implode(", ", $years);
        echo "</p>";
    }

    echo "<p><a href='announcement.php'>返回公告页面</a></p>";

} catch (Exception $e) {
    echo "<h2>更新失败</h2>";
    echo "<p>错误信息: " . $e->getMessage() . "</p>";
    echo "<p><a href='announcement.php'>返回公告页面</a></p>";
}

$conn->close();
?> 