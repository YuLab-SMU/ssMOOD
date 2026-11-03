<?php
include 'config.php';
// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Write database connection errors to the log file
    file_put_contents("../log/redis_error_log.txt", "Connection failed: " . $conn->connect_error . PHP_EOL, FILE_APPEND);
    exit;
}

// Define batch value
$datasetId = $_GET['id'] ?? '';

$sql = "SELECT c.cell_id, c.umap1, c.umap2, c.cluster_label2
        FROM spatial_cells c
        WHERE c.dataset_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $datasetId);
$stmt->execute();
$result = $stmt->get_result();

// Create arrays to store UMAP data and category info
$umap_data = array();
$cluster_labels = array();

if ($result->num_rows > 0) {
    // Store data in the array
    while ($row = $result->fetch_assoc()) {
        $umap_data[] = array(
            'i' => $row['cell_id'],
            'u1' => $row['umap1'],
            'u2' => $row['umap2'],
            'c' => $row['cluster_label2'],
        );
        $cluster_labels[$row['cluster_label2']] = true; // Collect unique cluster_label values
    }
} else {
    echo "0 results";
    $conn->close();
    exit;
}

$conn->close();

// Convert category info to an array
$cluster_labels = array_keys($cluster_labels);

// Convert data and category info to JSON
$response = json_encode(['umap_data' => $umap_data, 'cluster_labels' => $cluster_labels]);

$compressed = zlib_encode($response, ZLIB_ENCODING_GZIP);
// Return JSON data
header('Content-Type: application/json');
echo $compressed;
?>