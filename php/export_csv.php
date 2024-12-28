<?php
// export_csv.php

// 数据库连接配置
    $host = 'localhost';
    $db = 'scast';
    $user = 'scast';
    $pass = '20030609';
    $charset = 'utf8';

// 连接数据库
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // 查询数据库中的所有数据
    $stmt = $pdo->query("SELECT * FROM cells");
    $data = $stmt->fetchAll();

    // 设置 CSV 文件的 HTTP 头信息
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data_export.csv');

    // 打开输出流
    $output = fopen('php://output', 'w');

    // 写入 CSV 文件的列名
    fputcsv($output, array('cell_id', 'sample_id', 'cell_type', 'batch', 'condition'));

    // 写入数据行
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    // 关闭输出流
    fclose($output);
    exit;

} catch (PDOException $e) {
    echo "数据库连接失败: " . $e->getMessage();
}
?>
