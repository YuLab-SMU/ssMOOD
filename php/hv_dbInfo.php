<?php
include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed']));
}

// Initialize result array
$results = [];

// Query the number of single cells
$sql = "SELECT COUNT(*) as count FROM cells";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $results['cellNum'] = $row['count'];
} else {
    $results['cellNum'] = 0;
}

// Query the number of categories
$sql = "SELECT COUNT(*) as count FROM datasets";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $results['clusterNum'] = $row['count'];
} else {
    $results['clusterNum'] = 0;
}
// Query the number of unique genes
$sql = "SELECT COUNT(DISTINCT gene_id) as count FROM genes";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $results['geneNum'] = $row['count'];
} else {
    $results['geneNum'] = 0;
}


$sql = "SELECT COUNT(*) as count FROM spatial_cells";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $results['spatialNum'] = $row['count'];
} else {
    $results['spatialNum'] = 0;
}

// Close connection
$conn->close();

// Return JSON result
echo json_encode($results);
?>