<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DataTables Example</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include DataTables CSS version 2 -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-2.0.0/datatables.min.css"/>
    <!-- Include jQuery latest version -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include DataTables version 2 -->
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-2.0.0/datatables.min.js"></script>
    <!-- Include Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Server Data</h2>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="management-link-tab" data-bs-toggle="tab" data-bs-target="#management-link" type="button" role="tab" aria-controls="management-link" aria-selected="false">Management Link</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div id="server-data">
                    <!-- Original table here -->
                    <?php
                    include 'connect.php';

                    $sql = "SELECT tbl_server.server_id, tbl_server_network.Network_IP, tbl_server.server_name, tbl_server.Server_OS_Version
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
                                        <th>Server OS</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';

                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>
                                    <td>' . $row['server_id'] . '</td>
                                    <td>' . $row['Network_IP'] . '</td>
                                    <td>' . $row['server_name'] . '</td>
                                    <td>' . $row['Server_OS_Version'] . '</td>';
                            
                            if ($row['Server_OS_Version'] === 'Windows Server 2019') {
                                echo '<td><button onclick="launchVNC(\'' . $row['Network_IP'] . '\')">Launch VNC</button></td>';
                            } elseif ($row['Server_OS_Version'] === 'Ubuntu 20.04') {
                                echo '<td><button onclick="launchPuTTY(\'' . $row['Network_IP'] . '\')">Launch PuTTY</button></td>';
                            } else {
                                echo '<td></td>';
                            }
                            
                            echo '</tr>';
                        }

                        echo '</tbody>
                              </table>';
                    } else {
                        echo "No data found";
                    }

                    $conn->close();
                    ?>
                </div>
            </div>
            <div class="tab-pane fade" id="management-link" role="tabpanel" aria-labelledby="management-link-tab">
                <div id="management-data">
                    <h2>Management Link</h2>
                    <button id="add-row" class="btn btn-primary mb-3">Add</button>
                    <!-- New table here -->
                    <?php
                    include 'connect.php';

                    $sql = "SELECT mgnt_id, mgnt_name, mgnt_link, mgnt_ip, mgnt_type FROM tbl_management_link"; // Modify the SQL query as needed

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo '<table id="management-table" class="display" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>IP</th>
                                        <th>Link</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';

                        while ($row = $result->fetch_assoc()) {
                            echo '<tr data-id="' . $row['mgnt_id'] . '">
                                    <td>' . $row['mgnt_id'] . '</td>
                                    <td>' . $row['mgnt_name'] . '</td>
                                    <td>' . $row['mgnt_ip'] . '</td>
                                    <td><a href="' . $row['mgnt_link'] . '" target="_blank">' . $row['mgnt_link'] . '</a></td>
                                    <td>' . $row['mgnt_type'] . '</td>
                                    <td>
                                        <button class="edit-btn btn btn-warning">Edit</button>
                                        <button class="delete-btn btn btn-danger">Delete</button>
                                    </td>
                                  </tr>';
                        }

                        echo '</tbody>
                              </table>';
                    } else {
                        echo "No data found";
                    }

                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize DataTables version 2
            $('#server-table').DataTable({
                "pageLength": 100  // Set to display 100 rows per page
            });
            $('#management-table').DataTable({
                "pageLength": 100  // Set to display 100 rows per page
            });

            // Handle the click event for the Edit button
            $('#management-table').on('click', '.edit-btn', function() {
                var $row = $(this).closest('tr');
                var $tds = $row.find('td').not(':last'); // Exclude the last column which is the action button
                var id = $row.data('id');

                if ($(this).text() === 'Edit') {
                    $tds.each(function() {
                        var text = $(this).text();
                        $(this).html('<input type="text" class="form-control" value="' + text + '"/>');
                    });
                    $(this).text('Save');
                } else {
                    var updatedData = [];
                    $tds.each(function() {
                        var input = $(this).find('input');
                        if (input.length) {
                            var text = input.val();
                            $(this).html(text);
                            updatedData.push(text);
                        }
                    });
                    $(this).text('Edit');

                    // Update the database with AJAX
                    $.ajax({
                        type: 'POST',
                        url: 'update_management_link.php',
                        data: {
                            mgnt_id: id,
                            mgnt_name: updatedData[1],
                            mgnt_ip: updatedData[2],
                            mgnt_link: updatedData[3],
                            mgnt_type: updatedData[4]
                        },
                        success: function(response) {
                            console.log(response);
                            alert('Update successful.');
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            alert('Failed to update.');
                        }
                    });
                }
            });

            // Handle the click event for the Add button
            $('#add-row').on('click', function() {
                var $table = $('#management-table').DataTable();
                var newRow = $table.row.add([
                    '<input type="text" class="form-control" placeholder="Name"/>',
                    '<input type="text" class="form-control" placeholder="IP"/>',
                    '<input type="text" class="form-control" placeholder="Link"/>',
                    '<input type="text" class="form-control" placeholder="Type"/>',
                    '<button class="save-btn btn btn-success">Save</button>'
                ]).draw().node();

                $(newRow).addClass('new-row');
            });

            // Handle the click event for the Save button in the new row
            $('#management-table').on('click', '.save-btn', function() {
                var $row = $(this).closest('tr');
                var $tds = $row.find('td').not(':last');
                var newData = [];

                $tds.each(function() {
                    var input = $(this).find('input');
                    if (input.length) {
                        var text = input.val();
                        $(this).html(text);
                        newData.push(text);
                    }
                });

                // Remove the Save button and add Edit and Delete buttons
                $(this).closest('td').html('<button class="edit-btn btn btn-warning">Edit</button><button class="delete-btn btn btn-danger">Delete</button>');

                // Add the new data to the database with AJAX
                $.ajax({
                    type: 'POST',
                    url: 'add_management_link.php',
                    data: {
                        mgnt_name: newData[0],
                        mgnt_ip: newData[1],
                        mgnt_link: newData[2],
                        mgnt_type: newData[3]
                    },
                    success: function(response) {
                        console.log(response);
                        alert('New row added successfully.');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Failed to add new row.');
                    }
                });
            });

            // Handle the click event for the Delete button
            $('#management-table').on('click', '.delete-btn', function() {
                var $row = $(this).closest('tr');
                var id = $row.data('id');

                if (confirm('Are you sure you want to delete this row?')) {
                    // Send AJAX request to delete the row
                    $.ajax({
                        type: 'POST',
                        url: 'delete_management_link.php',
                        data: {
                            mgnt_id: id
                        },
                        success: function(response) {
                            console.log(response);
                            alert('Row deleted successfully.');
                            $row.remove(); // Remove the row from the table
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            alert('Failed to delete row.');
                        }
                    });
                }
            });
        });

        function launchVNC(ip) {
            var pathToVNC = 'C:\\Program Files\\RealVNC\\VNC Viewer\\vncviewer.exe'; // Set the path to VNC Viewer here

            // Create command including IP to open VNC Viewer
            var command = '"' + pathToVNC + '" ' + ip;

            // Use AJAX to call PHP script that launches VNC Viewer
            $.ajax({
                type: 'POST',
                url: 'launch_vnc.php', // Name of the PHP file that opens VNC Viewer
                data: { command: command },
                success: function(response) {
                    console.log(response);
                    alert('VNC Viewer launched successfully.');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Failed to launch VNC Viewer.');
                }
            });
        }

        function launchPuTTY(ip) {
            var pathToPuTTY = 'C:\\Program Files\\PuTTY\\putty.exe'; // Set the path to PuTTY here

            // Create command including IP to open PuTTY
            var command = '"' + pathToPuTTY + '" -ssh ' + ip;

            // Use AJAX to call PHP script that launches PuTTY
            $.ajax({
                type: 'POST',
                url: 'launch_putty.php', // Name of the PHP file that opens PuTTY
                data: { command: command },
                success: function(response) {
                    console.log(response);
                    alert('PuTTY launched successfully.');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Failed to launch PuTTY.');
                }
            });
        }
    </script>
</body>
</html>
