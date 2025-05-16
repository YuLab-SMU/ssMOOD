<?php
include 'config.php';
// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    // 将数据库连接错误写入日志文件
    file_put_contents("../log/redis_error_log.txt", "Connection failed: " . $conn->connect_error . PHP_EOL, FILE_APPEND);
    exit;
}

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


$sql = "SELECT cell_id, normalized_count FROM $expTable WHERE dataset_id = ? AND gene_id = ?";
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
            'i' => $row['cell_id'],
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