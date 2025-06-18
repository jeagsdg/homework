<?php
// 包含数据库配置文件
require_once 'config.php';

// 禁用所有输出缓存
@ob_end_clean();
@ob_end_flush();

// 设置内容类型为JSON
header('Content-Type: application/json; charset=utf-8');

try {
    // 首先检查products表中是否存在product_type列
    $check_column = $conn->query("SHOW COLUMNS FROM products LIKE 'product_type'");
    $product_type_exists = ($check_column && $check_column->num_rows > 0);
    
    // 处理查询参数
    $productType = isset($_GET['product_type']) && !empty($_GET['product_type']) ? $_GET['product_type'] : null;
    $productCode = isset($_GET['product_code']) && !empty($_GET['product_code']) ? $_GET['product_code'] : null;
    $searchText = isset($_GET['search_text']) && !empty($_GET['search_text']) ? $_GET['search_text'] : null;

    // 获取当前时间
    $currentDate = date('Y-m-d');

    // 构建SQL查询，根据表结构调整
    $sql = "SELECT 
                id AS 序号, 
                product_code AS 产品代码, 
                product_name AS 产品名称,";
    
    // 根据product_type列是否存在来构建查询            
    if ($product_type_exists) {
        $sql .= "product_type AS 产品类别,";
    } else {
        $sql .= "'稳健型' AS 产品类别,";
    }
    
    $sql .= "'金融银行' AS 发行方,
                '非存续' AS 存续方式,
                '全国' AS 销售区域,
                CASE 
                    WHEN annual_rate < 4.0 THEN '低风险'
                    WHEN annual_rate < 6.0 THEN '中低风险'
                    WHEN annual_rate < 8.0 THEN '中高风险'
                    ELSE '高风险'
                END AS 风险等级,
                CASE WHEN is_active = 1 THEN '在售' ELSE '停售' END AS 产品状态,
                '1.0000' AS 产品净值,
                DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 365 DAY), '%Y-%m-%d') AS 最新净值日期
            FROM products
            WHERE 1=1";
    
    // 添加筛选条件
    $params = array();
    $types = "";

    if ($productType !== null && $product_type_exists) {
        $sql .= " AND product_type = ?";
        $params[] = $productType;
        $types .= "s";
    }

    if ($productCode !== null) {
        $sql .= " AND product_code = ?";
        $params[] = $productCode;
        $types .= "s";
    }

    if ($searchText !== null) {
        $sql .= " AND (product_name LIKE ? OR product_code LIKE ?)";
        $params[] = "%$searchText%";
        $params[] = "%$searchText%";
        $types .= "ss";
    }
    
    // 添加活跃状态过滤（如果列存在）
    $check_is_active = $conn->query("SHOW COLUMNS FROM products LIKE 'is_active'");
    if ($check_is_active && $check_is_active->num_rows > 0) {
        $sql .= " AND is_active = 1";
    }

    // 准备语句
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("准备SQL语句失败: " . $conn->error . " SQL: " . $sql);
    }

    // 如果有参数，绑定参数
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    // 执行查询
    $stmt->execute();
    $result = $stmt->get_result();

    // 获取结果
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    // 返回JSON结果
    echo json_encode(array('data' => $products), JSON_UNESCAPED_UNICODE);
    $stmt->close();
    
} catch (Exception $e) {
    // 返回错误信息
    echo json_encode(array('error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
}

$conn->close();
exit; // 确保没有其他输出
?> 