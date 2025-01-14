<?php

include 'config.php';

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


// 返回 JSON 数据
echo $response;
?>
