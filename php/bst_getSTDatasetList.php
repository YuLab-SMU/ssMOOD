<?php
include 'config.php';


// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed']));
}

// SQL 查询
$sql = "SELECT dataset_id, species, age, sex, conditions, area, cells, way, scorst FROM datasets WHERE scorst = 'st'";

// 执行查询
$result = $conn->query($sql);

// 初始化数组来存储结果
$datasets = array();

// 检查结果
if ($result) {
    // 遍历结果集
    while ($row = $result->fetch_assoc()) {
        // 将每行数据添加到数组中
        $datasets[] = $row;
    }
    
    // 释放结果集
    $result->free();
    
    // 将数组转换为 JSON 格式
    echo json_encode($datasets);
} else {
    error_log("Query failed: " . $conn->error);
    http_response_code(500);
    echo json_encode(['error' => 'Query failed']);
}

// 关闭连接
$conn->close();
?>