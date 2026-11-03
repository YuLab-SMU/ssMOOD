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

$sql = "SELECT dataset_id, cells FROM datasets WHERE scorst = 'st'";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    // 将数据存入数组
    while ($row = $result->fetch_assoc()) {
        $data[$row['dataset_id']] = $row['cells'];
    }
}

// 关闭连接
$conn->close();

// 将数据转换为 JSON 格式
$response = json_encode($data);


// 返回 JSON 数据
echo $response;
?>
