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
$cell_cluster = $_GET['cluster'] ?? '';

if (empty($datasetId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid dataset ID']);
    exit;
}

$sql = "SELECT bin_data
FROM deg_bin
WHERE dataset_id = ? and group_type = ? and deg_group='cluster'
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    error_log("Prepare statement failed: " . $conn->error);
    echo json_encode(['error' => 'Prepare statement failed']);
    exit;
}

$stmt->bind_param("ss", $datasetId,$cell_cluster);
$stmt->execute();
$result = $stmt->get_result();

// 检查是否有结果
if ($result->num_rows > 0) {
    // 获取第一行数据
    $row = $result->fetch_assoc();
    
    // 设置适当的 HTTP 头
    header('Content-Type: application/octet-stream');
    
    // 清除缓冲区并关闭输出缓冲
    ob_clean();
    flush();
    
    // 输出二进制数据
    echo $row['bin_data'];
} else {
    echo 'error';
}

$stmt->close();
?>