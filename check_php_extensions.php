<?php
// 显示所有错误
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 检查mysqli扩展是否加载
$mysqli_loaded = extension_loaded('mysqli');
$mysqli_version = $mysqli_loaded ? mysqli_get_client_info() : 'N/A';

// 检查PDO扩展是否加载
$pdo_loaded = extension_loaded('pdo');
$pdo_mysql_loaded = extension_loaded('pdo_mysql');

// 获取所有已加载的扩展
$loaded_extensions = get_loaded_extensions();
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP环境检查</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; overflow: auto; }
        table { border-collapse: collapse; width: 100%; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>PHP环境检查</h1>
    
    <h2>基本信息</h2>
    <table>
        <tr><td>PHP版本</td><td><?php echo phpversion(); ?></td></tr>
        <tr><td>PHP接口</td><td><?php echo php_sapi_name(); ?></td></tr>
        <tr><td>操作系统</td><td><?php echo PHP_OS; ?></td></tr>
        <tr><td>PHP配置文件路径</td><td><?php echo php_ini_loaded_file(); ?></td></tr>
    </table>
    
    <h2>MySQL相关扩展</h2>
    <table>
        <tr>
            <th>扩展名</th>
            <th>状态</th>
            <th>版本</th>
        </tr>
        <tr>
            <td>mysqli</td>
            <td>
                <?php if($mysqli_loaded): ?>
                <span class="success">已加载</span>
                <?php else: ?>
                <span class="error">未加载</span>
                <?php endif; ?>
            </td>
            <td><?php echo $mysqli_version; ?></td>
        </tr>
        <tr>
            <td>PDO</td>
            <td>
                <?php if($pdo_loaded): ?>
                <span class="success">已加载</span>
                <?php else: ?>
                <span class="error">未加载</span>
                <?php endif; ?>
            </td>
            <td>-</td>
        </tr>
        <tr>
            <td>PDO_MySQL</td>
            <td>
                <?php if($pdo_mysql_loaded): ?>
                <span class="success">已加载</span>
                <?php else: ?>
                <span class="error">未加载</span>
                <?php endif; ?>
            </td>
            <td>-</td>
        </tr>
    </table>
    
    <h2>所有已加载的扩展</h2>
    <pre><?php print_r($loaded_extensions); ?></pre>
    
    <h2>phpinfo()</h2>
    <p><a href="phpinfo.php" target="_blank">查看完整的phpinfo()</a></p>
    
    <h2>解决方案</h2>
    <?php if (!$mysqli_loaded): ?>
    <div class="error">
        <p><strong>mysqli扩展未加载!</strong></p>
        <p>请按照以下步骤启用mysqli扩展:</p>
        <ol>
            <li>打开php.ini文件 (位置: <?php echo php_ini_loaded_file(); ?>)</li>
            <li>找到 <code>;extension=mysqli</code> 这一行</li>
            <li>删除前面的分号 <code>;</code> 以启用扩展</li>
            <li>保存文件并重启PHP服务</li>
        </ol>
        
        <p><strong>在phpStudy中启用mysqli扩展:</strong></p>
        <ol>
            <li>打开phpStudy控制面板</li>
            <li>点击"扩展"选项卡</li>
            <li>找到"mysqli"并勾选</li>
            <li>重启Apache或Nginx服务</li>
        </ol>
    </div>
    <?php else: ?>
    <div class="success">
        <p><strong>mysqli扩展已正确加载!</strong></p>
        <p>如果仍然遇到问题，请检查数据库连接参数是否正确。</p>
    </div>
    <?php endif; ?>
    
    <hr>
    <p><a href="reset_and_initialize.php">返回数据库初始化页面</a></p>
</body>
</html> 