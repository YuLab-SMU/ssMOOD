<?php
ini_set('memory_limit', '2048M');

include 'config.php';
// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Define batch value
$studyId = $_GET['id'] ?? '';
if (empty($studyId)) {
    http_response_code(400);
    echo "Invalid or missing 'id' parameter.";
    exit;
}
// Query the study table for the datasets field
$sql = "SELECT datasets FROM study WHERE study_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studyId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$umap_data = array();
$cluster_labels = array();

if ($row) {
    // Get the datasets field and split by comma
    $datasets = explode(",", $row['datasets']);

    // Iterate over each dataset_id and query the cells table
    foreach ($datasets as $dataset_id) {
        $dataset_id = trim($dataset_id); // Trim possible whitespace
        $sql = "SELECT c.spatial_cell_id, c.x_coordinate, c.y_coordinate, c.cluster_label2
        FROM spatial_cells c
        WHERE c.dataset_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $dataset_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Store data in the array
            while ($row = $result->fetch_assoc()) {
                $umap_data[$dataset_id][] = array(
                    'i' => $row['spatial_cell_id'],
                    'x' => $row['x_coordinate'],
                    'y' => $row['y_coordinate'],
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

$response = json_encode(['coordinate_data' => $umap_data, 'cluster_labels' => $cluster_labels]);

$compressed = zlib_encode($response, ZLIB_ENCODING_GZIP);
// Return JSON data
header('Content-Type: application/octet-stream');
    // Clear the buffer and turn off output buffering
ob_clean();
flush();
echo $compressed;

//Memory stats
//$peakMemory = memory_get_peak_usage();
// Write peak memory usage to the log file
//$logEntry = date("Y-m-d H:i:s") . "[". __FILE__ ."] - Peak memory usage: " . $peakMemory/1024 . " KB\n";
//file_put_contents($logFile, $logEntry, FILE_APPEND);
?>
