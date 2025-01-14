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

$sql = "SELECT gene_id
FROM genes
ORDER BY gene_id ASC;";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $datasetId);
$stmt->execute();
$result = $stmt->get_result();

// 获取查询结果并存储在数组中
$genes = array();
while ($row = $result->fetch_assoc()) {
    $genes[] = $row['gene_id'];
}

// 关闭数据库连接
$stmt->close();
$conn->close();

// 将数组编码为JSON格式
header('Content-Type: application/json');
echo json_encode(array('genes' => $genes));
?>