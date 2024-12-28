<?php
header('Content-Type: application/json');
// 数据库配置
$host = 'localhost'; // 或者其他服务器地址
$dbname = 'ssmood';
$username = 'scast';
$password = '20030609';



// 创建数据库连接
$conn = new mysqli($host, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
$datasetId = $_GET['id'] ?? '';
// SQL 查询
$sql = "SELECT dataset_id, species, age, sex, conditions, region, cells, detail, scorst, information FROM datasets WHERE dataset_id = '$datasetId' AND scorst = 'st'";

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
    echo "错误: " . $conn->error;
}

// 关闭连接
$conn->close();
?>