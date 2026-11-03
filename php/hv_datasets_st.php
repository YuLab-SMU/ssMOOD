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

$sql = "SELECT dataset_id, cells FROM datasets WHERE scorst = 'st'";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    // Store data in the array
    while ($row = $result->fetch_assoc()) {
        $data[$row['dataset_id']] = $row['cells'];
    }
}

// Close connection
$conn->close();

// Convert data to JSON
$response = json_encode($data);


// Return JSON data
echo $response;
?>
