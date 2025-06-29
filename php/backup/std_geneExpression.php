<?php
ini_set('memory_limit', '512M');
include 'config.php';
// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);
// 定义batch值
$datasetId = $_GET['id'] ?? '';
$gene = $_GET['gene'] ?? '';

$sql = "SELECT exp_table FROM datasets WHERE dataset_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $datasetId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$expTable = $row['exp_table'];


$sql = "SELECT spatial_cell_id, normalized_count FROM $expTable WHERE dataset_id = ? AND gene_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $datasetId, $gene);
$stmt->execute();
$result = $stmt->get_result();

// 创建数组来存储UMAP数据
$umap_data = array();

if ($result->num_rows > 0) {
    // 将数据存入数组
    while ($row = $result->fetch_assoc()) {
        $umap_data[] = array(
            'i' => $row['spatial_cell_id'],
            'nc' => $row['normalized_count']
        );
    }
} else {
    echo "0 results";
    $conn->close();
    exit;
}

$conn->close();

// 将数据转换为JSON格式
$response = json_encode(['data' => $umap_data]);

// 返回 JSON 数据
header('Content-Type: application/json');
echo $response;
?>