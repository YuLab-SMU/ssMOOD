<?php
include 'config.php';

//------------------------------------------------------------------------------
// 安全加固：
// 1. 仅允许 ssMOOD 自己的页面跨域调用（原来是 *，任何网站都能借用我们的 API key）
// 2. 仅接受 POST，并校验 messages 的结构和长度
// 3. 按 IP 限流，避免被脚本刷接口
//------------------------------------------------------------------------------
$allowedOrigins = [
    'https://ssmood.genomics.cn',
    'http://ssmood.genomics.cn',
    'http://172.16.165.245',
    'http://localhost:8787',
];
$rateLimit = 30;         // 每个 IP 在时间窗口内的最大请求数
$rateWindow = 600;       // 时间窗口（秒）
$maxMessages = 40;       // 单次请求最多消息条数
$maxTotalBytes = 120000; // 单次请求所有消息的总字节数上限（约 4 万汉字）

function proxy_error($code, $msg) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['error' => $msg]);
    exit;
}

// 跨域：只回显白名单内的 Origin（会覆盖 config.php 中的 *）
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '') {
    if (!in_array($origin, $allowedOrigins, true)) {
        proxy_error(403, 'Origin not allowed');
    }
    header("Access-Control-Allow-Origin: " . $origin);
    header("Vary: Origin");
} else {
    header_remove("Access-Control-Allow-Origin");
}
header_remove("Access-Control-Allow-Credentials");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

// 检查是否为预检请求（OPTIONS 请求），如果是则直接返回
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    proxy_error(405, 'Method not allowed');
}

// 按 IP 限流（文件计数，不依赖 redis/apcu）
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateFile = sys_get_temp_dir() . '/ssmood_ai_rate_' . md5($ip);
$fp = fopen($rateFile, 'c+');
if ($fp && flock($fp, LOCK_EX)) {
    $now = time();
    $hits = json_decode(stream_get_contents($fp), true);
    if (!is_array($hits)) $hits = [];
    $hits = array_values(array_filter($hits, function ($t) use ($now, $rateWindow) {
        return $t > $now - $rateWindow;
    }));
    if (count($hits) >= $rateLimit) {
        flock($fp, LOCK_UN);
        fclose($fp);
        header('Retry-After: ' . $rateWindow);
        proxy_error(429, 'Too many requests, please try again later');
    }
    $hits[] = $now;
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($hits));
    flock($fp, LOCK_UN);
    fclose($fp);
}

// 获取前端传来的数据，并只保留 role/content 两个字段
$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data) || !isset($data['messages']) || !is_array($data['messages'])) {
    proxy_error(400, 'Invalid request body');
}
if (count($data['messages']) === 0 || count($data['messages']) > $maxMessages) {
    proxy_error(400, 'Too many messages');
}
$messages = [];
$totalBytes = 0;
foreach ($data['messages'] as $m) {
    if (!is_array($m) || !isset($m['role'], $m['content']) || !is_string($m['content'])
        || !in_array($m['role'], ['system', 'user', 'assistant'], true)) {
        proxy_error(400, 'Invalid message format');
    }
    $totalBytes += strlen($m['content']);
    $messages[] = ['role' => $m['role'], 'content' => $m['content']];
}
if ($totalBytes > $maxTotalBytes) {
    proxy_error(413, 'Messages too long');
}

// 创建 cURL 请求
$ch = curl_init($url);

// 设置 cURL 选项
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 90);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $openaiApiKey,
]);

// 构造请求体（模型与参数固定在服务端，前端不能修改）
$requestBody = json_encode([
    'model' => $model, // 或 'gpt-3.5-turbo'
    'messages' => $messages, // 前端传递的聊天内容（已过滤）
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
    // 如果请求失败，返回错误信息（不透传上游错误细节）
    proxy_error($httpCode ?: 502, 'Error connecting to OpenAI API');
}
?>
