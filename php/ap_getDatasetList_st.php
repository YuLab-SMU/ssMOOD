<?php
include 'config.php';
// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// SQL 查询
$sql = "SELECT dataset_id FROM datasets WHERE scorst = 'st'"; // 假设您的数据表名为 datasets，并且包含 id 和 name 字段

// 执行查询
$result = $conn->query($sql);

// 初始化数组来存储结果
$datasets = array();

// 检查结果
if ($result) {
    // 遍历结果集
    $i = 0 ;
    while ($row = $result->fetch_assoc()) {
        // 将每行数据添加到数组中，确保键名为 'id' 和 'name'
        $datasets[] = array(
            "id" => $i,
            "name" => $row['dataset_id']
        );
        $i++;
    }
    
    // 释放结果集
    $result->free();
    
    // 将数组转换为 JSON 格式，并封装在包含 'datasets' 键的外层数组中
    echo json_encode(array("datasets" => $datasets));
} else {
    echo "错误: " . $conn->error;
}

// 关闭连接
$conn->close();
?>