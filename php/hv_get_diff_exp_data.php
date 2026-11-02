<?php
include 'config.php';

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed']));
}

// Execute query
$sql = "SELECT gene_id, cluster_label, p_value, fold_change
            FROM differential_expression
            WHERE p_value < 0.05
            ORDER BY RAND()
            LIMIT 1000";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    // Store results in the array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Convert data to JSON
$response = json_encode($data);


// Return JSON data
echo $response;

// Close database connection
$conn->close();
?>