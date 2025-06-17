<?php
// 包含数据库配置文件
require_once 'config.php';

header('Content-Type: application/json');

// 处理查询参数
$productType = isset($_GET['product_type']) && !empty($_GET['product_type']) ? $_GET['product_type'] : null;
$productCode = isset($_GET['product_code']) && !empty($_GET['product_code']) ? $_GET['product_code'] : null;
$searchText = isset($_GET['search_text']) && !empty($_GET['search_text']) ? $_GET['search_text'] : null;

// 构建SQL查询
$sql = "SELECT 
            bfp.id AS 序号, 
            bfp.product_code AS 产品代码, 
            bfp.product_name AS 产品名称,
            pt.type_name AS 产品类别,
            b.bank_name AS 发行方,
            '- ' AS 存续方式,
            '- ' AS 销售区域,
            rl.level_name AS 风险等级,
            bfp.product_status AS 产品状态,
            '1.0000' AS 产品净值,
            '无' AS 最新净值日期
        FROM bank_financing_products bfp
        LEFT JOIN banks b ON bfp.bank_id = b.id
        LEFT JOIN product_types pt ON bfp.product_type_id = pt.id
        LEFT JOIN risk_levels rl ON bfp.risk_level_id = rl.id
        WHERE 1=1";

// 添加筛选条件
$params = array();
$types = "";

if ($productType !== null) {
    $sql .= " AND pt.id = ?";
    $params[] = $productType;
    $types .= "i";
}

if ($productCode !== null) {
    $sql .= " AND bfp.product_code = ?";
    $params[] = $productCode;
    $types .= "s";
}

if ($searchText !== null) {
    $sql .= " AND (bfp.product_name LIKE ? OR bfp.product_code LIKE ?)";
    $params[] = "%$searchText%";
    $params[] = "%$searchText%";
    $types .= "ss";
}

// 准备语句
$stmt = $conn->prepare($sql);

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
$conn->close();
?> 