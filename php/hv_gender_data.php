<?php
header('Content-Type: application/json');

$data = [
    "male" => 60,   // 男性人数或比例
    "female" => 40  // 女性人数或比例
];

echo json_encode($data);
?>
