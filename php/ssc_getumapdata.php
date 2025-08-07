<?php
include 'config.php';
ini_set('memory_limit', '512M');
// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    // 将数据库连接错误写入日志文件
    file_put_contents("../log/redis_error_log.txt", "Connection failed: " . $conn->connect_error . PHP_EOL, FILE_APPEND);
    exit;
}

// 定义batch值
$studyId = $_GET['id'] ?? '';

// 查询study表获取datasets字段
$sql = "SELECT datasets FROM study WHERE study_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studyId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// 初始化数组
$umap_data = array();
$cluster_labels = array();

if ($row) {
    // 获取datasets字段并按逗号分割
    $datasets = explode(",", $row['datasets']);

    // 遍历每个dataset_id并查询cells表
    foreach ($datasets as $dataset_id) {
        $dataset_id = trim($dataset_id); // 去除可能的空格
        $sql = "SELECT c.cell_id, c.umap1, c.umap2, c.cluster_label2
                FROM cells c
                WHERE c.dataset_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $dataset_id);
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
    }
} else {
    echo "No datasets found for study ID: " . $studyId;
    $conn->close();
    exit;
}

// 将分类信息转换为数组
$cluster_labels = array_keys($cluster_labels);

$response = json_encode(['umap_data' => $umap_data, 'cluster_labels' => $cluster_labels]);

$compressed = zlib_encode($response, ZLIB_ENCODING_GZIP);
// 返回 JSON 数据
header('Content-Type: application/octet-stream');
    // 清除缓冲区并关闭输出缓冲
ob_clean();
flush();
echo $compressed;
?>