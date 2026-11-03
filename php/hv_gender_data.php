<?php
include 'config.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed']));
}


// Query the counts for mouse and human
$query = "SELECT species, COUNT(*) as count FROM datasets WHERE species IN ('mouse', 'human') GROUP BY species";
$result = $conn->query($query);

// Initialize result array
$resultArray = [
    'mouse' => 0,
    'human' => 0
];

// Get query results
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $resultArray[$row['species']] = $row['count'];
    }
    $result->free();
}

// Close database connection
$conn->close();

echo json_encode($resultArray);

?>
