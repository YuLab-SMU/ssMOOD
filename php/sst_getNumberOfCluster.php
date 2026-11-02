<?php
include 'config.php';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check whether the connection succeeded
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed']));
}

// Get study_id
$studyId = $_GET['id'] ?? '';

// Query the study table for the datasets field
$sql = "SELECT datasets FROM study WHERE study_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studyId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode([]);
    $stmt->close();
    $conn->close();
    exit;
}

// Get the datasets field and split by comma
$datasets = explode(",", $row['datasets']);

// Build the IN clause
$datasetInClause = implode("','", array_map(function ($d) use ($conn) {
    return $conn->real_escape_string(trim($d));
}, $datasets));

// Query the count of each cell type
$query = "
    SELECT cluster_label2, COUNT(*) as cell_count
    FROM spatial_cells
    WHERE dataset_id IN ('$datasetInClause')
    GROUP BY cluster_label2
";

// Execute query
$result = $conn->query($query);

// Initialize result array
$resultArray = [];

// Get query results
while ($row = $result->fetch_assoc()) {
    $resultArray[$row['cluster_label2']] = (int) $row['cell_count'];
}

// Close database connection
$conn->close();

// Return results as JSON
echo json_encode($resultArray);
?>