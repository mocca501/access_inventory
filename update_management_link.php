<?php
include 'connect.php';

$mgnt_id = $_POST['mgnt_id'];
$mgnt_name = $_POST['mgnt_name'];
$mgnt_ip = $_POST['mgnt_ip'];
$mgnt_link = $_POST['mgnt_link'];
$mgnt_type = $_POST['mgnt_type'];

// Update the row in the database
$sql = "UPDATE tbl_management_link SET mgnt_name='$mgnt_name', mgnt_ip='$mgnt_ip', mgnt_link='$mgnt_link', mgnt_type='$mgnt_type' WHERE mgnt_id='$mgnt_id'";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
