// 全局变量
let currentProduct = null;
let isLoggedIn = false;
let userData = null;

// 页面加载完成后执行
document.addEventListener('DOMContentLoaded', function() {
    // 默认显示第一个内容
    document.querySelectorAll('.productInfoContentText')[0].style.display = 'block';
    
    // 加载产品数据
    loadProductData();
    
    // 检查用户登录状态
    checkLoginStatus();
    
    // 登录按钮点击事件
    document.getElementById('loginBtn').addEventListener('click', function() {
        showLoginModal();
    });
    
    // 注册按钮点击事件（暂时重定向到登录）
    document.getElementById('registerBtn').addEventListener('click', function() {
        showLoginModal();
    });
    
    // 登录表单提交事件
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        login();
    });
    
    // 关闭模态框的点击事件
    document.querySelector('.close').addEventListener('click', function() {
        hideLoginModal();
    });
    
    // 投资按钮点击事件
    document.getElementById('investBtn').addEventListener('click', function() {
        invest();
    });
    
    // 投资金额输入框事件 - 计算预期收益
    document.getElementById('investmentAmount').addEventListener('input', function() {
        calculateProjectedRevenue();
    });
});

// 显示指定索引的内容
function showContent(index) {
    // 隐藏所有内容
    document.querySelectorAll('.productInfoContentText').forEach(function(content) {
        content.style.display = 'none';
    });
    // 显示选中的内容
    document.querySelectorAll('.productInfoContentText')[index].style.display = 'block';
}

// 加载产品数据
function loadProductData() {
    // 创建AJAX请求
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_product_data.php?ajax=1&product_code=200110', true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                currentProduct = response;
                
                // 更新页面上的产品数据
                document.getElementById('productName').textContent = response.product_name + ' ' + response.product_code + '期';
                document.getElementById('annualRate').textContent = response.annual_rate;
                document.getElementById('investmentPeriod').textContent = response.investment_period;
                document.getElementById('minInvestment').textContent = response.min_investment;
                document.getElementById('startDate').textContent = response.start_date;
                document.getElementById('endDate').textContent = response.end_date;
                document.getElementById('incrementAmount').textContent = response.increment_amount + '元';
                document.getElementById('timer').textContent = response.remaining_time;
                document.getElementById('remaininginvestableAmount').textContent = response.remaining_amount;
                
                // 更新进度条
                const progressBar = document.getElementById('progressBar1');
                const percentage = document.getElementById('percentage');
                progressBar.value = response.progress;
                percentage.textContent = response.progress + '%';
                
                // 如果有用户数据，计算预期收益
                if (userData) {
                    calculateProjectedRevenue();
                }
            } catch (e) {
                console.error('解析产品数据失败:', e);
            }
        } else {
            console.error('获取产品数据失败');
        }
    };
    
    xhr.onerror = function() {
        console.error('AJAX请求错误');
    };
    
    xhr.send();
}

// 检查用户登录状态
function checkLoginStatus() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'user_api.php?action=get_user_info', true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                
                if (response.success) {
                    // 用户已登录
                    isLoggedIn = true;
                    userData = response;
                    
                    // 更新UI显示用户信息
                    updateUIForLoggedInUser();
                } else {
                    // 用户未登录
                    isLoggedIn = false;
                    userData = null;
                }
            } catch (e) {
                console.error('解析用户数据失败:', e);
            }
        } else {
            console.error('获取用户数据失败');
        }
    };
    
    xhr.onerror = function() {
        console.error('AJAX请求错误');
    };
    
    xhr.send();
}

// 更新UI显示用户信息
function updateUIForLoggedInUser() {
    if (!userData) return;
    
    // 更新账户余额
    document.getElementById('accountbalance').textContent = userData.balance;
    
    // 更新用户控制区域
    const userControls = document.getElementById('userControls');
    userControls.innerHTML = `
        <div class="user-info">欢迎, ${userData.username}</div>
        <button class="login" id="logoutBtn">注销</button>
    `;
    
    // 添加注销按钮点击事件
    document.getElementById('logoutBtn').addEventListener('click', function() {
        logout();
    });
    
    // 计算预期收益
    calculateProjectedRevenue();
}

