<?php
include 'config.php';

// 创建数据库连接
$conn = new mysqli($host, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// 获取study_id
$studyId = $_GET['id'] ?? '';

// 查询study表获取datasets字段
$sql = "SELECT datasets FROM study WHERE study_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studyId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode([]);
    $stmt->close();
    $conn->close();
    exit;
}

// 获取datasets字段并按逗号分割
$datasets = explode(",", $row['datasets']);

// 构造IN子句
$datasetInClause = implode("','", array_map('trim', $datasets));

// 查询各个细胞分类的数量
$query = "
    SELECT cluster_label2, COUNT(*) as cell_count
    FROM cells
    WHERE dataset_id IN ('$datasetInClause')
    GROUP BY cluster_label2
";

// 执行查询
$result = $conn->query($query);

// 初始化结果数组
$resultArray = [];

// 获取查询结果
while ($row = $result->fetch_assoc()) {
    $resultArray[$row['cluster_label2']] = (int) $row['cell_count'];
}

// 关闭数据库连接
$conn->close();

// 返回JSON格式的结果
echo json_encode($resultArray);
?>