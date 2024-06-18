<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Launch VNC Viewer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h2>Launch VNC Viewer</h2>
    <form method="post">
        <label for="ip">IP Address:</label>
        <input type="text" id="ip" name="ip" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <input type="submit" name="launch" value="Launch VNC Viewer">
    </form>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['launch'])) {
    $ip = $_POST['ip'];
    $password = $_POST['password'];
    $pathToVNC = 'C:\\Program Files\\RealVNC\\VNC Viewer\\vncviewer.exe'; // ตั้งค่า path ของ VNC Viewer ที่นี่

    // สร้าง command ที่รวม IP และ Password เข้าไปเพื่อใช้ในการเปิด VNC Viewer
    // ในกรณีนี้ใช้ -Password และค่า Password โดยตรง
    $command = '"' . $pathToVNC . '" ' . $ip . ' -Password ' . escapeshellarg($password);

    exec('start "" ' . $command, $output, $return_var);

//     if ($return_var === 0) {
//         echo "<script>alert('VNC Viewer launched successfully.');</script>";
//     } else {
//         echo "<script>alert('Failed to launch VNC Viewer.');</script>";
//     }
}
?>

