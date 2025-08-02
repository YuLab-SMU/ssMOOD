<?php
include 'config.php';

$gene_sets = $_POST['gene_sets'] ?? '';
$datasetId = $_POST['id'] ?? '';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    file_put_contents("../log/redis_error_log.txt", "Connection failed: " . $conn->connect_error . PHP_EOL, FILE_APPEND);
    exit;
}

// 获取 significant_genes（根据你的业务逻辑）
// 假设 POST 中的 genes 是 JSON 数组
$significant_genes = json_decode($_POST['genes'] ?? '[]', true);

if (strpos($datasetId, 'study') !== false) {
    // 解析study部分
    $study = explode('.', $datasetId)[0];
    $sql = "SELECT gene_id FROM genes WHERE study_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $study);
} else {
    // 获取背景基因
    $sql = "SELECT g.gene_id FROM genes g JOIN datasets d ON g.study_id = d.study WHERE d.dataset_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $datasetId);
}
$stmt->execute();
$result = $stmt->get_result();
$background_genes = [];
while ($row = $result->fetch_assoc()) {
    $background_genes[] = $row['gene_id'];
}
$stmt->close();
$conn->close();

// 参数校验
if (empty($significant_genes) || empty($gene_sets) || empty($background_genes)) {
    echo "Error: Missing genes, gene_sets or background.";
    exit();
}

// 构建JSON数据
$data = json_encode([
    'significant_genes' => $significant_genes,
    'background_genes' => $background_genes,
    'gene_sets' => $gene_sets
]);

// 运行 Python
$command = "python3 enrichment.py";
$descriptorspec = [
    0 => ["pipe", "r"],
    1 => ["pipe", "w"],
    2 => ["pipe", "w"]
];
$process = proc_open($command, $descriptorspec, $pipes);
if (is_resource($process)) {
    fwrite($pipes[0], $data);
    fclose($pipes[0]);

    $output = stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    $errors = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    proc_close($process);

    if ($errors) {
        echo "错误: $errors";
    } else {
        echo $output;
    }
} else {
    echo "无法执行命令";
}
?>
