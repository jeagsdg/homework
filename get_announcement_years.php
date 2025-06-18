<?php
// 包含数据库配置文件
require_once 'config.php';

// 禁用所有输出缓存
@ob_end_clean();
@ob_end_flush();

// 设置内容类型为JSON
header('Content-Type: application/json; charset=utf-8');

try {
    // 查询所有可用的公告年份
    $sql = "SELECT DISTINCT year FROM announcements WHERE is_active = 1 ORDER BY year DESC";
    $result = $conn->query($sql);
    
    if ($result === false) {
        throw new Exception("查询年份失败: " . $conn->error);
    }

    $years = array();
    while ($row = $result->fetch_assoc()) {
        $years[] = $row['year'];
    }

    // 返回JSON结果
    echo json_encode($years, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // 返回错误信息
    echo json_encode(array('error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
}

$conn->close();
exit; // 确保没有其他输出
?> 