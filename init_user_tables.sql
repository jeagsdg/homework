-- 创建用户表
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE COMMENT '用户名',
    password VARCHAR(255) NOT NULL COMMENT '密码哈希',
    balance DECIMAL(15,2) DEFAULT 50000.00 COMMENT '账户余额',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    last_login TIMESTAMP NULL COMMENT '最后登录时间'
);

-- 插入测试用户，密码为123456
INSERT INTO users (username, password, balance) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 100000.00),
('test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 50000.00);

-- 创建投资记录表
CREATE TABLE IF NOT EXISTS investments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT '用户ID',
    product_id INT NOT NULL COMMENT '产品ID',
    amount DECIMAL(15,2) NOT NULL COMMENT '投资金额',
    expected_revenue DECIMAL(15,2) NOT NULL COMMENT '预期收益',
    invest_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '投资日期',
    FOREIGN KEY (user_id) REFERENCES users(id)
); 