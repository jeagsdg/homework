<?php
// 包含数据库配置文件
require_once 'config.php';

// 禁用所有输出缓存
@ob_end_clean();
@ob_end_flush();

// 设置内容类型为JSON
header('Content-Type: application/json; charset=utf-8');

try {
    // 查询所有产品类型
    $sql = "SELECT id, type_name FROM product_types ORDER BY id";
    $result = $conn->query($sql);
    
    if ($result === false) {
        throw new Exception("查询产品类型失败: " . $conn->error);
    }

    // 如果没有数据，从产品表中获取唯一的产品类型
    if ($result->num_rows == 0) {
        $sql = "SELECT DISTINCT product_type as type_name FROM products ORDER BY product_type";
        $result = $conn->query($sql);
        
        if ($result === false) {
            throw new Exception("备用查询产品类型失败: " . $conn->error);
        }
        
        $types = array();
        $id = 1;
        while ($row = $result->fetch_assoc()) {
            $types[] = array(
                'id' => $id++,
                'type_name' => $row['type_name']
            );
        }
    } else {
        // 从product_types表获取数据
        $types = array();
        while ($row = $result->fetch_assoc()) {
            $types[] = $row;
        }
    }

    // 返回JSON结果
    echo json_encode($types, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // 返回错误信息
    echo json_encode(array('error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
}

$conn->close();
exit; // 确保没有其他输出
?> 