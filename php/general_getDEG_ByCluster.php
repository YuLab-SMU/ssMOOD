<?php
include 'config.php';

// Remove the Content-Type header set in config.php
header_remove('Content-Type');

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    header('Content-Type: application/json');
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Define datasetId value
$datasetId = $_GET['id'] ?? '';
$cell_cluster = $_GET['cluster'] ?? '';

if (empty($datasetId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid dataset ID']);
    exit;
}

$sql = "SELECT bin_data
FROM deg_bin
WHERE dataset_id = ? and group_type = ? and deg_group='cluster'
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    error_log("Prepare statement failed: " . $conn->error);
    echo json_encode(['error' => 'Prepare statement failed']);
    exit;
}

$stmt->bind_param("ss", $datasetId,$cell_cluster);
$stmt->execute();
$result = $stmt->get_result();

// Check whether there are results
if ($result->num_rows > 0) {
    // Get the first row
    $row = $result->fetch_assoc();

    // Set appropriate HTTP headers
    header('Content-Type: application/octet-stream');

    // Clear the buffer and turn off output buffering
    ob_clean();
    flush();

    // Output binary data
    echo $row['bin_data'];
} else {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No DEG data found for the given parameters']);
}

$stmt->close();
?>