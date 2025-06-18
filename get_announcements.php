<?php
// 包含数据库配置文件
require_once 'config.php';

// 禁用所有输出缓存
@ob_end_clean();
@ob_end_flush();

// 设置内容类型为JSON
header('Content-Type: application/json; charset=utf-8');

try {
    // 处理查询参数
    $year = isset($_GET['year']) && !empty($_GET['year']) ? $_GET['year'] : null;
    
    // 构建SQL查询
    $sql = "SELECT 
                id,
                title,
                content,
                publish_date,
                year,
                reference_code
            FROM announcements
            WHERE is_active = 1";
    
    // 添加年份筛选条件
    if ($year !== null) {
        $sql .= " AND year = ?";
    }
    
    // 排序（最新日期靠前）
    $sql .= " ORDER BY publish_date DESC";
    
    // 准备语句
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("准备SQL语句失败: " . $conn->error);
    }
    
    // 如果有年份参数，绑定参数
    if ($year !== null) {
        $stmt->bind_param("i", $year);
    }
    
    // 执行查询
    $stmt->execute();
    $result = $stmt->get_result();
    
    // 获取结果
    $announcements = array();
    while ($row = $result->fetch_assoc()) {
        // 日期格式化
        $row['publish_date'] = date('Y-m-d', strtotime($row['publish_date']));
        $announcements[] = $row;
    }
    
    // 返回JSON结果
    echo json_encode(array('data' => $announcements), JSON_UNESCAPED_UNICODE);
    $stmt->close();
    
} catch (Exception $e) {
    // 返回错误信息
    echo json_encode(array('error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
}

$conn->close();
exit; // 确保没有其他输出
?> 