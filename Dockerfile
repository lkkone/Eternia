FROM docker.1ms.run/library/php:8.1.31-fpm-alpine3.21

# 安装扩展
RUN docker-php-ext-install pdo_mysql mysqli

# 拷贝代码
COPY ./Eternia/ /app

# 设置工作目录
WORKDIR /app

# 暴露端口
EXPOSE 1314

# 使用 JSON 数组形式的 CMD（推荐写法，防止信号丢失问题）
CMD ["php", "-S", "0.0.0.0:1314", "-t", "/app"]
