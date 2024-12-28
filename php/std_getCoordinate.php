<?php
ini_set('memory_limit', '512M');

$servername = "localhost";
$username = "scast";
$password = "20030609";
$dbname = "ssmood";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo "Database connection failed: " . $conn->connect_error;
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
    echo "SQL Prepare failed: " . $conn->error;
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
header('Content-Type: application/json');
echo $compressed;
?>
