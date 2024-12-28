<?php
header('Content-Type: application/json');

// 数据库连接信息
$servername = "127.0.0.1";
$username = "scast";
$password = "20030609";
$dbname = "ssmood";

// 创建 Redis 连接
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// 缓存键
$cacheKey = 'ssmood:differential_expression_data';

// 尝试从 Redis 获取缓存数据
$cachedData = $redis->get($cacheKey);

if ($cachedData) {
    // 如果缓存数据存在，直接返回缓存数据
    echo $cachedData;
} else {
    // 创建数据库连接
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 检查连接
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 执行查询
    $sql = "SELECT gene_id, cluster_label, p_value, fold_change 
            FROM differential_expression 
            WHERE p_value < 0.05
            ORDER BY RAND()
            LIMIT 1000";
    $result = $conn->query($sql);

    $data = array();

    if ($result->num_rows > 0) {
        // 将结果存储到数组中
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    // 将数据转换为 JSON 格式
    $response = json_encode($data);

    // 将结果缓存到 Redis，过期时间设置为 3600 秒（1小时）
    $redis->setex($cacheKey, 72000, $response);

    // 返回 JSON 数据
    echo $response;

    // 关闭数据库连接
    $conn->close();
}
?>
