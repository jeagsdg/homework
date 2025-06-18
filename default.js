// 全局变量
let currentProduct = null;
let isLoggedIn = false;
let userData = null;

// 页面加载完成后执行
document.addEventListener('DOMContentLoaded', function() {
    // 默认显示第一个内容
    const productInfoContentElements = document.querySelectorAll('.productInfoContentText');
    if (productInfoContentElements && productInfoContentElements.length > 0) {
        productInfoContentElements[0].style.display = 'block';
    }
    
    // 加载产品数据
    loadProductData();
    
    // 检查用户登录状态
    checkLoginStatus();
    
    // 登录按钮点击事件
    const loginBtn = document.getElementById('loginBtn');
    if (loginBtn) {
        loginBtn.addEventListener('click', function() {
            showLoginModal();
        });
    }
    
    // 注册按钮点击事件（暂时重定向到登录）
    const registerBtn = document.getElementById('registerBtn');
    if (registerBtn) {
        registerBtn.addEventListener('click', function() {
            showLoginModal();
        });
    }
    
    // 登录表单提交事件
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            login();
        });
    }
    
    // 关闭模态框的点击事件
    const closeBtn = document.querySelector('.close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            hideLoginModal();
        });
    }
    
    // 投资按钮点击事件
    const investBtn = document.getElementById('investBtn');
    if (investBtn) {
        investBtn.addEventListener('click', function() {
            invest();
        });
    }
    
    // 投资金额输入框事件 - 计算预期收益
    const investmentAmount = document.getElementById('investmentAmount');
    if (investmentAmount) {
        investmentAmount.addEventListener('input', function() {
            calculateProjectedRevenue();
        });
    }
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
                // 检查响应是否包含认证错误
                if (xhr.responseText.indexOf("authentication method unknown") !== -1) {
                    console.error('MySQL 8.0认证错误:', xhr.responseText);
                    alert('MySQL 8.0认证错误，请访问mysql_fix.php页面查看解决方案。');
                    window.location.href = 'mysql_fix.php';
                    return;
                }
                
                // 检查响应是否以"连接失败"开头
                if (xhr.responseText.indexOf("连接失败") === 0 || 
                    xhr.responseText.indexOf("<") === 0) {
                    console.error('数据库连接失败:', xhr.responseText);
                    alert('数据库连接失败，请联系管理员或访问mysql_fix.php页面查看解决方案。');
                    return;
                }
                
                const response = JSON.parse(xhr.responseText);
                
                // 检查是否有错误消息
                if (response.error) {
                    console.error('API错误:', response.error, response.message);
                    if (response.error === 'MySQL 8.0认证错误') {
                        alert('MySQL 8.0认证错误，请访问mysql_fix.php页面查看解决方案。');
                        window.location.href = 'mysql_fix.php';
                    } else {
                        alert('错误: ' + response.message);
                    }
                    return;
                }
                
                currentProduct = response;
                
                // --- 安全地更新页面元素 ---
                // 创建一个辅助函数来检查元素是否存在
                const updateElementText = (id, text) => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.textContent = text;
                    }
                };

                // 更新页面上的产品数据
                updateElementText('productName', response.product_name);
                updateElementText('annualRate', response.annual_rate);
                updateElementText('investmentPeriod', response.investment_period);
                updateElementText('minInvestment', response.min_investment);
                updateElementText('startDate', response.start_date);
                updateElementText('endDate', response.end_date);
                updateElementText('incrementAmount', response.increment_amount + '元');
                updateElementText('timer', response.remaining_time);
                updateElementText('remaininginvestableAmount', response.remaining_amount);
                
                // 更新进度条
                const progressBar = document.getElementById('progressBar1');
                const percentage = document.getElementById('percentage');
                if (progressBar && percentage) {
                    progressBar.value = response.progress;
                    percentage.textContent = response.progress + '%';
                }
                
                // 如果有用户数据，计算预期收益
                if (userData) {
                    calculateProjectedRevenue();
                }
            } catch (e) {
                console.error('解析产品数据失败:', e, '原始响应:', xhr.responseText);
                
                // 检查是否可能是MySQL 8.0认证错误
                if (xhr.responseText.indexOf("authentication method unknown") !== -1) {
                    alert('MySQL 8.0认证错误，请访问mysql_fix.php页面查看解决方案。');
                    window.location.href = 'mysql_fix.php';
                } else {
                    alert('加载产品数据失败，请刷新页面重试。');
                }
            }
        } else {
            console.error('获取产品数据失败');
            alert('获取产品数据失败，请检查网络连接。');
        }
    };
    
    xhr.onerror = function() {
        console.error('AJAX请求错误');
        alert('网络连接失败，请检查网络设置。');
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
                // 检查响应是否包含认证错误
                if (xhr.responseText.indexOf("authentication method unknown") !== -1) {
                    console.error('MySQL 8.0认证错误:', xhr.responseText);
                    return;
                }
                
                // 检查响应是否以HTML或错误信息开头
                if (xhr.responseText.indexOf("连接失败") === 0 || 
                    xhr.responseText.indexOf("<") === 0) {
                    console.error('数据库连接失败:', xhr.responseText);
                    return;
                }
                
                const response = JSON.parse(xhr.responseText);
                
                // 检查是否有错误消息
                if (response.error) {
                    console.error('API错误:', response.error, response.message);
                    return;
                }
                
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
                console.error('解析用户数据失败:', e, '原始响应:', xhr.responseText);
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
    
    // 辅助函数：安全地更新元素内容
    const updateElementText = (id, text) => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = text;
        }
    };
    
    // 更新账户余额
    updateElementText('accountbalance', userData.balance);
    
    // 更新用户控制区域
    const userControls = document.getElementById('userControls');
    if (userControls) {
        // 如果已经有HTML内容，说明服务器端已经渲染了登录状态
        // 不需要再次更新
    }
    
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
                    
                    // 辅助函数：安全地更新元素内容
                    const updateElementText = (id, text) => {
                        const element = document.getElementById(id);
                        if (element) {
                            element.textContent = text;
                        }
                    };
                    
                    // 更新UI
                    const userControls = document.getElementById('userControls');
                    if (userControls) {
                        userControls.innerHTML = `
                            <button class="login" id="loginBtn">登录</button>
                            <button class="register" id="registerBtn">注册</button>
                        `;
                        
                        // 重新添加登录和注册按钮的事件监听
                        const loginBtn = document.getElementById('loginBtn');
                        if (loginBtn) {
                            loginBtn.addEventListener('click', function() {
                                showLoginModal();
                            });
                        }
                        
                        const registerBtn = document.getElementById('registerBtn');
                        if (registerBtn) {
                            registerBtn.addEventListener('click', function() {
                                showLoginModal();
                            });
                        }
                    }
                    
                    // 更新账户余额显示
                    updateElementText('accountbalance', '请登录查看');
                    
                    // 清空预计收益
                    updateElementText('projectedrevenue', '0.0');
                    
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
    // 获取投资金额输入框
    const investmentAmountInput = document.getElementById('investmentAmount');
    if (!investmentAmountInput) return;
    
    // 获取投资金额
    const investmentAmount = parseFloat(investmentAmountInput.value);
    if (isNaN(investmentAmount) || investmentAmount <= 0) {
        // 如果投资金额无效，则将预期收益设为0
        const projectedRevenueElement = document.getElementById('projectedrevenue');
        if (projectedRevenueElement) {
            projectedRevenueElement.textContent = '0.00';
        }
        return;
    }
    
    // 确保currentProduct存在
    if (!currentProduct) {
        console.error('无法计算预期收益: currentProduct 未定义');
        return;
    }
    
    try {
        // 获取年化利率
        const annualRate = parseFloat(currentProduct.annual_rate) || 3.07;
        
        // 获取投资期限（天数）
        const investmentPeriod = parseInt(currentProduct.investment_period) || 90;
        
        // 计算预期收益：投资金额 * 年化利率 / 365 * 投资期限
        const projectedRevenue = (investmentAmount * (annualRate / 100) * investmentPeriod / 365).toFixed(2);
        
        // 更新预期收益显示
        const projectedRevenueElement = document.getElementById('projectedrevenue');
        if (projectedRevenueElement) {
            projectedRevenueElement.textContent = projectedRevenue;
        }
    } catch (e) {
        console.error('计算预期收益时出错:', e);
    }
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
    if (progressBar && percentage) {
        progressBar.value = progress;
        percentage.textContent = progress + '%';
    }
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
    // 检查timer元素是否存在
    const timerElement = document.getElementById('timer');
    if (timerElement && timerElement.textContent === '7天7时12分31秒') {
        countdown();
    }
};