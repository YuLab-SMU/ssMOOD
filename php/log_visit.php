<?php

include 'config.php';
header('Content-Type: application/json');
date_default_timezone_set('Asia/Shanghai');

// ------------------ 配置 -------------------
$dsn = "mysql:host=localhost;dbname=ssmood_log;charset=utf8mb4";
$db = new PDO($dsn, $username, $password);

// ------------------ 工具函数 -------------------

function getMaskedIP() {
    $ip = $_SERVER['REMOTE_ADDR'];

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // 使用第一个非私有 IP
        foreach (explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']) as $forwarded) {
            $forwarded = trim($forwarded);
            if (filter_var($forwarded, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $ip = $forwarded;
                break;
            }
        }
    }

    // 脱敏处理
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $parts = explode('.', $ip);
        return "{$parts[0]}.{$parts[1]}.{$parts[2]}.0";
    } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        return substr($ip, 0, strrpos($ip, ':')) . '::';
    }

    return 'Unknown';
}



function parseUserAgent($ua) {
    $browser = 'Unknown';
    $device = 'Desktop';
    $os = 'Unknown';

    // 浏览器判断
    if (preg_match('/Edge/i', $ua)) $browser = 'Edge';
    elseif (preg_match('/Chrome/i', $ua)) $browser = 'Chrome';
    elseif (preg_match('/Firefox/i', $ua)) $browser = 'Firefox';
    elseif (preg_match('/Safari/i', $ua)) $browser = 'Safari';
    elseif (preg_match('/MSIE|Trident/i', $ua)) $browser = 'IE';

    // 设备判断
    if (preg_match('/Mobile|iPhone|Android/i', $ua)) $device = 'Mobile';

    // 操作系统判断（顺序非常关键）
    if (preg_match('/HarmonyOS/i', $ua)) {
        $os = 'HarmonyOS';
    } elseif (preg_match('/iPhone|iPad/i', $ua)) {
        $os = 'iOS';
    } elseif (preg_match('/Android/i', $ua)) {
        $os = 'Android';
    } elseif (preg_match('/Windows NT 10.0/i', $ua)) {
        $os = 'Windows 10 / 11'; // 无法进一步区分
    } elseif (preg_match('/Windows NT 6.1/i', $ua)) {
        $os = 'Windows 7';
    } elseif (preg_match('/Mac OS X/i', $ua)) {
        $os = 'macOS';
    } elseif (preg_match('/Linux/i', $ua)) {
        $os = 'Linux';
    }

    return [$browser, $device, $os];
}



// 获取地理信息（使用 ip-api.com 免费服务）
// ⚠️更正部分地名的名称
function getGeoInfo($ip) {
    $json = @file_get_contents("http://ip-api.com/json/{$ip}?lang=en");
    $data = json_decode($json, true);
    
    $country = $data['country'] ?? 'Unknown';

    if ($country === 'Taiwan') {
        $country = "Taiwan, China";
    } elseif ($country === 'Hong Kong') {
        $country = "Hong Kong, China";
    } elseif ($country === 'Macao') {
        $country = "Macao, China";
    }

    return [
        'country' => $country,
        'region' => $data['regionName'] ?? 'Unknown',
        'lat' => isset($data['lat']) ? floatval($data['lat']) : null,
        'lon' => isset($data['lon']) ? floatval($data['lon']) : null,
    ];
}


// ------------------ 主逻辑 -------------------
$ip = getMaskedIP();
$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
[$browser, $device, $os] = parseUserAgent($ua);
$geo = getGeoInfo($ip);

// 插入数据库
$stmt = $db->prepare("
  INSERT INTO visit_logs (ip, country, region, browser, device, os, lat, lon)
  VALUES (:ip, :country, :region, :browser, :device, :os, :lat, :lon)
");

$stmt->execute([
    ':ip' => $ip,
    ':country' => $geo['country'],
    ':region' => $geo['region'],
    ':browser' => $browser,
    ':device' => $device,
    ':os' => $os,
    ':lat' => $geo['lat'],
    ':lon' => $geo['lon'],
]);

echo json_encode(['status' => 'ok']);
