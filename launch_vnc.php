<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $command = $_POST['command'];

    // ใช้ exec() เพื่อรันคำสั่งในระบบปฏิบัติการ
    exec($command, $output, $return_var);

    if ($return_var === 0) {
        echo "VNC Viewer launched successfully.";
    } else {
        echo "Failed to launch VNC Viewer. Return code: " . $return_var;
    }
} else {
    echo "Invalid request method.";
}
?>
