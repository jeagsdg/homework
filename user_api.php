<?php
// 显示所有错误，便于调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 包含数据库配置文件
require_once 'config.php';

// 启动会话
session_start();

// 用户登录
function login($username, $password) {
    global $conn;
    
    try {
        // 检查数据库连接
        if (!$conn || $conn->connect_error) {
            throw new Exception("数据库连接异常，请检查 config.php");
        }
    
        $sql = "SELECT id, username, password, balance FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("SQL预处理失败: " . $conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // 验证密码
            if (password_verify($password, $user['password'])) {
                // 登录成功，设置会话
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;
                
                return [
                    'success' => true,
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'balance' => $user['balance']
                ];
            }
        }
        
        return ['success' => false, 'message' => '用户名或密码错误'];
    } catch (Exception $e) {
        error_log("登录错误: " . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// 用户注销
function logout() {
    // 清除所有会话变量
    $_SESSION = [];
    
    // 销毁会话
    session_destroy();
    
    return ['success' => true];
}

// 获取用户信息
function getUserInfo() {
    global $conn;
    
    try {
        // 检查数据库连接
        if (!$conn || $conn->connect_error) {
            throw new Exception("数据库连接异常，请检查 config.php");
        }
    
        if (!isset($_SESSION['user_id'])) {
            return ['success' => false, 'message' => '未登录'];
        }
    
        $userId = $_SESSION['user_id'];
        
        $sql = "SELECT id, username, balance FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("SQL预处理失败: " . $conn->error);
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            return [
                'success' => true,
                'user_id' => $user['id'],
                'username' => $user['username'],
                'balance' => $user['balance']
            ];
        }
        
        return ['success' => false, 'message' => '用户不存在'];
    } catch (Exception $e) {
        error_log("获取用户信息错误: " . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// 投资产品
function investProduct($productId, $amount) {
    global $conn;
    
    try {
        // 检查数据库连接
        if (!$conn || $conn->connect_error) {
            throw new Exception("数据库连接异常，请检查 config.php");
        }
    
        if (!isset($_SESSION['user_id'])) {
            return ['success' => false, 'message' => '请先登录'];
        }
    
        $userId = $_SESSION['user_id'];
        
        // 开始事务
        $conn->begin_transaction();
        
            // 获取用户余额
            $sql = "SELECT balance FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            
            // 获取产品信息
            $sql = "SELECT * FROM products WHERE id = ? AND is_active = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            
            // 检查产品是否存在
            if (!$product) {
                throw new Exception('产品不存在或已下架');
            }
            
            // 检查账户余额是否足够
            if ($user['balance'] < $amount) {
                throw new Exception('账户余额不足');
            }
            
            // 检查投资金额是否符合要求
            if ($amount < $product['min_investment']) {
                throw new Exception('投资金额不能低于最低投资额');
            }
            
            // 检查投资金额是否是递增金额的整数倍
            if ($amount % $product['increment_amount'] != 0) {
                throw new Exception('投资金额必须是递增金额的整数倍');
            }
            
            // 检查剩余可投金额是否足够
            if ($product['remaining_amount'] < $amount) {
                throw new Exception('产品剩余可投金额不足');
            }
            
            // 计算预期收益
            $expectedRevenue = calculateExpectedRevenue($amount, $product['annual_rate'], $product['investment_period']);
            
            // 更新用户余额
            $sql = "UPDATE users SET balance = balance - ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("di", $amount, $userId);
            $stmt->execute();
            
            // 更新产品剩余可投金额
            $sql = "UPDATE products SET remaining_amount = remaining_amount - ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("di", $amount, $productId);
            $stmt->execute();
            
            // 记录投资
            $sql = "INSERT INTO investments (user_id, product_id, amount, expected_revenue) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iidd", $userId, $productId, $amount, $expectedRevenue);
            $stmt->execute();
            
            // 提交事务
            $conn->commit();
            
            return [
                'success' => true,
                'message' => '投资成功',
                'amount' => $amount,
                'expected_revenue' => $expectedRevenue,
                'new_balance' => $user['balance'] - $amount,
                'new_remaining_amount' => $product['remaining_amount'] - $amount
            ];
        } catch (Exception $e) {
            // 回滚事务
            if (isset($conn) && $conn->connect_error === null) {
            $conn->rollback();
            }
            
            error_log("投资错误: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
}

// 辅助函数：计算预期收益
function calculateExpectedRevenue($amount, $annualRate, $period) {
    // 日利率 = 年化利率 / 100 / 365
    $dailyRate = $annualRate / 100 / 365;
    // 预期收益 = 投资金额 * 日利率 * 投资天数
    $revenue = $amount * $dailyRate * $period;
    
    return number_format($revenue, 2, '.', '');
}

// 处理AJAX请求
if (isset($_GET['action'])) {
    try {
    $action = $_GET['action'];
    $response = [];
    
    switch ($action) {
        case 'login':
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $response = login($username, $password);
            break;
        case 'logout':
            $response = logout();
            break;
        case 'get_user_info':
            $response = getUserInfo();
            break;
        case 'invest':
            $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
            $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
            $response = investProduct($productId, $amount);
            break;
        default:
            $response = ['success' => false, 'message' => '未知操作'];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
?> 