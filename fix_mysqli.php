<?php
// 显示所有错误
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
<head>
    <title>修复mysqli扩展问题</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; max-width: 800px; margin: 0 auto; }
        h1, h2 { color: #333; }
        .error { color: #cc0000; }
        .success { color: #007700; }
        .warning { color: #ff6600; }
        .step { background: #f5f5f5; padding: 15px; margin-bottom: 20px; border-left: 4px solid #666; }
        pre, code { background: #f0f0f0; padding: 2px 5px; border: 1px solid #ddd; }
        img { max-width: 100%; border: 1px solid #ddd; margin: 10px 0; }
        .btn { display: inline-block; padding: 10px 15px; background: #0066cc; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>修复mysqli扩展问题</h1>
    
    <div class="error">
        <h2>检测到问题: mysqli扩展未启用</h2>
        <p>您的PHP环境中未启用mysqli扩展，这是连接MySQL数据库所必需的。</p>
    </div>
    
    <h2>解决方案</h2>
    
    <div class="step">
        <h3>方法1: 通过phpStudy控制面板启用</h3>
        <ol>
            <li>打开phpStudy控制面板</li>
            <li>点击"扩展"或"扩展管理"选项卡</li>
            <li>找到"mysqli"扩展并勾选</li>
            <li>点击"保存"按钮</li>
            <li>重启Apache/Nginx服务</li>
        </ol>
        <p><img src="https://www.phpstudy.net/phpstudy/phpStudyManual/7.2.1/images/soft/phpext.png" alt="phpStudy扩展管理示意图"></p>
    </div>
    
    <div class="step">
        <h3>方法2: 手动修改php.ini文件</h3>
        <ol>
            <li>找到您的php.ini文件位置: <code><?php echo php_ini_loaded_file() ?: '未找到'; ?></code></li>
            <li>使用文本编辑器打开此文件</li>
            <li>搜索 <code>;extension=mysqli</code> 或 <code>;extension=php_mysqli.dll</code></li>
            <li>删除行首的分号(;)，使其变为 <code>extension=mysqli</code> 或 <code>extension=php_mysqli.dll</code></li>
            <li>保存文件</li>
            <li>重启PHP服务或Web服务器</li>
        </ol>
        <pre>
; 修改前
;extension=mysqli

; 修改后
extension=mysqli
        </pre>
    </div>
    
    <div class="step">
        <h3>方法3: 使用PDO替代mysqli</h3>
        <p>如果您无法启用mysqli扩展，可以考虑修改代码使用PDO连接数据库。</p>
        <p>这需要修改config.php文件中的数据库连接代码。</p>
    </div>
    
    <h2>验证修复</h2>
    <p>完成以上步骤后，请重新访问网站检查问题是否解决。</p>
    <p>您可以通过访问以下链接检查PHP扩展状态:</p>
    <ul>
        <li><a href="check_php_extensions.php">检查PHP扩展</a></li>
        <li><a href="phpinfo.php">查看phpinfo()</a></li>
    </ul>
    
    <div style="margin-top: 30px;">
        <a href="reset_and_initialize.php" class="btn">返回初始化页面</a>
        <a href="index.php" class="btn">返回首页</a>
    </div>
</body>
</html> 