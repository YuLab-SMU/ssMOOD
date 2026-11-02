<?php
ini_set('memory_limit', '512M');
// Database connection info
include 'config.php';

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Write database connection errors to the log file
    file_put_contents("../log/redis_error_log.txt", "Connection failed: " . $conn->connect_error . PHP_EOL, FILE_APPEND);
    exit;
}

// Define query conditions for the 4 tables, with fields uniformly named umap1 and umap2
$queries = [
    "SELECT c.cell_id AS cell_id, c.umap1 AS umap1, c.umap2 AS umap2, c.cluster_label2 FROM cells c WHERE dataset_id = 'SC0023_S_Stri'",
    "SELECT c.spatial_cell_id AS cell_id, c.x_coordinate AS umap1, c.y_coordinate AS umap2, c.cluster_label2 FROM spatial_cells c WHERE dataset_id = 'ST0049_S_mPFC'",
    "SELECT c.cell_id AS cell_id, c.umap1 AS umap1, c.umap2 AS umap2, c.cluster_label2 FROM cells c WHERE dataset_id = 'SC0039_C_BA11'",
    "SELECT c.spatial_cell_id AS cell_id, c.x_coordinate AS umap1, c.y_coordinate AS umap2, c.cluster_label2 FROM spatial_cells c WHERE dataset_id = 'ST0054_S_BA12'"
];

// Initialize array to store data from the 4 tables
$umap_datasets = [];

foreach ($queries as $index => $sql) {
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $umap_data = [];
        $cluster_labels = [];

        while ($row = $result->fetch_assoc()) {
            $umap_data[] = [
                'i' => $row['cell_id'],
                'u1' => $row['umap1'],
                'u2' => $row['umap2'],
                'c' => $row['cluster_label2']
            ];

            if (!in_array($row['cluster_label2'], $cluster_labels)) {
                $cluster_labels[] = $row['cluster_label2'];
            }
        }

        // Store the current table's data and category info in the array
        $umap_datasets[$index] = [
            'umap' => $umap_data,
            'cluster' => $cluster_labels
        ];
    } else {
        echo "0 results for dataset $index";
    }
}

$conn->close();

// Convert all data to JSON
$response = json_encode($umap_datasets);
$compressed = zlib_encode($response, ZLIB_ENCODING_GZIP);

    // Set appropriate HTTP headers
header('Content-Type: application/octet-stream');
    // Clear the buffer and turn off output buffering
ob_clean();
flush();
echo $compressed;
//echo $response;
?>