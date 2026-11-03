<?php
include 'config.php';
ini_set('memory_limit', '512M');
// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Write database connection errors to the log file
    file_put_contents("../log/redis_error_log.txt", "Connection failed: " . $conn->connect_error . PHP_EOL, FILE_APPEND);
    exit;
}

// Define batch value
$studyId = $_GET['id'] ?? '';

// Query the study table for the datasets field
$sql = "SELECT datasets FROM study WHERE study_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studyId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Initialize array
$umap_data = array();
$cluster_labels = array();

if ($row) {
    // Get the datasets field and split by comma
    $datasets = explode(",", $row['datasets']);

    // Iterate over each dataset_id and query the cells table
    foreach ($datasets as $dataset_id) {
        $dataset_id = trim($dataset_id); // Trim possible whitespace
        $sql = "SELECT c.cell_id, c.umap1, c.umap2, c.cluster_label2
                FROM cells c
                WHERE c.dataset_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $dataset_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Store data in the array
            while ($row = $result->fetch_assoc()) {
                $umap_data[] = array(
                    'i' => $row['cell_id'],
                    'u1' => $row['umap1'],
                    'u2' => $row['umap2'],
                    'c' => $row['cluster_label2'],
                );
                $cluster_labels[$row['cluster_label2']] = true; // Collect unique cluster_label values
            }
        } else {
            echo "0 results";
            $conn->close();
            exit;
        }
    }
} else {
    echo "No datasets found for study ID: " . $studyId;
    $conn->close();
    exit;
}

// Convert category info to an array
$cluster_labels = array_keys($cluster_labels);

$response = json_encode(['umap_data' => $umap_data, 'cluster_labels' => $cluster_labels]);

$compressed = zlib_encode($response, ZLIB_ENCODING_GZIP);
// Return JSON data
header('Content-Type: application/octet-stream');
    // Clear the buffer and turn off output buffering
ob_clean();
flush();
echo $compressed;
?>