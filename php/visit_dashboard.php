<?php
include 'config.php';
header('Content-Type: application/json');
date_default_timezone_set('Asia/Shanghai');

// Database configuration
$db = new PDO("mysql:host=localhost;dbname=ssmood_log;charset=utf8mb4", $username, $password);

// Time range
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));
$last7Start = date('Y-m-d', strtotime('-6 days'));
$last7End = $today;
$prev7Start = date('Y-m-d', strtotime('-13 days'));
$prev7End = date('Y-m-d', strtotime('-7 days'));
$last30Start = date('Y-m-d', strtotime('-29 days'));
$prev30Start = date('Y-m-d', strtotime('-59 days'));
$prev30End = date('Y-m-d', strtotime('-30 days'));

// Query helper
function getVisitCount($pdo, $startDate, $endDate) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM visit_logs WHERE DATE(visit_time) BETWEEN :start AND :end");
    $stmt->execute([':start' => $startDate, ':end' => $endDate]);
    return (int)$stmt->fetchColumn();
}

// Statistics
$todayCount = getVisitCount($db, $today, $today);
$yesterdayCount = getVisitCount($db, $yesterday, $yesterday);
$last7Count = getVisitCount($db, $last7Start, $last7End);
$prev7Count = getVisitCount($db, $prev7Start, $prev7End);
$last30Count = getVisitCount($db, $last30Start, $today);
$prev30Count = getVisitCount($db, $prev30Start, $prev30End);
$totalCount = getVisitCount($db, '2020-01-01', $today);

// Recent visitors list
$stmt = $db->query("SELECT browser, os, visit_time, country, region FROM visit_logs ORDER BY visit_time DESC LIMIT 100");
$visitors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Map marker data
$stmt = $db->query("SELECT lat, lon, country, region FROM visit_logs WHERE lat IS NOT NULL AND lon IS NOT NULL LIMIT 1000");
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON
echo json_encode([
    'stats' => [
        'today' => $todayCount,
        'yesterday' => $yesterdayCount,
        'last7' => $last7Count,
        'prev7' => $prev7Count,
        'last30' => $last30Count,
        'prev30' => $prev30Count,
        'total' => $totalCount
    ],
    'visitors' => array_map(function ($v) {
        return [
            'country' => $v['country'],
            'region' => $v['region'],
            'browser' => $v['browser'],
            'os' => $v['os'],
            'time' => $v['visit_time']
        ];
    }, $visitors),
    'mapPoints' => array_map(function ($p) {
        return [
            'lat' => (float)$p['lat'],
            'lon' => (float)$p['lon'],
            'label' => "{$p['region']}, {$p['country']}"
        ];
    }, $locations)
]);
