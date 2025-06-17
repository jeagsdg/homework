<?php
// 包含数据库配置文件
require_once 'config.php';

header('Content-Type: application/json');

// 查询所有可用的公告年份
$sql = "SELECT DISTINCT year FROM announcements WHERE is_active = 1 ORDER BY year DESC";
$result = $conn->query($sql);

$years = array();
while ($row = $result->fetch_assoc()) {
    $years[] = $row['year'];
}

// 返回JSON结果
echo json_encode($years, JSON_UNESCAPED_UNICODE);
$conn->close();
?> 