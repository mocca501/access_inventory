<?php
include 'connect.php';

$mgnt_name = $_POST['mgnt_name'];
$mgnt_ip = $_POST['mgnt_ip'];
$mgnt_link = $_POST['mgnt_link'];
$mgnt_type = $_POST['mgnt_type'];

// Insert the new row into the database
$sql = "INSERT INTO tbl_management_link (mgnt_name, mgnt_ip, mgnt_link, mgnt_type) VALUES ('$mgnt_name', '$mgnt_ip', '$mgnt_link', '$mgnt_type')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
