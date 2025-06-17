<?php
// 包含数据库配置文件
require_once 'config.php';

// 获取产品数据
function getProductData($productCode = '200110') {
    global $conn;
    
    $sql = "SELECT * FROM products WHERE product_code = ? AND is_active = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $productCode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// 计算剩余时间
function getRemainingTime($deadline) {
    $now = new DateTime();
    $end = new DateTime($deadline);
    $interval = $now->diff($end);
    
    if ($interval->invert == 1) {
        // 已过期
        return "已截止";
    }
    
    return $interval->format('%a天%h时%i分%s秒');
}

// 计算预期收益
function calculateExpectedRevenue($amount, $annualRate, $period) {
    // 日利率 = 年化利率 / 365
    $dailyRate = $annualRate / 100 / 365;
    // 预期收益 = 投资金额 * 日利率 * 投资天数
    $revenue = $amount * $dailyRate * $period;
    
    return number_format($revenue, 2, '.', '');
}

// 计算投资进度百分比
function calculateProgress($totalAmount, $remainingAmount) {
    $investedAmount = $totalAmount - $remainingAmount;
    $progress = ($investedAmount / $totalAmount) * 100;
    
    return number_format($progress, 0);
}

// 如果是AJAX请求，返回产品数据
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $productCode = isset($_GET['product_code']) ? $_GET['product_code'] : '200110';
    $product = getProductData($productCode);
    
    if ($product) {
        $remainingTime = getRemainingTime($product['deadline']);
        $progress = calculateProgress($product['total_amount'], $product['remaining_amount']);
        
        $response = [
            'product_name' => $product['product_name'],
            'product_code' => $product['product_code'],
            'annual_rate' => $product['annual_rate'],
            'investment_period' => $product['investment_period'],
            'min_investment' => $product['min_investment'],
            'start_date' => $product['start_date'],
            'end_date' => $product['end_date'],
            'increment_amount' => $product['increment_amount'],
            'remaining_time' => $remainingTime,
            'progress' => $progress,
            'remaining_amount' => $product['remaining_amount'],
            'total_amount' => $product['total_amount']
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => '产品不存在']);
        exit;
    }
}
?> 