<?php
ini_set('memory_limit', '2048M');

include 'config.php';
// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed: " . $conn->connect_error;
    exit;
}

// 定义batch值
$studyId = $_GET['id'] ?? '';
if (empty($studyId)) {
    http_response_code(400);
    echo "Invalid or missing 'id' parameter.";
    exit;
}
// 查询study表获取datasets字段
$sql = "SELECT datasets FROM study WHERE study_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studyId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$umap_data = array();
$cluster_labels = array();

if ($row) {
    // 获取datasets字段并按逗号分割
    $datasets = explode(",", $row['datasets']);

    // 遍历每个dataset_id并查询cells表
    foreach ($datasets as $dataset_id) {
        $dataset_id = trim($dataset_id); // 去除可能的空格
        $sql = "SELECT c.spatial_cell_id, c.x_coordinate, c.y_coordinate, c.cluster_label2
        FROM spatial_cells c
        WHERE c.dataset_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $dataset_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // 将数据存入数组
            while ($row = $result->fetch_assoc()) {
                $umap_data[$dataset_id][] = array(
                    'i' => $row['spatial_cell_id'],
                    'x' => $row['x_coordinate'],
                    'y' => $row['y_coordinate'],
                    'c' => $row['cluster_label2'],
                );
                $cluster_labels[$row['cluster_label2']] = true; // 收集唯一的cluster_label
            }
        } else {
            echo "0 results";
            $conn->close();
            exit;
        }
    }
} else {
    echo "No datasets found for study ID: " . $studyId;
    $conn->close();
    exit;
}

// 将分类信息转换为数组
$cluster_labels = array_keys($cluster_labels);

$response = json_encode(['coordinate_data' => $umap_data, 'cluster_labels' => $cluster_labels]);

$compressed = zlib_encode($response, ZLIB_ENCODING_GZIP);
// 返回 JSON 数据
header('Content-Type: application/octet-stream');
    // 清除缓冲区并关闭输出缓冲
ob_clean();
flush();
echo $compressed;

//内存统计
//$peakMemory = memory_get_peak_usage();
// 将峰值内存使用情况写入日志文件
//$logEntry = date("Y-m-d H:i:s") . "[". __FILE__ ."] - 峰值内存使用量：" . $peakMemory/1024 . " KB\n";
//file_put_contents($logFile, $logEntry, FILE_APPEND);
?>
