<?php
include 'config.php';

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


// 返回 JSON 数据
echo $response;

// 关闭数据库连接
$conn->close();
?>