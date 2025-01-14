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

// 定义studyId值
$studyId = $_GET['studyid'] ?? '';
// 分割字符串
$parts = explode('.', $studyId);

// 提取需要的部分
$study = $parts[0] ?? ''; // 'study1'
$type = $parts[1] ?? ''; // 'area'
$param = $parts[2] ?? ''; // 'Stri'

$allowedTypes = ['area', 'conditions']; // 根据实际需要添加更多类型
if (!in_array($type, $allowedTypes)) {
    echo "Invalid type";
    exit;
}

// 根据study和area查询dataset_id
$datasetIds = [];
$sql = "SELECT dataset_id FROM datasets WHERE study = ? AND $type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $study, $param);
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

$gene = $_GET['gene'] ?? '';

// 创建数组来存储UMAP数据
$umap_data = [];

foreach ($datasetIds as $datasetId) {
    // 查询每个dataset_id的exp_table
    $sql = "SELECT exp_table FROM datasets WHERE dataset_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $datasetId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $expTable = $row['exp_table'];

    // 根据exp_table查询cell_id和normalized_count
    $sql = "SELECT cell_id, normalized_count FROM $expTable WHERE dataset_id = ? AND gene_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $datasetId, $gene);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // 将数据存入数组
        while ($row = $result->fetch_assoc()) {
            $umap_data[] = array(
                'i' => $row['cell_id'],
                'nc' => $row['normalized_count']
            );
        }
    }
}

$conn->close();

// 将数据转换为JSON格式
$response = json_encode(['data' => $umap_data]);

// 返回 JSON 数据
header('Content-Type: application/json');
echo $response;
?>