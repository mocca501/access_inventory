<?php
// Include the database connection file (connect.php)
include 'connect.php';

// SQL query to fetch data from tbl_server with search condition
$sql = "SELECT tbl_server.server_id, tbl_server_network.Network_IP, tbl_server.server_name
        FROM tbl_server
        LEFT JOIN tbl_server_network 
        ON tbl_server.Server_ID = tbl_server_network.Server_ID";

$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลในฐานข้อมูลหรือไม่
if ($result->num_rows > 0) {
    // สร้าง array เพื่อเก็บข้อมูล
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    // แปลงข้อมูลเป็นรูปแบบ JSON
    echo json_encode($data);
} else {
    echo "0 results";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
