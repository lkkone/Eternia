#!/bin/bash

# 部署脚本 - 每次部署前清空数据库以验证初始化状态

set -e  # 遇到错误立即退出

echo "🚀 开始部署 Love 项目..."
echo ""

# 进入项目目录
cd "$(dirname "$0")"

echo "📦 步骤 1: 停止并删除现有容器..."
docker-compose down

echo ""
echo "🗑️  步骤 2: 清空数据库目录 (验证初始化状态)..."
if [ -d "data520/db" ]; then
    rm -rf data520/db
    echo "✅ 已删除 data520/db 目录"
else
    echo "⚠️  data520/db 目录不存在，跳过删除"
fi

echo ""
echo "🔨 步骤 3: 构建 Docker 镜像..."
docker build -t love:v520 .

echo ""
echo "🚀 步骤 4: 启动容器..."
docker-compose up -d

echo ""
echo "⏳ 步骤 5: 等待数据库健康检查..."
sleep 5

echo ""
echo "✅ 部署完成！"
echo ""
echo "📊 容器状态："
docker-compose ps

echo ""
echo "🔍 验证数据库初始化..."
sleep 3
docker exec home-love-db mysql -uroot -plove1314520 love_db -e "SHOW TABLES;" 2>/dev/null || echo "⚠️  数据库可能还在初始化中..."

echo ""
echo "🎉 全部完成！访问 http://localhost:1314"

