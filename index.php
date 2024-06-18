<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Launch VNC Viewer</title>
    
    <!-- Bootstrap CSS -->
    <link href="/path/to/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/v/dt/dt-2.0.0/datatables.min.css" rel="stylesheet">

    <style>
        body {
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mt-4">Launch VNC Viewer</h2>

    <!-- Search form -->
    <div class="mb-4">
        <input type="text" class="form-control" placeholder="Search by Server Name or IP Address" id="search">
    </div>

    <table id="server-table" class="table table-bordered">
        <thead>
            <tr>
                <th>Server ID</th>
                <th>Server Name</th>
                <th>Server IP</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="/path/to/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/v/dt/dt-2.0.0/datatables.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#server-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "fetch_servers.php", // ตั้งชื่อไฟล์ PHP ที่ใช้สำหรับดึงข้อมูล
            "type": "POST"
        },
        "columns": [
            { "data": "server_id" },
            { "data": "server_name" },
            { "data": "Network_IP" },
            { 
                "data": null,
                "render": function(data, type, row) {
                    return '<button type="button" class="btn btn-primary" onclick="launchVNC(\'' + row.Network_IP + '\')">Launch VNC Viewer</button>';
                }
            }
        ]
    });

    // Search box event listener
    $('#search').on('keyup', function() {
        table.search(this.value).draw();
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

</body>
</html>
