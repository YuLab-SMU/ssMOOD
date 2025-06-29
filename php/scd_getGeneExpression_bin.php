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

// 获取 GET 参数
$datasetId = $_GET['id'] ?? '';
$gene = $_GET['gene'] ?? '';

// 检查参数是否为空
if (empty($datasetId) || empty($gene)) {
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

// 准备 SQL 查询
$sql = "SELECT bin_data FROM sc_expression WHERE dataset_id = ? AND gene_id = ?";

// 准备语句
$stmt = $conn->prepare($sql);

// 绑定参数
$stmt->bind_param("ss", $datasetId, $gene);

// 执行查询
$stmt->execute();

// 获取结果
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

// 关闭语句
$stmt->close();

// 关闭连接
$conn->close();
?>