<?php
include 'config.php';
// 创建数据库连接
$conn = new mysqli($host, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// 查询 sc 和 st 的 cells 字段数值之和
$query = "
    SELECT 
        SUM(CASE WHEN scorst = 'sc' THEN cells ELSE 0 END) AS sc_cells_sum,
        SUM(CASE WHEN scorst = 'st' THEN cells ELSE 0 END) AS st_cells_sum
    FROM datasets
";

$result = $conn->query($query);

// 初始化结果数组
$resultArray = [
    'sc_cells_sum' => 0,
    'st_cells_sum' => 0
];

// 获取查询结果
if ($result) {
    $row = $result->fetch_assoc();
    $resultArray['sc_cells_sum'] = $row['sc_cells_sum'];
    $resultArray['st_cells_sum'] = $row['st_cells_sum'];
    $result->free();
}

// 关闭数据库连接
$conn->close();

echo json_encode($resultArray);
?>