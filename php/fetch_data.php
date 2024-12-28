<?php
// fetch_data.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sample_id = $_POST['sample_id'];

    // 创建数据库连接
    $host = 'localhost';
    $db = 'scast';
    $user = 'scast';
    $pass = '20030609';
    $charset = 'utf8';
    

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);

        // 查询数据
        $stmt = $pdo->prepare("
            SELECT 
                s.sample_id,
                s.sample_name,
                s.organism,
                s.tissue,
                c.cell_id,
                c.cell_type,
                c.batch,
                c.condition,
                cl.cluster_id,
                cl.cluster_label
            FROM 
                samples s
            JOIN 
                cells c ON s.sample_id = c.sample_id
            LEFT JOIN 
                clusters cl ON c.cell_id = cl.cell_id
            WHERE 
                s.sample_id = :sample_id
        ");
        $stmt->execute(['sample_id' => $sample_id]);
        $rows = $stmt->fetchAll();

        if ($rows) {
            echo '<table class="table table-bordered">';
            echo '<thead><tr><th>字段</th><th>值</th></tr></thead>';
            echo '<tbody>';

            foreach ($rows as $row) {
                foreach ($row as $key => $value) {
                    echo '<tr><td>' . htmlspecialchars($key) . '</td><td>' . htmlspecialchars($value) . '</td></tr>';
                }
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '没有找到数据。';
        }

    } catch (\PDOException $e) {
        echo '查询失败: ' . $e->getMessage();
    }
}
?>
