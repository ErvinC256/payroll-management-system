<?php 
    include '../scripts/connectdb.php'; 

    // Set the page name to a session variable
    $_SESSION['page'] = 'Allowances';

    // Check if the username value is set in the session
    if (!isset($_SESSION['username'])) {
        // Redirect back to index.php
        header('Location: ../index.php');
        exit;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Payroll</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styles/button-8.css">
    <link rel="stylesheet" href="../styles/modal.css">
    <link rel="stylesheet" href="../styles/scrollbars.css">
    <link rel="stylesheet" href="../styles/forms.css">
    <link rel="stylesheet" href="../styles/base.css">
    <style>
        #deletedAllowancesTable {
            font-size: 12px;
            cursor: default;
        }
        #deletedAllowancesTable tr.selected {
            background-color: lightblue;
        }
    </style>
    
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>
    <main>
        <p style="text-align: right; font-style: italic; position: relative; bottom: 10px;">logged in as <?php echo $_SESSION['username']; ?></p>
        <h2>Allowances</h2>
        <hr>
        <div style="border-radius: 5px; border: 2px groove white; padding: 10px;">
            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Base Amount $</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $result = $conn->query("SELECT * FROM allowances");

                        // Output data in the tbody section of the table
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['type'] . "</td>";
                            echo "<td>" . $row['base_amount'] . "</td>";
                            echo "<td><i class='fas fa-edit'></i> | <i class='fas fa-trash'></i></td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div id="general-message" style="height: 25px; padding-left: 5px; font-style: italic; border-radius: 5px; border: 2px groove white;"></div>
        <div style="display: flex; justify-content: space-between; background-color: #d3dce3; padding: 10px; border-radius: 5px; border: 2px groove white;">
            <button id="createButton" class="button-8">Create</button>
            <div>
                <button id="refreshButton" class="button-8">Refresh</button>
            </div>
        </div>
        <br><br><br>
        <?php include '../includes/log-section.php'; ?>
        <br><br><br>
        
        <div id="updateModal" class="modal">
            <div class="modal-content" style="width: 325px; margin: 10% auto;">
                <div class="modal-header">
                    <p>Update Allowance</p>
                    <span class="close" onclick="closeModal('updateModal')">×</span>
                </div>
                <div class="modal-body">
                    <form id="update-form">
                        <input type="hidden" id="updateId">
                        <input type="hidden" id="originalBaseAmt">
                        <fieldset>
                            <legend>Update Details</legend>
                            <table>
                                <tr>
                                    <td><label>Name</label></td>
                                    <td><input id="name" type="text" class="non-editable" readonly tabindex="-1"></td>
                                </tr>
                                <tr>
                                    <td><label>Type</label></td>
                                    <td><input id="type" type="text" class="non-editable" readonly tabindex="-1"></td>
                                </tr>
                                <tr>
                                    <td><label>Base Amount</label> <span title="Modifying fixed allowances will affect all employees" style="cursor: help;">&#x1F6C8;</span></td>
                                    <td><input id="baseAmt" type="number" style="height: 21px;" step="50"></td>
                                </tr>
                            </table>
                        </fieldset>
                        <hr>
                        <div style="text-align: right;">
                            <button type="submit" id="okButton-update">OK</button>
                            <button type="button" onclick="closeModal('updateModal')">Cancel</button>
                        </div>
                    </form>
                </div>  
            </div>
        </div>
        <div id="deleteModal" class="modal">
            <div class="modal-content" style="width: 250px;">
                <div class="modal-header">
                    <p>Delete Payroll</p>
                    <span class="close" onclick="closeModal('deleteModal')">×</span>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="deleteId">
                        <p>Are you sure you want to delete <span id="deleteName"></span>?</p>
                        <br>
                        <label>Reason<span style="color: red;">*</span> <span title="Short description of reason" style="cursor: help;">&#x1F6C8;</span></label>
                        <input type="text" id="reason">
                        <hr>
                        <div style="text-align: right;">
                            <button type="submit" id="confirmButton">OK</button>
                            <button type="button" onclick="closeModal('deleteModal')">Cancel</button>
                        </div>
                    </form>
                </div>  
            </div>
        </div>
        <div id="createModal" class="modal">
            <div class="modal-content" style="width: 450px; margin: 5% auto;">
                <div class="modal-header">
                    <p>Create Allowance</p>
                    <span class="close" onclick="closeModal('createModal')">×</span>
                </div>
                <div class="modal-body">
                    <form id="create-form">
                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 5px;">
                            <fieldset>
                                <legend>Allowance Details</legend>
                                <table>
                                    <tr>
                                        <td style="width: 100px;"><label>Name</label><span style="color: red;">*</span> <span title="Allowance name" style="cursor: help;">&#x1F6C8;</span></td>
                                        <td><input type="text" name="name"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100px;"><label>Type</label><span style="color: red;">*</span> <span title="Fixed allowances: Same amount for all employees. Varied allowances: Amount can differ between employees" style="cursor: help;">&#x1F6C8;</span></td>
                                        <td>
                                            <select name="type">
                                                <option value="fixed">Fixed</option>
                                                <option value="varied">Varied</option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                                <div id="create-message-top" style="height: 20px; font-size: 12px; font-style: italic; position: relative; top: 15px;">Select a deleted allowance to view its details</div>
                            </fieldset>
                            <div style="background-color: white; max-height: 120px; overflow-x: auto; overflow-y: auto; border: 0.1px solid black;">
                                <table id="deletedAllowancesTable">
                                    <?php
                                        // Retrieve records from deleted_allowances table
                                        $stmt = $conn->prepare('SELECT name, reason FROM deleted_allowances');
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        // Display the retrieved names in the table
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr><td>" . $row['name'] . "</td><td style='display: none;'>" . $row['reason'] . "</td></tr>";
                                        }
                                    ?>
                                    <script>
                                        let tableRows = document.querySelectorAll('#deletedAllowancesTable tr');
                                        tableRows.forEach(row => {
                                            row.addEventListener('click', () => {
                                                // Remove the 'selected' class from any other rows that may have it
                                                tableRows.forEach(r => r.classList.remove('selected'));
                                                // Add the 'selected' class to the clicked row
                                                row.classList.add('selected');
                                                let reason = row.querySelector('td:nth-child(2)').textContent;
                                                document.querySelector('#create-message-top').textContent = reason;
                                            });
                                        });
                                    </script>
                                </table>
                            </div>
                        </div>
                        <br>
                        <fieldset>
                            <legend>Amount Details</legend>
                            <table>
                                <tr>
                                    <td style="width: 150px;"><label>Base Amount</label><span style="color: red;">*</span> <span title="Monthly base amount prior to revision" style="cursor: help;">&#x1F6C8;</span></td>
                                    <td><input type="number" name="baseAmt" style="height: 21px;" step="50" placeholder="0.00"></td>
                                </tr>
                            </table>
                        </fieldset>
                        <p><span style="color: red;">*</span> indicates a mandatory field.</p>
                        <div id="create-message" style="height: 20px; text-align: right; font-style: italic; color: red;"></div>
                        <hr style="margin-top: 0;">
                        <div style="display: flex; justify-content: space-between;">
                            <button type="button" id="resetButton">Reset</button>
                            <div>
                                <button type="submit" id="okButton-create">OK</button>
                                <button type="button" onclick="closeModal('createModal')">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>  
            </div>
        </div>
    </main>

    <!-- scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.20/jspdf.plugin.autotable.min.js"></script>
    <script src="../js/saveToFile.js"></script>
    <script src="../js/log-section.js"></script>
    <script>
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = "none";
        }
        const table = $('#example').DataTable();
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            document.querySelector('#example tbody').addEventListener('click', function (event) {
                if (event.target.classList.contains('fa-edit')) {
                    const row = event.target.closest('tr');
                    const updateId = row.querySelector('td:first-child').textContent;
                    const name = row.querySelector('td:nth-child(2)').textContent;
                    const type = row.querySelector('td:nth-child(3)').textContent;
                    const baseAmt = row.querySelector('td:nth-child(4)').textContent;

                    // Set the values in the modal fields
                    document.querySelector('#updateId').value = updateId;
                    document.querySelector('#name').value = name;
                    document.querySelector('#type').value = type;
                    document.querySelector('#baseAmt').value = baseAmt;
                    document.querySelector('#originalBaseAmt').value = baseAmt;

                    if (type === "Varied") {
                        document.querySelector('#baseAmt').classList.add('non-editable');
                        document.querySelector('#baseAmt').readOnly = true;
                        document.querySelector('#baseAmt').tabIndex = -1;
                    } else {
                        document.querySelector('#baseAmt').classList.remove('non-editable');
                        document.querySelector('#baseAmt').readOnly = false;
                        document.querySelector('#baseAmt').tabIndex = 0;
                    }

                    // Open the modal window
                    document.querySelector("#updateModal").style.display = "block";

                } else if (event.target.classList.contains('fa-trash')) {
                    const row = event.target.closest('tr');
                    const deleteId = row.querySelector('td:first-child').textContent;
                    const deleteName = row.querySelector('td:nth-child(2)').textContent;

                    document.querySelector('#deleteId').value = deleteId;
                    document.querySelector('#deleteName').textContent = deleteName;

                    document.querySelector('#deleteModal').style.display = "block";
                }
            });

            // update functionalities
            document.querySelector('#okButton-update').addEventListener('click', function(event) {
                event.preventDefault();

                // Get the form values
                let updateId = document.querySelector('#updateId').value;
                let name = document.querySelector('#name').value;
                let type = document.querySelector('#type').value;
                let baseAmt = document.querySelector('#baseAmt').value;
                let originalBaseAmt = document.querySelector('#originalBaseAmt').value;

                // Convert baseAmt and originalBaseAmt to two decimal places
                baseAmt = Number(baseAmt).toFixed(2);
                originalBaseAmt = Number(originalBaseAmt).toFixed(2);

                if (baseAmt === originalBaseAmt) {
                    closeModal('updateModal');
                    return;
                }

                // Create a FormData object
                const formData = new FormData();
                formData.append('id', updateId);
                formData.append('name', name);
                formData.append('baseAmt', baseAmt);
                formData.append('originalBaseAmt', originalBaseAmt);

                // Send the form data to update-resignation.php using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../scripts/update-allowance.php');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Parse the JSON response
                        const response = JSON.parse(xhr.responseText);

                        // Check if the database update was successful
                        if (response.success) {
                            // Close the modal and display a success message
                            closeModal('updateModal');
                            document.querySelector('#general-message').textContent = 'Update successful. Refresh the table to see changes.';

                        } else {
                            // handle error
                        }
                    } else {
                            // Handle error
                    }
                };
                xhr.send(formData);
            });

            // delete functionalities
            document.querySelector('#confirmButton').addEventListener('click', function(event) {
                event.preventDefault();

                // Get the form values
                let deleteId = document.querySelector('#deleteId').value;
                let deleteName = document.querySelector('#deleteName').textContent;
                let reasonInput = document.querySelector('#reason').value;

                if (!reasonInput) {
                    return;
                }
                
                // Create a FormData object
                const formData = new FormData();
                formData.append('id', deleteId);
                formData.append('deleteName', deleteName);
                formData.append('reason', reasonInput);

                console.log(deleteId);
                console.log(deleteName);
                console.log(reasonInput);
            
                // Send the form data to update-resignation.php using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../scripts/delete-allowance.php');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Parse the JSON response
                        const response = JSON.parse(xhr.responseText);

                        // Check if the database update was successful
                        if (response.success) {
                            // Close the modal and display a success message
                            closeModal('deleteModal');
                            document.querySelector('#general-message').textContent = 'Delete successful. Refresh the table to see changes.';

                        } else {
                            // handle error
                        }
                    } else {
                        // Handle error
                    }
                };
                xhr.send(formData);
            });

            // create functionalities
            document.querySelector('#createButton').addEventListener('click', function(event) {
                document.querySelector("#createModal").style.display = "block";
            });

            document.querySelector('#resetButton').addEventListener('click', function(event) {
                document.querySelector('#create-form').reset();
                document.querySelector('#create-message-top').textContent = 'Select a deleted allowance to view its details';
                document.querySelector('#create-message').textContent = '';
            });

            document.querySelector('#okButton-create').addEventListener('click', function(event) {
                event.preventDefault();

                // Get the form values
                let name = document.querySelector('input[name="name"]').value;
                let type = document.querySelector('select[name="type"]').value;
                let baseAmt = document.querySelector('input[name="baseAmt"]').value;

                // Check if any of the specified fields are empty
                if (!name) {
                    // Display an error message
                    document.querySelector('#create-message').textContent = 'Name is required.';
                    return;
                }
                if (!baseAmt) {
                    // Display an error message
                    document.querySelector('#create-message').textContent = 'Base amount is required.';
                    return;
                }

                baseAmt = Number(baseAmt).toFixed(2);

                if (type === 'varied' && baseAmt > 0) {
                    // Display an error message
                    document.querySelector('#create-message').textContent = 'Base amount must be 0.00 for Varied type.';
                    return;
                }

                // Create a FormData object
                const formData = new FormData();
                formData.append('name', name);
                formData.append('type', type);
                formData.append('baseAmt', baseAmt);

                console.log(name);
                console.log(type);
                console.log(baseAmt);
                // Send the form data to the PHP script using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../scripts/create-allowance.php');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Parse the JSON response
                        const response = JSON.parse(xhr.responseText);

                        // Check if the database update was successful
                        if (response.success) {
                            // Clear the form fields
                            document.querySelector('#create-form').reset();

                            closeModal('createModal');
                            document.querySelector('#general-message').textContent = 'Create successful. Refresh the table to see changes.';

                        } else {
                            // Display the error message from the server script
                            document.querySelector('#create-message').textContent = response.error;
                        }
                    } else {
                        // handle error
                    }
                };
                xhr.send(formData);
            });

            // refresh functionalities
            document.querySelector('#refreshButton').addEventListener('click', function(event) {
                event.preventDefault();

                // Fetch updated data from server
                const xhr = new XMLHttpRequest();
                xhr.open('GET', '../scripts/fetch-get/fetch-allowances.php');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Parse the JSON response
                        const newData = JSON.parse(xhr.responseText);

                        // Extract the required fields from the newData and create a new array
                        const updatedData = newData.map(item => [
                            item.id,
                            item.name,
                            item.type,
                            item.base_amount,
                            '<i class="fas fa-edit"></i> | <i class="fas fa-trash"></i>'
                        ]);

                        // Get the current page index
                        const currentPage = table.page();

                        // Clear the DataTable and add the updated data
                        table.clear().rows.add(updatedData).draw();

                        // Go back to the current page
                        table.page(currentPage).draw('page');

                        document.querySelector('#general-message').textContent = 'Refreshed.';
                    } else {
                        // Handle error
                    }
                };
                xhr.send();
            });
        });
    </script>
</body>
</html>
