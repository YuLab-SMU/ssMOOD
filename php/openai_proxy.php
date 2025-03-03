<?php
include 'config.php';
// 设置 CORS 头，允许来自任何来源的请求（根据需要修改）
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");

// 检查是否为预检请求（OPTIONS 请求），如果是则直接返回
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 获取前端传来的数据
$data = json_decode(file_get_contents('php://input'), true);



// 创建 cURL 请求
$ch = curl_init($url);

// 设置 cURL 选项
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $openaiApiKey,
]);

// 构造请求体
$requestBody = json_encode([
    'model' => $model, // 或 'gpt-3.5-turbo'
    'messages' => $data['messages'], // 前端传递的聊天内容
    'temperature' => 0.7,
    'max_tokens' => 1000
]);

// 设置 cURL 请求体
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);

// 执行 cURL 请求
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// 关闭 cURL 会话
curl_close($ch);

// 处理响应
if ($httpCode == 200) {
    // 如果请求成功，返回 OpenAI 响应
    echo $response;
} else {
    // 如果请求失败，返回错误信息
    http_response_code($httpCode);
    echo json_encode(['error' => 'Error connecting to OpenAI API']);
}
?>
