<?php
include 'config.php';
// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Define datasetId value
$datasetId = $_GET['id'] ?? '';

if (empty($datasetId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid dataset ID']);
    exit;
}

$sql = "SELECT DISTINCT group_type
        FROM deg_bin
        WHERE dataset_id = ? AND deg_group='cluster';";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    error_log("Prepare statement failed: " . $conn->error);
    echo json_encode(['error' => 'Prepare statement failed']);
    exit;
}

$stmt->bind_param("s", $datasetId);
$stmt->execute();
$result = $stmt->get_result();

$cluster_labels = [];
while ($row = $result->fetch_assoc()) {
    $cluster_labels[] = $row['group_type'];
}
header('Content-Type: application/json');
// Convert the array to JSON
echo json_encode($cluster_labels);

$conn->close();

?>