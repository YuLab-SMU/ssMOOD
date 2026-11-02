<?php
include 'config.php';

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Write database connection errors to the log file
    file_put_contents("../log/redis_error_log.txt", "Connection failed: " . $conn->connect_error . PHP_EOL, FILE_APPEND);
    exit;
}

// Get GET parameters
$datasetId = $_GET['id'] ?? '';
$gene = $_GET['gene'] ?? '';

// Check whether parameters are empty
if (empty($datasetId) || empty($gene)) {
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

// Prepare SQL query
$sql = "SELECT bin_data FROM sc_expression WHERE dataset_id = ? AND gene_id = ?";

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("ss", $datasetId, $gene);

// Execute query
$stmt->execute();

// Get results
$result = $stmt->get_result();

// Check whether there are results
if ($result->num_rows > 0) {
    // Get the first row
    $row = $result->fetch_assoc();
    
    // Set appropriate HTTP headers
    header('Content-Type: application/octet-stream');
    
    // Clear the buffer and turn off output buffering
    ob_clean();
    flush();
    
    // Output binary data
    echo $row['bin_data'];
} else {
    echo 'error';
}

// Close statement
$stmt->close();

// Close connection
$conn->close();
?>