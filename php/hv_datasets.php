<?php

include 'config.php';

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 定义查询条件
$queries = [
    ['species' => 'mouse', 'scorst' => 'sc'],
    ['species' => 'mouse', 'scorst' => 'st'],
    ['species' => 'human', 'scorst' => 'sc'],
    ['species' => 'human', 'scorst' => 'st'],
];

$results = [];

// 遍历查询条件并执行查询
foreach ($queries as $query) {
    $species = $conn->real_escape_string($query['species']);
    $scorst = $conn->real_escape_string($query['scorst']);

    $sql = "SELECT dataset_id, cells FROM datasets WHERE species = '$species' AND scorst = '$scorst'";
    $result = $conn->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        // 将数据存入数组
        while ($row = $result->fetch_assoc()) {
            $data[$row['dataset_id']] = $row['cells'];
        }
    }

    // 将结果存入最终数组
    $results[] = $data;
}

// 关闭连接
$conn->close();

// 将结果转换为 JSON 格式
$response = json_encode($results);

$compressed = zlib_encode($response, ZLIB_ENCODING_GZIP);

    // 设置适当的 HTTP 头
header('Content-Type: application/octet-stream');
    // 清除缓冲区并关闭输出缓冲
ob_clean();
flush();
echo $compressed;
?>