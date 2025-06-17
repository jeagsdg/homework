<?php
// 启动session（如果尚未启动）
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 检查用户登录状态
function isUserLoggedIn() {
    // 首先检查SESSION
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        return true;
    }
    
    // 然后检查COOKIE
    if (isset($_COOKIE['user_logged_in']) && $_COOKIE['user_logged_in'] === 'true' && isset($_COOKIE['username'])) {
        // 从cookie恢复会话
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $_COOKIE['username'];
        // 这里可以添加从数据库获取用户ID的代码
        return true;
    }
    
    return false;
}

// 获取当前登录用户名
function getCurrentUsername() {
    if (isset($_SESSION['username'])) {
        return $_SESSION['username'];
    } elseif (isset($_COOKIE['username'])) {
        return $_COOKIE['username'];
    }
    return null;
}

// 获取用户登录/注册/登出按钮HTML
function getUserControlsHTML() {
    if (isUserLoggedIn()) {
        $username = getCurrentUsername();
        return '
            <div class="Btn" id="userControls">
                <span style="color: #fff; margin-right: 10px;">您好, ' . htmlspecialchars($username) . '</span>
                <button class="login" onclick="window.location.href=\'logout_process.php\'">退出登录</button>
            </div>
        ';
    } else {
        return '
            <div class="Btn" id="userControls">
                <button class="login" id="loginBtn">登录</button>
                <button class="register" id="registerBtn">注册</button>
            </div>
        ';
    }
}
?> 