# LikeGirl 情侣小站

一个精美的情侣纪念网站，记录你们的爱情故事。

## 功能简介

| 功能 | 描述 |
|-----|------|
| 🏠 **首页** | 展示情侣信息、在一起的时间计数器 |
| 📝 **点点滴滴** | 记录生活中的点滴故事 |
| 📸 **恋爱相册** | 上传和展示甜蜜照片 |
| 📋 **恋爱清单** | 记录想要一起完成的事项 |
| 🎁 **礼物墙** | 展示互赠的礼物 |
| 💬 **关于页面** | 互动对话式介绍 |
| ⚙️ **后台管理** | 完整的内容管理系统 |

## 环境要求

- PHP >= 7.4
- MySQL >= 5.6
- Nginx

---

## Ubuntu + Nginx 部署指南

### 1. 安装依赖

```bash
# 更新系统
sudo apt update && sudo apt upgrade -y

# 安装 Nginx
sudo apt install nginx -y

# 安装 PHP 和必要扩展
sudo apt install php-fpm php-mysql php-curl php-gd php-mbstring -y

# 安装 MySQL
sudo apt install mysql-server -y
```

### 2. 配置 MySQL

```bash
# 启动 MySQL 安全配置
sudo mysql_secure_installation

# 登录 MySQL
sudo mysql -u root -p

# 创建数据库和用户
CREATE DATABASE likegirl DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'likegirl'@'localhost' IDENTIFIED BY '你的密码';
GRANT ALL PRIVILEGES ON likegirl.* TO 'likegirl'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# 导入数据库
mysql -u likegirl -p likegirl < /path/to/LikeGirl/admin/install/likegirl.sql
```

### 3. 上传网站文件

```bash
# 创建网站目录
sudo mkdir -p /var/www/likegirl

# 复制项目文件（根据你的实际路径修改）
sudo cp -r /path/to/LikeGirl/* /var/www/likegirl/

# 设置权限
sudo chown -R www-data:www-data /var/www/likegirl
sudo chmod -R 755 /var/www/likegirl
```

### 4. 配置 Nginx

创建 Nginx 配置文件：

```bash
sudo nano /etc/nginx/sites-available/likegirl
```

添加以下内容：

```nginx
server {
    listen 80;
    server_name your-domain.com;  # 替换为你的域名或IP
    root /var/www/likegirl;
    index index.php index.html;

    # 日志
    access_log /var/log/nginx/likegirl_access.log;
    error_log /var/log/nginx/likegirl_error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP 处理
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # 禁止访问隐藏文件
    location ~ /\. {
        deny all;
    }

    # 静态资源缓存
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
```

启用站点：

```bash
# 创建软链接
sudo ln -s /etc/nginx/sites-available/likegirl /etc/nginx/sites-enabled/

# 测试配置
sudo nginx -t

# 重启 Nginx
sudo systemctl restart nginx
```

### 5. 配置数据库连接

编辑数据库配置文件：

```bash
sudo nano /var/www/likegirl/admin/Config_DB.php
```

修改为你的数据库信息：

```php
<?php
$db_address = "localhost";
$db_username = "likegirl";
$db_password = "你的密码";
$db_name = "likegirl";
?>
```

### 6. 访问网站

1. 打开浏览器访问 `http://your-domain.com`
2. 后台管理访问 `http://your-domain.com/admin`
3. 默认账号密码：
   - boy: `admin1` / `123456`
   - girl: `admin2` / `123456`

> ⚠️ **重要**：部署后请立即修改默认密码！

---

## HTTPS 配置（可选）

使用 Let's Encrypt 免费证书：

```bash
# 安装 Certbot
sudo apt install certbot python3-certbot-nginx -y

# 申请证书
sudo certbot --nginx -d your-domain.com

# 自动续期测试
sudo certbot renew --dry-run
```

---

## 常见问题

**Q: 页面显示空白或 500 错误**
```bash
# 检查 PHP 错误日志
sudo tail -f /var/log/nginx/likegirl_error.log

# 检查文件权限
sudo chown -R www-data:www-data /var/www/likegirl
```

**Q: 数据库连接失败**
```bash
# 检查 MySQL 服务状态
sudo systemctl status mysql

# 验证数据库连接
mysql -u likegirl -p -h localhost likegirl
```

---

## 许可证

本项目基于 [GPL-3.0](LICENSE) 开源协议。