// 显示登录模态框
function showLoginModal() {
    document.getElementById('loginModal').style.display = 'block';
}

// 隐藏登录模态框
function hideLoginModal() {
    document.getElementById('loginModal').style.display = 'none';
}

// 用户登录
function login() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    if (!username || !password) {
        alert('请输入用户名和密码');
        return;
    }
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'user_api.php?action=login', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                
                if (response.success) {
                    // 登录成功
                    isLoggedIn = true;
                    userData = response;
                    
                    // 更新UI
                    updateUIForLoggedInUser();
                    
                    // 隐藏登录模态框
                    hideLoginModal();
                } else {
                    // 登录失败
                    alert('登录失败: ' + response.message);
                }
            } catch (e) {
                console.error('解析登录响应失败:', e);
                alert('登录过程中发生错误');
            }
        } else {
            console.error('登录请求失败');
            alert('登录请求失败');
        }
    };
    
    xhr.onerror = function() {
        console.error('AJAX请求错误');
        alert('网络错误，请稍后重试');
    };
    
    xhr.send(`username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`);
}

// 用户注销
function logout() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'user_api.php?action=logout', true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                
                if (response.success) {
                    // 注销成功
                    isLoggedIn = false;
                    userData = null;
                    
                    // 更新UI
                    document.getElementById('userControls').innerHTML = `
                        <button class="login" id="loginBtn">登录</button>
                        <button class="register" id="registerBtn">注册</button>
                    `;
                    
                    // 重新添加登录和注册按钮的事件监听
                    document.getElementById('loginBtn').addEventListener('click', function() {
                        showLoginModal();
                    });
                    
                    document.getElementById('registerBtn').addEventListener('click', function() {
                        showLoginModal();
                    });
                    
                    // 更新账户余额显示
                    document.getElementById('accountbalance').textContent = '请登录查看';
                    
                    // 清空预计收益
                    document.getElementById('projectedrevenue').textContent = '0.0';
                    
                    alert('您已成功注销');
                }
            } catch (e) {
                console.error('解析注销响应失败:', e);
            }
        } else {
            console.error('注销请求失败');
        }
    };
    
    xhr.onerror = function() {
        console.error('AJAX请求错误');
    };
    
    xhr.send();
}

// 计算预期收益
function calculateProjectedRevenue() {
    if (!currentProduct) return;
    
    const investmentAmountInput = document.getElementById('investmentAmount');
    const amount = parseFloat(investmentAmountInput.value);
    
    if (isNaN(amount) || amount <= 0) {
        document.getElementById('projectedrevenue').textContent = '0.0';
        return;
    }
    
    // 计算预期收益
    const dailyRate = currentProduct.annual_rate / 100 / 365;
    const projectedRevenue = (dailyRate * currentProduct.investment_period * amount).toFixed(2);
    
    // 更新预期收益显示
    document.getElementById('projectedrevenue').textContent = projectedRevenue;
}

