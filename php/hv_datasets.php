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

// Define query conditions
$queries = [
    ['species' => 'mouse', 'scorst' => 'sc'],
    ['species' => 'mouse', 'scorst' => 'st'],
    ['species' => 'human', 'scorst' => 'sc'],
    ['species' => 'human', 'scorst' => 'st'],
];

$results = [];

// Iterate over query conditions and run each query
foreach ($queries as $query) {
    $species = $conn->real_escape_string($query['species']);
    $scorst = $conn->real_escape_string($query['scorst']);

    $sql = "SELECT dataset_id, cells FROM datasets WHERE species = '$species' AND scorst = '$scorst'";
    $result = $conn->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        // Store data in the array
        while ($row = $result->fetch_assoc()) {
            $data[$row['dataset_id']] = $row['cells'];
        }
    }

    // Store results in the final array
    $results[] = $data;
}

// Close connection
$conn->close();

// Convert results to JSON
$response = json_encode($results);

$compressed = zlib_encode($response, ZLIB_ENCODING_GZIP);

    // Set appropriate HTTP headers
header('Content-Type: application/octet-stream');
    // Clear the buffer and turn off output buffering
ob_clean();
flush();
echo $compressed;
?>