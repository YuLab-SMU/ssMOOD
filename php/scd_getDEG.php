<?php
// 数据库连接信息
$servername = "localhost";
$username = "scast";
$password = "20030609";
$dbname = "scast";

// 数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
$date = new DateTime();
if ($conn->connect_error) {
    // 将数据库连接错误写入日志文件
    file_put_contents("log.txt", "[".$date->format('Y-m-d H:i:s')."][scd_different.php]Connection failed: " . $conn->connect_error . PHP_EOL, FILE_APPEND);
    exit;
}

// 定义batch值
$datasetId = $_GET['id'] ?? '';

$sql = "SELECT d.gene_id, d.cluster_label, d.p_value, d.fold_change
FROM differential_expression d
WHERE d.gene_id IN (
    SELECT DISTINCT e.gene_id
    FROM expression e
    JOIN cells c1 ON e.cell_id = c1.cell_id
    WHERE c1.batch = ?
)
ORDER BY d.gene_id ASC;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$datasetId);
$stmt->execute();
$result = $stmt->get_result();

// 创建数组来存储UMAP数据
$umap_data = array();

if ($result->num_rows > 0) {
    // 将数据存入数组
    while ($row = $result->fetch_assoc()) {
        $umap_data[] = array(
            'i' => $row['gene_id'],
            'c' => $row['cluster_label'],
            'p' => $row['p_value'],
            'f' => $row['fold_change']
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