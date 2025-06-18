<?php
// 显示所有错误，便于调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 包含数据库配置文件
require_once 'config.php';

// 获取产品数据
function getProductData($productCode = '200110') {
    global $conn;
    
    try {
        // 检查数据库连接
        if ($conn->connect_error) {
            throw new Exception("数据库连接失败: " . $conn->connect_error);
        }
        
        // 检查products表是否存在
        $check_table = $conn->query("SHOW TABLES LIKE 'products'");
        if ($check_table->num_rows == 0) {
            throw new Exception("products表不存在，请先初始化数据库");
        }
        
        $sql = "SELECT * FROM products WHERE product_code = ? AND is_active = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $productCode);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            
            // 更新起息日期和到期日期为当前日期
            $data['start_date'] = date('Y-m-d'); // 设置起息日为今天
            $data['end_date'] = date('Y-m-d', strtotime('+' . $data['investment_period'] . ' days')); // 到期日为投资期限后
            $data['deadline'] = date('Y-m-d H:i:s', strtotime('+7 day')); // 截止日期设为7天后
            
            return $data;
        } else {
            return null;
        }
    } catch (Exception $e) {
        error_log("获取产品数据错误: " . $e->getMessage());
        return ['error' => $e->getMessage()];
    }
}

// 计算剩余时间
function getRemainingTime($deadline) {
    $now = new DateTime();
    
    // 如果deadline过期了，使用明天作为新的截止时间
    $end = new DateTime($deadline);
    if ($now > $end) {
        $end = new DateTime();
        $end->add(new DateInterval('P1D')); // 添加1天
    }
    
    $interval = $now->diff($end);
    
    if ($interval->invert == 1) {
        // 已过期，但这种情况不应发生，因为我们已经确保deadline总是在未来
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
    try {
        $productCode = isset($_GET['product_code']) ? $_GET['product_code'] : '200110';
        
        // 检查products表是否存在，不存在则创建
        $check_table = $conn->query("SHOW TABLES LIKE 'products'");
        if ($check_table->num_rows == 0) {
            // 创建products表
            $create_table = "CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_code VARCHAR(20) NOT NULL COMMENT '产品编号',
                product_name VARCHAR(100) NOT NULL COMMENT '产品名称',
                annual_rate DECIMAL(5,2) NOT NULL COMMENT '预期年化利率(%)',
                investment_period INT NOT NULL COMMENT '投资期限(天)',
                min_investment DECIMAL(12,2) NOT NULL COMMENT '起投金额(元)',
                increment_amount DECIMAL(12,2) NOT NULL COMMENT '递增金额(元)',
                start_date DATE NOT NULL COMMENT '项目起息日',
                end_date DATE NOT NULL COMMENT '项目到期日',
                total_amount DECIMAL(12,2) NOT NULL COMMENT '总可投金额(元)',
                remaining_amount DECIMAL(12,2) NOT NULL COMMENT '剩余可投金额(元)',
                deadline DATETIME NOT NULL COMMENT '投资截止时间',
                is_active TINYINT(1) DEFAULT 1 COMMENT '是否激活'
            )";
            
            if (!$conn->query($create_table)) {
                throw new Exception("无法创建products表: " . $conn->error);
            }
            
            // 插入示例产品数据
            $current_date = date('Y-m-d');
            $future_date = date('Y-m-d', strtotime('+90 days'));
            $deadline = date('Y-m-d H:i:s', strtotime('+1 day'));
            
            $insert_data = "INSERT INTO products (product_code, product_name, annual_rate, investment_period, min_investment, increment_amount, start_date, end_date, total_amount, remaining_amount, deadline, is_active) VALUES
                ('200110', '安富 200110期', 3.07, 90, 10000.00, 1000.00, '$current_date', '$future_date', 1000000.00, 300000.00, '$deadline', 1)";
                
            if (!$conn->query($insert_data)) {
                throw new Exception("无法插入示例产品数据: " . $conn->error);
            }
        }
        
        $product = getProductData($productCode);
        
        if (isset($product['error'])) {
            throw new Exception($product['error']);
        }
        
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
        } else {
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['error' => '产品不存在']);
        }
    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
?> 