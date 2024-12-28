<?php
ini_set('memory_limit', '512M');
// 数据库连接信息
$servername = "127.0.0.1";
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

    // 查询UMAP数据和对应的cluster_label，随机选择1000条数据
    $sql = "SELECT c.cell_id, c.umap1, c.umap2, c.cluster_label2 
            FROM cells c
            ORDER BY RAND()";

    $result = $conn->query($sql);

    // 创建数组来存储UMAP数据和分类信息
    $umap_data = array();

    if ($result->num_rows > 0) {
        // 将数据存入数组
        while ($row = $result->fetch_assoc()) {
            $umap_data[] = array(
                'i' => $row['cell_id'],
                'u1' => $row['umap1'],
                'u2' => $row['umap2'],
                'c' => $row['cluster_label2'],
            );
            // 收集不重复的cluster_label
            if (!in_array($row['cluster_label2'], $cluster_labels)) {
                $cluster_labels[] = $row['cluster_label2'];
            }
        }
    } else {
        echo "0 results";
        $conn->close();
        exit;
    }

    $conn->close();

    // 将数据和分类信息转换为JSON格式
    $response = json_encode(['umap' => $umap_data, 'cluster' => $cluster_labels]);
    $compressed = zlib_encode($response, ZLIB_ENCODING_GZIP);

    // 返回 JSON 数据
    header('Content-Type: application/json');
    echo $compressed;
?>