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
$studyId = $_GET['id'] ?? '';
$gene = $_GET['gene'] ?? '';

// Check whether parameters are empty
if (empty($studyId) || empty($gene)) {
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

// Query the study table for the datasets field
$sql = "SELECT datasets FROM study WHERE study_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studyId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    // Get the datasets field and split by comma
    $datasets = explode(",", $row['datasets']);
    $expressionData = []; // Initialize array

    // Iterate over each dataset_id and query the sc_expression table
    foreach ($datasets as $dataset_id) {
        $dataset_id = trim($dataset_id); // Trim possible whitespace
        $sql = "SELECT bin_data FROM st_expression WHERE dataset_id = ? AND gene_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $dataset_id, $gene);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $binDataRow = $result->fetch_assoc();
            $expressionData[] = $binDataRow['bin_data']; // Append bin_data to the array
        } else {
        }
    }

    // Set appropriate HTTP headers
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="expression_data.bin"');

    // Clear the buffer and turn off output buffering
    ob_clean();
    flush();

    // Output binary data
    foreach ($expressionData as $binData) {
        echo pack("N", strlen($binData)); // Output data length (4 bytes)
        echo $binData; // Output binary data
        //file_put_contents($logFile, strlen($binData).'\n', FILE_APPEND);
    }
} else {
    echo json_encode(['error' => "No datasets found for study ID: $studyId"]);
}

// Close statement
$stmt->close();

// Close connection
$conn->close();
?>