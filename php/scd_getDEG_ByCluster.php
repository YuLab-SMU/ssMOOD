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

$sql = "SELECT gene_id, p_value, fold_change ,pct1,pct2,p_value_adj
FROM sc_deg
WHERE dataset_id = ? and cluster_label = ?
ORDER BY gene_id ASC;
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

// 创建数组来存储 UMAP 数据
$deg_data = array();

if ($result->num_rows > 0) {
    // 将数据存入数组
    while ($row = $result->fetch_assoc()) {
        $deg_data[] = array(
            'i' => $row['gene_id'],
            'a' => $row['p_value_adj'],
            'f' => $row['fold_change'],
            't1' => $row['pct1'],
            't2' => $row['pct2']
        );
    }
} else {
    echo json_encode(['error' => '0 results']);
    $conn->close();
    exit;
}

$conn->close();

// 将数据转换为 JSON 格式
$response = json_encode(['data' => $deg_data]);

$compressed = zlib_encode($response, ZLIB_ENCODING_GZIP);
// 返回 JSON 数据
    // 设置适当的 HTTP 头
header('Content-Type: application/octet-stream');
    // 清除缓冲区并关闭输出缓冲
ob_clean();
flush();
echo $compressed;
?>