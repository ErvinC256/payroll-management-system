<head>
    <style>
        #logTable thead tr th {
            padding-left: 5px;
        }
        #logTable tbody tr td {
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding-left: 5px;
        }
    </style>
</head> 
<body>
    <section>
        <h4>Log</h4>
        <hr>
        <div style="display: grid; grid-template-columns: 2fr 1fr;">
            <div style="max-height: 200px; overflow-x: auto; overflow-y: auto; border-radius: 5px; border: 2px groove white;">
                <table id="logTable" style="width: 1400px;">
                    <thead style="background-color: #efefee;">
                        <tr>
                            <th style="width: 200px;">Timestamp</th>
                            <th style="width: 200px;">Operation</th>
                            <th style="width: 200px;">Object</th>
                            <th style="width: auto;">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Retrieve logs from the database
                            $stmt = $conn->prepare('SELECT * FROM temp_log WHERE page = ? ORDER BY timestamp DESC');
                            $stmt->bind_param("s", $_SESSION['page']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $stmt->close();

                            // Populate the table with data
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $row['timestamp'] . '</td>';
                                echo '<td>' . $row['operation'] . '</td>';
                                echo '<td>' . $row['object'] . '</td>';
                                echo '<td>' . $row['details'] . '</td>';
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div style="background-color: #efefee; border-radius: 5px; border: 2px groove white; padding: 10px; height: 200px;">
                <form id="export-form">
                    <table>
                        <tr>
                            <td><label>File name</label></td>
                            <td><input type="text" name="filename"></td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;"><label>Remarks</label> <span title="This remarks will be displayed inside the file" style="cursor: help;">&#x1F6C8;</span></td>
                            <td><textarea name="remarks" placeholder="Enter some remarks here (optional)" cols="30" rows="4"></textarea></td>
                        </tr>
                        <tr>
                            <td><label>File format</label></td>
                            <td style="display: flex; align-items: center;">
                                <input type="radio" name="fileformat" value="pdf" checked style="margin-right: 5px;"><span style="margin-right: 10px;">PDF</span>
                                <input type="radio" name="fileformat" value="csv" style="margin-right: 5px;"><span>CSV</span>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div id="general-message2" style="height: 25px; padding-left: 5px; font-style: italic; border-radius: 5px; border: 2px groove white;"></div>
        <div style="display: flex; justify-content: space-between; background-color: #d3dce3; padding: 10px; border-radius: 5px; border: 2px groove white;">
            <button id="clearLogButton" class="button-8">Clear Log</button>
            <div>
                <button id="refreshButton2" class="button-8">Refresh Log</button>
                <button id="exportButton" class="button-8">Export</button>  
            </div>
        </div>
    </section>
</body>
