<?php
ini_set('memory_limit', '512M');
// 数据库连接信息
include 'config.php';

// 数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    // 将数据库连接错误写入日志文件
    file_put_contents("../log/redis_error_log.txt", "Connection failed: " . $conn->connect_error . PHP_EOL, FILE_APPEND);
    exit;
}

// 定义 4 张表的查询条件，统一字段命名为 umap1 和 umap2
$queries = [
    "SELECT c.cell_id AS cell_id, c.umap1 AS umap1, c.umap2 AS umap2, c.cluster_label2 FROM cells c WHERE dataset_id = 'SC0023_S_Stri'",
    "SELECT c.spatial_cell_id AS cell_id, c.x_coordinate AS umap1, c.y_coordinate AS umap2, c.cluster_label2 FROM spatial_cells c WHERE dataset_id = 'ST0049_S_mPFC'",
    "SELECT c.cell_id AS cell_id, c.umap1 AS umap1, c.umap2 AS umap2, c.cluster_label2 FROM cells c WHERE dataset_id = 'SC0039_C_BA11'",
    "SELECT c.spatial_cell_id AS cell_id, c.x_coordinate AS umap1, c.y_coordinate AS umap2, c.cluster_label2 FROM spatial_cells c WHERE dataset_id = 'ST0055_C_BA46'"
];

// 初始化数组存储 4 张表的数据
$umap_datasets = [];

foreach ($queries as $index => $sql) {
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $umap_data = [];
        $cluster_labels = [];

        while ($row = $result->fetch_assoc()) {
            $umap_data[] = [
                'i' => $row['cell_id'],
                'u1' => $row['umap1'],
                'u2' => $row['umap2'],
                'c' => $row['cluster_label2']
            ];

            if (!in_array($row['cluster_label2'], $cluster_labels)) {
                $cluster_labels[] = $row['cluster_label2'];
            }
        }

        // 将当前表的数据和分类信息存入数组
        $umap_datasets[$index] = [
            'umap' => $umap_data,
            'cluster' => $cluster_labels
        ];
    } else {
        echo "0 results for dataset $index";
    }
}

$conn->close();

// 将所有数据转换为 JSON 格式
$response = json_encode($umap_datasets);
$compressed = zlib_encode($response, ZLIB_ENCODING_GZIP);

    // 设置适当的 HTTP 头
header('Content-Type: application/octet-stream');
    // 清除缓冲区并关闭输出缓冲
ob_clean();
flush();
echo $compressed;
//echo $response;
?>