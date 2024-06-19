<?php
include 'connect.php';

$mgnt_id = $_POST['mgnt_id'];

// Delete the row from the database
$sql = "DELETE FROM tbl_management_link WHERE mgnt_id='$mgnt_id'";

if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
