<?php
include 'config.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed']));
}


// 查询 mouse 和 human 的数量
$query = "SELECT species, COUNT(*) as count FROM datasets WHERE species IN ('mouse', 'human') GROUP BY species";
$result = $conn->query($query);

// 初始化结果数组
$resultArray = [
    'mouse' => 0,
    'human' => 0
];

// 获取查询结果
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $resultArray[$row['species']] = $row['count'];
    }
    $result->free();
}

// 关闭数据库连接
$conn->close();

echo json_encode($resultArray);

?>
