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
$studyId = $_GET['id'] ?? '';
$gene = $_GET['gene'] ?? '';

// 检查参数是否为空
if (empty($studyId) || empty($gene)) {
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

// 查询 study 表获取 datasets 字段
$sql = "SELECT datasets FROM study WHERE study_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studyId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    // 获取 datasets 字段并按逗号分割
    $datasets = explode(",", $row['datasets']);
    $expressionData = []; // 初始化数组

    // 遍历每个 dataset_id 并查询 sc_expression 表
    foreach ($datasets as $dataset_id) {
        $dataset_id = trim($dataset_id); // 去除可能的空格
        $sql = "SELECT bin_data FROM sc_expression WHERE dataset_id = ? AND gene_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $dataset_id, $gene);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $binDataRow = $result->fetch_assoc();
            $expressionData[] = $binDataRow['bin_data']; // 将 bin_data 添加到数组
        } else {
        }
    }

    // 设置适当的 HTTP 头
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="expression_data.bin"');

    // 清除缓冲区并关闭输出缓冲
    ob_clean();
    flush();

    // 输出二进制数据
    foreach ($expressionData as $binData) {
        echo pack("N", strlen($binData)); // 输出数据长度（4字节）
        echo $binData; // 输出二进制数据
        //file_put_contents($logFile, strlen($binData).'\n', FILE_APPEND);
    }
} else {
    echo json_encode(['error' => "No datasets found for study ID: $studyId"]);
}

// 关闭语句
$stmt->close();

// 关闭连接
$conn->close();
?>