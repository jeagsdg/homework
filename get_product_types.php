<?php
// 包含数据库配置文件
require_once 'config.php';

header('Content-Type: application/json');

// 查询所有产品类型
$sql = "SELECT id, type_name FROM product_types ORDER BY id";
$result = $conn->query($sql);

$types = array();
while ($row = $result->fetch_assoc()) {
    $types[] = $row;
}

// 返回JSON结果
echo json_encode($types, JSON_UNESCAPED_UNICODE);
$conn->close();
?> 