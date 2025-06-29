<?php
include 'config.php';
// 创建数据库连接
$conn = new mysqli($host, $username, $password, $dbname);
// 指定的 dataset_id
$datasetId = $_GET['id'] ?? '';

// 创建数据库连接
$conn = new mysqli($host, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// 查询各个细胞分类的数量
$query = "
    SELECT cluster_label2, COUNT(*) as cell_count
    FROM spatial_cells
    WHERE dataset_id = ?
    GROUP BY cluster_label2
";

// 准备 SQL 语句
$stmt = $conn->prepare($query);

// 绑定参数
$stmt->bind_param('s', $datasetId);

// 执行查询
$stmt->execute();

// 获取结果
$result = $stmt->get_result();

// 初始化结果数组
$resultArray = [];

// 获取查询结果
while ($row = $result->fetch_assoc()) {
    $resultArray[$row['cluster_label2']] = (int) $row['cell_count'];
}

// 关闭数据库连接
$stmt->close();
$conn->close();

echo json_encode($resultArray);
?>