// 投资函数
function invest() {
    // 检查用户是否登录
    if (!isLoggedIn) {
        alert('请先登录');
        showLoginModal();
        return;
    }
    
    // 获取输入的投资金额
    const amount = parseFloat(document.getElementById('investmentAmount').value);
    
    // 检查金额是否有效
    if (isNaN(amount) || amount <= 0) {
        alert('请输入有效的投资金额');
        return;
    }
    
    // 检查金额是否符合要求
    if (amount < currentProduct.min_investment) {
        alert(`投资金额不能低于最低投资额 ${currentProduct.min_investment} 元`);
        return;
    }
    
    // 检查金额是否是递增金额的整数倍
    if (amount % currentProduct.increment_amount !== 0) {
        alert(`投资金额必须是 ${currentProduct.increment_amount} 元的整数倍`);
        return;
    }
    
    // 检查账户余额是否足够
    if (parseFloat(userData.balance) < amount) {
        alert('账户余额不足，请充值后再投资');
        return;
    }
    
    // 检查剩余可投金额是否足够
    if (parseFloat(currentProduct.remaining_amount) < amount) {
        alert('产品剩余可投金额不足');
        return;
    }
    
    // 发送投资请求
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'user_api.php?action=invest', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                
                if (response.success) {
                    // 投资成功
                    alert('投资成功！');
                    
                    // 更新账户余额
                    userData.balance = response.new_balance;
                    document.getElementById('accountbalance').textContent = response.new_balance;
                    
                    // 更新剩余可投金额
                    currentProduct.remaining_amount = response.new_remaining_amount;
                    document.getElementById('remaininginvestableAmount').textContent = response.new_remaining_amount;
                    
                    // 更新预计收益
                    document.getElementById('projectedrevenue').textContent = response.expected_revenue;
                    
                    // 更新进度条
                    updateProgressBar(currentProduct);
                    
                    // 清空投资金额输入框
                    document.getElementById('investmentAmount').value = '';
                } else {
                    // 投资失败
                    alert('投资失败: ' + response.message);
                }
            } catch (e) {
                console.error('解析投资响应失败:', e);
                alert('投资过程中发生错误');
            }
        } else {
            console.error('投资请求失败');
            alert('投资请求失败');
        }
    };
    
    xhr.onerror = function() {
        console.error('AJAX请求错误');
        alert('网络错误，请稍后重试');
    };
    
    xhr.send(`product_id=1&amount=${encodeURIComponent(amount)}`);
}

// 更新进度条
function updateProgressBar(product) {
    const totalAmount = parseFloat(product.total_amount);
    const remainingAmount = parseFloat(product.remaining_amount);
    const investedAmount = totalAmount - remainingAmount;
    const progress = Math.round((investedAmount / totalAmount) * 100);
    
    const progressBar = document.getElementById('progressBar1');
    const percentage = document.getElementById('percentage');
    
    // 更新进度条值
    progressBar.value = progress;
    percentage.textContent = progress + '%';
}

// 倒计时函数（保留原有功能）
function countdown() {
    // 获取剩余时间字符串
    const timerElement = document.getElementById('timer');
    const timeStr = timerElement.textContent;
    
    // 如果已经从服务器获取了剩余时间，不需要再做倒计时
    if (timeStr !== '7天7时12分31秒') {
        return;
    }
    
    let days, hours, minutes, seconds;
    
    // 解析初始时间
    const timeParts = timeStr.match(/(\d+)天(\d+)时(\d+)分(\d+)秒/);
    if (timeParts) {
        days = parseInt(timeParts[1]);
        hours = parseInt(timeParts[2]);
        minutes = parseInt(timeParts[3]);
        seconds = parseInt(timeParts[4]);
    } else {
        // 默认初始值
        days = 7;
        hours = 7;
        minutes = 12;
        seconds = 31;
    }
    
    // 定时器更新函数
    const intervalId = setInterval(() => {
        // 更新时间
        seconds--;
        if (seconds < 0) {
            seconds = 59;
            minutes--;
            if (minutes < 0) {
                minutes = 59;
                hours--;
                if (hours < 0) {
                    hours = 23;
                    days--;
                    if (days < 0) {
                        clearInterval(intervalId); // 清除定时器
                        timerElement.textContent = "已截止"; // 显示结束信息
                        return;
                    }
                }
            }
        }
        
        // 更新显示
        timerElement.textContent = `${days}天${hours}时${minutes}分${seconds}秒`;
    }, 1000);
}

// 页面加载时启动倒计时
window.onload = function() {
    // 如果定时器显示的是默认值，才启动倒计时
    if (document.getElementById('timer').textContent === '7天7时12分31秒') {
        countdown();
    }
};