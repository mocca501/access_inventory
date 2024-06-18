<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DataTables Example</title>
    <!-- เรียกใช้ CSS ของ DataTables version 2 -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-2.0.0/datatables.min.css"/>
    <!-- เรียกใช้ jQuery เวอร์ชั่นล่าสุด -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- เรียกใช้ DataTables version 2 -->
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-2.0.0/datatables.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Server Data</h2>
        <div id="server-data"></div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // เรียกใช้ DataTables version 2
            $('#server-table').DataTable({
                "pageLength": 50  // กำหนดให้แสดง 100 rows ต่อ page
            });
        });

        function launchVNC(ip) {
            var pathToVNC = 'C:\\Program Files\\RealVNC\\VNC Viewer\\vncviewer.exe'; // ตั้งค่า path ของ VNC Viewer ที่นี่

            // สร้าง command ที่รวม IP เข้าไปเพื่อใช้ในการเปิด VNC Viewer
            var command = '"' + pathToVNC + '" ' + ip;

            // ใช้ AJAX สำหรับการเรียก PHP ที่ทำการเปิด VNC Viewer
            $.ajax({
                type: 'POST',
                url: 'launch_vnc.php', // ตั้งชื่อไฟล์ PHP ที่ใช้สำหรับเปิด VNC Viewer
                data: { command: command },
                success: function(response) {
                    console.log(response); // แสดงผลลัพธ์ใน console สำหรับตรวจสอบ
                    alert('VNC Viewer launched successfully.');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Failed to launch VNC Viewer.');
                }
            });
        }
    </script>
    
    <?php
   include 'connect.php';

    $sql = "SELECT tbl_server.server_id, tbl_server_network.Network_IP, tbl_server.server_name
            FROM tbl_server
            LEFT JOIN tbl_server_network 
            ON tbl_server.Server_ID = tbl_server_network.Server_ID";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table id="server-table" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Server ID</th>
                        <th>Network IP</th>
                        <th>Server Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['server_id'] . '</td>
                    <td>' . $row['Network_IP'] . '</td>
                    <td>' . $row['server_name'] . '</td>
                    <td><button onclick="launchVNC(\'' . $row['Network_IP'] . '\')">Launch VNC</button></td>
                  </tr>';
        }

        echo '</tbody>
              </table>';
    } else {
        echo "ไม่พบข้อมูล";
    }

    $conn->close();
    ?>
</body>
</html>
