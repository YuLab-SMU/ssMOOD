<?php
include 'config.php';

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 初始化结果数组
$results = [];

// 查询单细胞数量
$sql = "SELECT COUNT(*) as count FROM cells";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $results['cellNum'] = $row['count'];
} else {
    $results['cellNum'] = 0;
}

// 查询类别数量
$sql = "SELECT COUNT(DISTINCT cluster_label) as category_count FROM cells";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $results['clusterNum'] = $row['category_count'];
} else {
    $results['clusterNum'] = 0;
}

// 查询基因数量
$sql = "SELECT COUNT(*) as count FROM genes";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $results['geneNum'] = $row['count'];
} else {
    $results['geneNum'] = 0;
}


// 查询基因数量
$sql = "SELECT COUNT(*) as count FROM spatial_cells";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $results['spatialNum'] = $row['count'];
} else {
    $results['spatialNum'] = 0;
}

// 关闭连接
$conn->close();

// 返回JSON结果
echo json_encode($results);
?>