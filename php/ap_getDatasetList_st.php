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

// SQL query
$sql = "SELECT dataset_id FROM datasets WHERE scorst = 'st'"; // Assumes the table is named datasets and has id and name columns

// Execute query
$result = $conn->query($sql);

// Initialize array to store results
$datasets = array();

// Check results
if ($result) {
    // Iterate over the result set
    $i = 0 ;
    while ($row = $result->fetch_assoc()) {
        // Append each row to the array, with keys 'id' and 'name'
        $datasets[] = array(
            "id" => $i,
            "name" => $row['dataset_id']
        );
        $i++;
    }
    
    // Free the result set
    $result->free();
    
    // Convert the array to JSON, wrapped in an outer array under the 'datasets' key
    echo json_encode(array("datasets" => $datasets));
} else {
    error_log("Query failed: " . $conn->error);
    http_response_code(500);
    echo json_encode(['error' => 'Query failed']);
}

// Close connection
$conn->close();
?>