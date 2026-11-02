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

// Query the sum of the cells field for sc and st
$query = "
    SELECT 
        SUM(CASE WHEN scorst = 'sc' THEN cells ELSE 0 END) AS sc_cells_sum,
        SUM(CASE WHEN scorst = 'st' THEN cells ELSE 0 END) AS st_cells_sum
    FROM datasets
";

$result = $conn->query($query);

// Initialize result array
$resultArray = [
    'sc_cells_sum' => 0,
    'st_cells_sum' => 0
];

// Get query results
if ($result) {
    $row = $result->fetch_assoc();
    $resultArray['sc_cells_sum'] = $row['sc_cells_sum'];
    $resultArray['st_cells_sum'] = $row['st_cells_sum'];
    $result->free();
}

// Close database connection
$conn->close();

echo json_encode($resultArray);
?>