<?php
include 'config.php';
// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);
// Specified dataset_id
$datasetId = $_GET['id'] ?? '';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check whether the connection succeeded
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed']));
}

// Query the count of each cell type
$query = "
    SELECT cluster_label2, COUNT(*) as cell_count
    FROM cells
    WHERE dataset_id = ?
    GROUP BY cluster_label2
";

// Prepare SQL statement
$stmt = $conn->prepare($query);

// Bind parameters
$stmt->bind_param('s', $datasetId);

// Execute query
$stmt->execute();

// Get results
$result = $stmt->get_result();

// Initialize result array
$resultArray = [];

// Get query results
while ($row = $result->fetch_assoc()) {
    $resultArray[$row['cluster_label2']] = (int) $row['cell_count'];
}

// Close database connection
$stmt->close();
$conn->close();

echo json_encode($resultArray);
?>