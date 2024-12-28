<?php
header('Content-Type: application/json');

// 数据库连接信息
$servername = "localhost";
$username = "scast";
$password = "20030609";
$dbname = "ssmood";

// 创建 Redis 连接
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// 缓存键
$cacheKey = 'ssmood:chart1_data';

// 尝试从 Redis 获取缓存数据
$cachedData = $redis->get($cacheKey);

if ($cachedData) {
    // 如果缓存数据存在，直接返回缓存数据
    echo $cachedData;
    exit; // 退出脚本，避免继续执行数据库查询
}

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$sql = "SELECT cluster_label, COUNT(*) as cell_count FROM cells GROUP BY cluster_label";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    // 将数据存入数组
    while ($row = $result->fetch_assoc()) {
        $data[$row['cluster_label']] = $row['cell_count'];
    }
}

// 关闭连接
$conn->close();

// 将数据转换为 JSON 格式
$response = json_encode($data);

// 将结果缓存到 Redis，过期时间设置为 3600 秒（1小时）
$redis->setex($cacheKey, 72000, $response);

// 返回 JSON 数据
echo $response;
?>
