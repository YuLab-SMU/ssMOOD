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
$study_id = $_GET['id'] ?? '';
// SQL query
$sql = "SELECT study_id, species, age, sex, conditions, area, cells, clusters,information,datasets FROM study WHERE study_id = ?";

// Run the query with a prepared statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $study_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize array to store results
$datasets = array();

// Check results
if ($result) {
    // Iterate over the result set
    while ($row = $result->fetch_assoc()) {
        // Append each row to the array
        $datasets[] = $row;
    }
    
    // Free the result set
    $result->free();
    
    // Convert the array to JSON
    echo json_encode($datasets);
} else {
    error_log("Query failed: " . $conn->error);
    http_response_code(500);
    echo json_encode(['error' => 'Query failed']);
}

// Close connection
$stmt->close();
$conn->close();
?>