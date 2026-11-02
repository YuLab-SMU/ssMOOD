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

// Define batch value
$datasetId = $_GET['id'] ?? '';

if (strpos($datasetId, 'study') !== false) {
    // Parse the study part
    $study = explode('.', $datasetId)[0];
    $sql = "SELECT gene_id FROM genes WHERE study_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $study);
} else {
    $sql = "SELECT g.gene_id,d.study
    FROM genes g
    JOIN datasets d ON g.study_id = d.study
    WHERE d.dataset_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $datasetId);
}

$stmt->execute();
$result = $stmt->get_result();
// Fetch query results into an array
$genes = array();
while ($row = $result->fetch_assoc()) {
    $genes[] = $row['gene_id'];
}

// Close database connection
$stmt->close();
$conn->close();


$compressed = zlib_encode(json_encode(array('genes' => $genes)), ZLIB_ENCODING_GZIP);

    // Set appropriate HTTP headers
header('Content-Type: application/octet-stream');
    // Clear the buffer and turn off output buffering
ob_clean();
flush();
echo $compressed;
?>