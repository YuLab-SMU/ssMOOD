<?php
include 'config.php';
// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    http_response_code(500);
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// 定义 datasetId 值
$datasetId = $_GET['id'] ?? '';

if (empty($datasetId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid dataset ID']);
    exit;
}

$sql = "SELECT DISTINCT cluster_label
        FROM spatial_differential_expression
        WHERE dataset_id = ?;";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    error_log("Prepare statement failed: " . $conn->error);
    echo json_encode(['error' => 'Prepare statement failed']);
    exit;
}

$stmt->bind_param("s", $datasetId);
$stmt->execute();
$result = $stmt->get_result();

$cluster_labels = [];
while ($row = $result->fetch_assoc()) {
    $cluster_labels[] = $row['cluster_label'];
}
header('Content-Type: application/json');
// 将数组转换为 JSON 格式
echo json_encode($cluster_labels);

$conn->close();

?>