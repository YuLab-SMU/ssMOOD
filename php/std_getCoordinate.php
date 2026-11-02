<?php
ini_set('memory_limit', '512M');

include 'config.php';
// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$datasetId = $_GET['id'] ?? '';
if (empty($datasetId)) {
    http_response_code(400);
    echo "Invalid or missing 'id' parameter.";
    exit;
}

$sql = "SELECT c.spatial_cell_id, c.x_coordinate, c.y_coordinate, c.cluster_label2
        FROM spatial_cells c
        WHERE c.dataset_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    error_log("Query failed: " . $conn->error);
    echo json_encode(['error' => 'Query failed']);
    exit;
}

$stmt->bind_param("s", $datasetId);
$stmt->execute();
$result = $stmt->get_result();

$umap_data = array();
$cluster_labels = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $umap_data[] = array(
            'i' => $row['spatial_cell_id'],
            'x' => $row['x_coordinate'],
            'y' => $row['y_coordinate'],
            'c' => $row['cluster_label2'],
        );
        $cluster_labels[$row['cluster_label2']] = true;
    }
} else {
    echo "0 results";
    $conn->close();
    exit;
}

$conn->close();

$cluster_labels = array_keys($cluster_labels);
$response = json_encode(['coordinate_data' => $umap_data, 'cluster_labels' => $cluster_labels]);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    echo "JSON Error: " . json_last_error_msg();
    exit;
}

if (!function_exists('zlib_encode')) {
    http_response_code(500);
    echo "Zlib compression not available.";
    exit;
}

$compressed = zlib_encode($response, ZLIB_ENCODING_GZIP);
//header('Content-Type: application/json');
// Return JSON data
header('Content-Type: application/octet-stream');
    // Clear the buffer and turn off output buffering
ob_clean();
flush();
echo $compressed;

//Memory stats
//$peakMemory = memory_get_peak_usage();
// Write peak memory usage to the log file
//$logEntry = date("Y-m-d H:i:s") . "[". __FILE__ ."] - Peak memory usage: " . $peakMemory/1024 . " KB\n";
//file_put_contents($logFile, $logEntry, FILE_APPEND);
?>
