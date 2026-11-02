<?php
include 'config.php';

//------------------------------------------------------------------------------
// Request guards:
// 1. Cross-origin calls are limited to the ssMOOD front end
// 2. POST only; the messages payload is validated for shape and size
// 3. Per-IP rate limiting
//------------------------------------------------------------------------------
$allowedOrigins = [
    'https://ssmood.genomics.cn',
    'http://ssmood.genomics.cn',
    'http://172.16.165.245',
    'http://localhost:8787',
];
$rateLimit = 30;         // max requests per IP within the window
$rateWindow = 600;       // window length in seconds
$maxMessages = 40;       // max messages per request
$maxTotalBytes = 120000; // max total bytes of all message contents per request

function proxy_error($code, $msg) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['error' => $msg]);
    exit;
}

// CORS: echo back allow-listed origins only (overrides the header set in config.php)
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

// Answer CORS preflight (OPTIONS) requests immediately
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    proxy_error(405, 'Method not allowed');
}

// Per-IP rate limit, counted in a temp file (no redis/apcu needed)
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

// Read the request body and keep only role/content for each message
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

// Create the cURL request
$ch = curl_init($url);

// cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 90);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $openaiApiKey,
]);

// Build the upstream request; model and parameters are fixed server-side
$requestBody = json_encode([
    'model' => $model, // configured in config.php
    'messages' => $messages, // chat history from the client (sanitized)
    'temperature' => 0.7,
    'max_tokens' => 1000
]);

// Attach the request body
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);

// Send the request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close the cURL handle
curl_close($ch);

// Relay the response
if ($httpCode == 200) {
    // On success, pass the upstream response through unchanged
    echo $response;
} else {
    // On failure, return a generic error without upstream details
    proxy_error($httpCode ?: 502, 'Error connecting to OpenAI API');
}
?>
