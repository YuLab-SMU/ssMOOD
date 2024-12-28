<?php
// 数据库连接信息
$servername = "localhost";
$username = "scast";
$password = "20030609";
$dbname = "ssmood";

// 数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    // 将数据库连接错误写入日志文件
    file_put_contents("../log/redis_error_log.txt", "Connection failed: " . $conn->connect_error . PHP_EOL, FILE_APPEND);
    exit;
}

$studyId = $_GET['studyid'] ?? '';
// 分割字符串
$parts = explode('.', $studyId);

// 提取需要的部分
$study = $parts[0]; // 'study1'
$type = $parts[1]; // 'area'
$param = $parts[2]; // 'Stri'


$allowedTypes = ['area', 'conditions']; // 根据实际需要添加更多类型
if (!in_array($type, $allowedTypes)) {
    echo "Invalid type";
    exit;
}
// 根据study和area查询dataset_id
$datasetIds = array();
$sql = "SELECT dataset_id FROM datasets WHERE study = ? AND $type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $study,$param);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // 将dataset_id存入数组
    while ($row = $result->fetch_assoc()) {
        $datasetIds[] = $row['dataset_id'];
    }
} else {
    echo "0 results";
    $conn->close();
    exit;
}

// 使用dataset_id查询UMAP数据
$umap_data = array();
$cluster_labels = array();

$sql = "SELECT c.cell_id, c.umap1, c.umap2, c.cluster_label2
        FROM cells c
        WHERE c.dataset_id IN (" . implode(',', array_fill(0, count($datasetIds), '?')) . ")";

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($datasetIds)), ...$datasetIds);
$stmt->execute();
$result = $stmt->get_result();

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