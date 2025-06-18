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
- **开发环境**：推荐使用phpStudy、XAMPP、WAMP等集成环境

## 快速启动指南

### 1. 环境准备

- 安装phpStudy、XAMPP或WAMP等PHP集成环境
- 确保PHP版本≥7.0
- 确保MySQL版本≥5.7
- 确保启用了mysqli扩展

### 2. 部署项目

1. 将项目文件复制到Web服务器根目录（如phpStudy的WWW目录）
2. 创建一个名为`finance`的子目录，将所有文件放入此目录
3. 确保Web服务器有读写项目文件的权限

### 3. 配置数据库连接

编辑`config.php`文件，设置您的数据库连接信息：

```php
$db_host = 'localhost';     // 数据库主机
$db_user = 'root';          // 数据库用户名（phpStudy默认为root）
$db_pass = '';              // 数据库密码（phpStudy默认为空）
$db_name = 'financial_products'; // 数据库名称
```

### 4. 初始化系统

有两种方式初始化系统：

#### 方式一：自动初始化（推荐）

1. 在浏览器中访问：`http://localhost/finance/setup.php`
2. 按照页面提示，点击"初始化数据库"按钮
3. 系统将自动创建数据库、表结构并导入示例数据

#### 方式二：手动初始化

1. 使用phpMyAdmin或其他MySQL管理工具创建名为`financial_products`的数据库
2. 导入`database_init.sql`文件
3. 在浏览器中访问：`http://localhost/finance/index.php`确认系统正常运行

### 5. 访问系统

初始化完成后，通过以下链接访问系统各功能：

- **首页**：`http://localhost/finance/index.php`
- **银行理财页面**：`http://localhost/finance/financing.php`
- **理财公告页面**：`http://localhost/finance/announcement.php`

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

## 常见问题解决

### 日期显示问题

如果理财产品或公告的日期显示不正确（如显示固定日期而非当前日期），可以执行以下步骤：

1. 访问系统中的`update_database.php`页面进行数据更新
2. 点击"更新数据"按钮，系统将自动调整日期设置

### 产品数据问题

如果银行理财页面产品显示不完整或只有一个产品，可以：

1. 检查`config.php`中数据库连接配置是否正确
2. 确认MySQL服务是否正常运行
3. 访问系统中的"更新数据"按钮刷新产品信息

### 登录问题

如果无法登录系统：

1. 确保使用正确的用户名和密码（区分大小写）
2. 检查PHP是否启用了session支持
3. 确保浏览器允许Cookie

### 数据库连接错误

如果遇到数据库连接错误：

1. 确认MySQL服务已启动
2. 检查`config.php`中的用户名和密码
3. 尝试将数据库密码修改为空值（phpStudy默认设置）
4. 确保mysqli扩展已启用

## 系统维护

### 更新产品数据

系统提供了便捷的数据更新功能：

1. 在银行理财页面(`financing.php`)点击"更新数据"按钮
2. 系统将自动更新产品日期和其他相关数据

### 数据备份

建议定期备份数据库：

```sql
mysqldump -u root -p financial_products > backup.sql
```

### 添加新产品

可以通过以下方式添加新产品：

1. 使用phpMyAdmin直接向products表添加记录
2. 使用自定义脚本添加产品数据

## 文件结构说明

```
finance/
├── index.php                  # 首页（理财产品详情）
├── financing.php              # 银行理财产品列表页面
├── announcement.php           # 理财公告列表页面
├── announcement_detail.php    # 公告详情页面
├── setup.php                  # 项目初始化脚本
├── update_database.php        # 数据库更新工具
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
4. 限制数据库用户权限，遵循最小权限原则

## 开发者说明

项目使用简单的PHP+MySQL架构，不依赖任何框架，适合学习和教学使用。代码结构清晰，功能模块化，便于扩展和定制。如需添加新功能，建议遵循现有的代码组织结构。

## 后续开发计划

- 管理员后台界面
- 用户投资记录查询
- 移动端适配优化
- 更完善的产品筛选功能
- 用户个人中心
- 在线投资交易功能
