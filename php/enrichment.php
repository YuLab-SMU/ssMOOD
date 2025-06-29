<?php

include 'config.php';

$genes = $_POST['genes'] ?? '';
$gene_sets = $_POST['gene_sets'] ?? '';

//'Mouse_GO_2024.gmt'

if (empty($genes) || empty($gene_sets)) {
    // 参数为空，输出错误消息并结束脚本
    echo "Error: Both 'genes' and 'gene_sets' parameters are required.";
    exit(); // 或者使用 die();
}
// 构建JSON数据
$data = json_encode(['significant_genes' => $genes, 'gene_sets' => $gene_sets]);

// 构建命令行命令
$command = "python3 enrichment.py";
$descriptorspec = array(
    0 => array("pipe", "r"),  // 标准输入
    1 => array("pipe", "w"),  // 标准输出
    2 => array("pipe", "w")   // 标准错误
);

// 执行命令
$process = proc_open($command, $descriptorspec, $pipes);
if (is_resource($process)) {
    // 写入基因列表到标准输入
    fwrite($pipes[0], $data);
    fclose($pipes[0]);

    // 读取标准输出
    $output = stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    // 读取标准错误
    $errors = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    // 关闭进程
    proc_close($process);

    // 检查是否有错误
    if ($errors) {
        echo "错误: $errors";
    } else {
        // 解析并显示结果
        $results = json_decode($output, true);
        echo($output);
    }
} else {
    echo "无法执行命令";
}
?>