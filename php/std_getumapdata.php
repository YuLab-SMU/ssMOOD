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

$sql = "SELECT c.cell_id, c.umap1, c.umap2, c.cluster_label2
        FROM spatial_cells c
        WHERE c.dataset_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $datasetId);
$stmt->execute();
$result = $stmt->get_result();

// 创建数组来存储UMAP数据和分类信息
$umap_data = array();
$cluster_labels = array();

if ($result->num_rows > 0) {
    // 将数据存入数组
    while ($row = $result->fetch_assoc()) {
        $umap_data[] = array(
            'i' => $row['cell_id'],
            'u1' => $row['umap1'],
            'u2' => $row['umap2'],
            'c' => $row['cluster_label2'],
        );
        $cluster_labels[$row['cluster_label2']] = true; // 收集唯一的cluster_label
    }
} else {
    echo "0 results";
    $conn->close();
    exit;
}

$conn->close();

// 将分类信息转换为数组
$cluster_labels = array_keys($cluster_labels);

// 将数据和分类信息转换为JSON格式
$response = json_encode(['umap_data' => $umap_data, 'cluster_labels' => $cluster_labels]);

$compressed = zlib_encode($response, ZLIB_ENCODING_GZIP);
// 返回 JSON 数据
header('Content-Type: application/json');
echo $compressed;
?>