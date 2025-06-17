# 金融理财产品展示与投资系统

这是一个完整的金融理财产品展示与投资系统，实现了产品展示、用户登录注册、理财产品查询、公告查看等功能，采用PHP和MySQL技术栈开发。

## 功能特点

1. **用户认证系统**
   - 用户注册与登录
   - 基于Session和Cookie的登录状态维护
   - 用户登出功能

2. **金融产品展示**
   - 从MySQL数据库动态拉取金融产品数据
   - 显示产品详情（年化利率、投资期限、起投金额等）
   - 实时计算预期收益

3. **银行理财产品查询**
   - 按产品类型、产品代码筛选
   - 产品列表展示
   - 详细的产品信息查看

4. **理财公告系统**
   - 按年份查看公告
   - 公告详情页面
   - 公告历史记录浏览

5. **响应式界面**
   - 适配不同屏幕尺寸
   - 良好的用户交互体验

## 技术栈

- **前端**：HTML, CSS, JavaScript
- **后端**：PHP 7.0+
- **数据库**：MySQL 5.7+
- **开发环境**：可用XAMPP, WAMP, LAMP等集成环境

## 安装与设置

### 1. 环境要求

- PHP 7.0或更高版本
- MySQL 5.7或更高版本
- Web服务器（Apache/Nginx）

### 2. 下载项目

```bash
git clone https://github.com/yourusername/finance-product-system.git
cd finance-product-system
```

或直接下载项目ZIP文件并解压到Web服务器目录。

### 3. 配置数据库连接

编辑`config.php`文件，设置您的数据库连接信息：

```php
$db_host = 'localhost';     // 数据库主机
$db_user = 'root';          // 数据库用户名
$db_pass = 'password';      // 数据库密码
$db_name = 'financial_products'; // 数据库名称
```

### 4. 初始化项目

访问`setup.php`页面，系统会引导您完成数据库初始化：

1. 打开浏览器，访问`http://yourserver/project-folder/setup.php`
2. 检查数据库连接状态
3. 点击"初始化数据库"按钮创建表结构并导入示例数据
4. 系统会显示初始化结果和测试账户信息

### 5. 访问系统

初始化完成后，可以通过以下链接访问系统：

- **首页**：`http://yourserver/project-folder/index.php`
- **银行理财页面**：`http://yourserver/project-folder/financing.php`
- **理财公告页面**：`http://yourserver/project-folder/announcement.php`

## 测试账号

系统预置了两个测试账号：

- **管理员账号**
  - 用户名：admin
  - 密码：123456
  - 余额：100,000.00元

- **普通用户账号**
  - 用户名：test
  - 密码：123456
  - 余额：50,000.00元

## 文件结构

```
project/
├── index.php                  # 首页
├── financing.php              # 银行理财页面
├── announcement.php           # 理财公告列表页面
├── announcement_detail.php    # 公告详情页面
├── setup.php                  # 项目初始化脚本
├── database_init.sql          # 数据库初始化SQL脚本
├── config.php                 # 数据库配置文件
├── user_api.php               # 用户相关API
├── check_login.php            # 登录状态检查
├── login_process.php          # 登录处理
├── logout_process.php         # 登出处理
├── register.php               # 用户注册页面
├── register_process.php       # 注册处理
├── welcome.php                # 登录成功页面
├── register_success.php       # 注册成功页面
├── get_product_data.php       # 产品数据API
├── get_product_types.php      # 产品类型API
├── get_financing_products.php # 理财产品API
├── get_announcements.php      # 公告数据API
├── get_announcement_years.php # 公告年份API
├── default.css                # 样式表
└── default.js                 # JavaScript脚本
```

## 重要提示

1. **请使用index.php而不是index.html访问首页**，这样才能确保用户登录状态正确显示
2. 确保PHP具有对文件目录的读写权限
3. 如果遇到数据库连接问题，请检查config.php中的设置是否正确
4. 默认的数据库名为"financial_products"，如果您需要使用其他名称，请同时修改config.php和database_init.sql

## 安全注意事项

1. 生产环境中应更改默认的测试账户密码
2. 建议配置HTTPS以保护用户数据传输安全
3. 定期备份数据库以防数据丢失

## 常见问题

### Q: 登录后显示"未登录"状态怎么办？
A: 请确保使用index.php而不是index.html访问首页，并检查PHP的session配置是否正常。

### Q: 初始化数据库失败怎么办？
A: 请检查数据库连接设置是否正确，以及用户是否有创建数据库和表的权限。

### Q: 如何添加新的理财产品或公告？
A: 您可以直接通过MySQL管理工具向对应的表中添加数据，或开发额外的管理界面实现该功能。

## 后续开发计划

- 管理员后台界面
- 用户投资记录查询
- 移动端适配优化
- 更完善的产品筛选功能
- 用户个人中心 