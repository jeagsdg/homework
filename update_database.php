<?php
// 显示所有错误，便于调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 包含数据库配置文件
require_once 'config.php';

// 获取当前日期和时间
$currentDate = date('Y-m-d');
$currentDateTime = date('Y-m-d H:i:s');

// 计算90天后的日期（用于产品到期日）
$endDate = date('Y-m-d', strtotime('+90 days'));

try {
    // 检查数据库连接
    if ($conn->connect_error) {
        throw new Exception("数据库连接失败: " . $conn->connect_error);
    }

    echo "<h2>数据库更新工具</h2>";
    echo "<h3>正在更新产品日期数据...</h3>";

    // 更新products表
    $sql_products = "UPDATE products SET 
                    start_date = '$currentDate',
                    end_date = '$endDate',
                    deadline = '$currentDateTime'
                    WHERE is_active = 1";
    
    if ($conn->query($sql_products)) {
        echo "<p>✅ products表更新成功</p>";
    } else {
        echo "<p>❌ products表更新失败: " . $conn->error . "</p>";
    }

    // 检查bank_financing_products表是否存在
    $check_table = $conn->query("SHOW TABLES LIKE 'bank_financing_products'");
    if ($check_table->num_rows > 0) {
        // 更新bank_financing_products表
        $sql_bank_products = "UPDATE bank_financing_products SET 
                            start_date = '$currentDate',
                            end_date = '$endDate',
                            sale_start_date = DATE_SUB('$currentDate', INTERVAL 15 DAY),
                            sale_end_date = DATE_SUB('$currentDate', INTERVAL 1 DAY),
                            product_status = CASE 
                                WHEN product_status = '已结束' THEN '募集中'
                                ELSE product_status 
                            END";
        
        if ($conn->query($sql_bank_products)) {
            echo "<p>✅ bank_financing_products表更新成功</p>";
        } else {
            echo "<p>❌ bank_financing_products表更新失败: " . $conn->error . "</p>";
        }
    }



    echo "<h3>数据库更新完成</h3>";
    echo "<p><a href='index.php'>返回首页</a></p>";

} catch (Exception $e) {
    echo "<h2>数据库更新失败</h2>";
    echo "<p>错误信息: " . $e->getMessage() . "</p>";
    echo "<p><a href='index.php'>返回首页</a></p>";
}

$conn->close();
?> 