<?php
// Include the database connection file (connect.php)
include 'connect.php';

// SQL query to fetch data from tbl_server with search condition
$sql = "SELECT tbl_server.server_id, tbl_server_network.Network_IP, tbl_server.server_name, tbl_server.Server_OS_Version
        FROM tbl_server
        LEFT JOIN tbl_server_network 
        ON tbl_server.Server_ID = tbl_server_network.Server_ID";

// Execute SQL query
$result = mysqli_query($conn, $sql);

// Prepare data for DataTables
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Return data as JSON
echo json_encode(array('data' => $data));

$conn->close();
?>
