<?php 
    include '../scripts/connectdb.php'; 

    // Set the page name to a session variable
    $_SESSION['page'] = 'Dashboard';

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
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>
    <main>
        <p style="text-align: right; font-style: italic; position: relative; bottom: 10px;">logged in as <?php echo $_SESSION['username']; ?></p>
        <h2>Designations</h2>
        <hr>
        <div style="border-radius: 5px; border: 2px groove white; padding: 10px;">
            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Department Code</th>
                        <th>Name</th>
                        <th>Bonus Eligibility</th>
                        <th>Overtime Rate $</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $result = $conn->query("SELECT * FROM designations");

                        // Output data in the tbody section of the table
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['department_code'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . ($row['bonus_eligible'] ? 'Yes' : 'No') . "</td>";
                            echo "<td>" . number_format($row['overtime_rate'], 2) . "</td>";
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
            <button id="refreshButton" class="button-8">Refresh</button>
        </div>
        <br><br><br>
        <?php include '../includes/log-section.php'; ?>
        <br><br><br>

        <div id="updateModal" class="modal">
            <div class="modal-content" style="width: 325px; margin: 10% auto;">
                <div class="modal-header">
                    <p>Update Designation</p>
                    <span class="close" onclick="closeModal('updateModal')">×</span>
                </div>
                <div class="modal-body">
                    <form id="update-form">
                        <input type="hidden" id="updateId">
                        <input type="hidden" id="originalBonus">
                        <input type="hidden" id="originalOvertime">
                        <fieldset>
                            <legend>Update Details</legend>
                            <table>
                                <tr>
                                    <td><label>Department Code</label></td>
                                    <td><input id="deptCode" type="text" class="non-editable" readonly tabindex="-1"></td>
                                </tr>
                                <tr>
                                    <td><label>Name</label></td>
                                    <td><input id="name" type="text" class="non-editable" readonly tabindex="-1"></td>
                                </tr>
                                <tr>
                                    <td><label>Bonus Eligibility</label></td>
                                    <td>
                                        <select id="bonus">
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Overtime Rate</label></td>
                                    <td>
                                        <select id="overtime">
                                            <option value="30.00">30.00</option>
                                            <option value="35.00">35.00</option>
                                            <option value="40.00">40.00</option>
                                            <option value="45.00">45.00</option>
                                            <option value="50.00">50.00</option>
                                        </select>
                                    </td>
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
            <div class="modal-content" style="width: 400px;">
                <div class="modal-header">
                    <p>Delete Designation</p>
                    <span class="close" onclick="closeModal('deleteModal')">×</span>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="deleteId">
                        <p>Are you sure you want to delete <span id="deleteName"></span>?</p>
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
            <div class="modal-content" style="width: 450px; margin: 1% auto;">
                <div class="modal-header">
                    <p>Create Designation</p>
                    <span class="close" onclick="closeModal('createModal')">×</span>
                </div>
                <div class="modal-body">
                    <form id="create-form">
                        <fieldset>
                            <legend>Designation Details</legend>
                            <table>
                                <tr>
                                    <td style="width: 150px;"><label>Department Code</label></td>
                                    <td>
                                        <select name="deptCode">
                                            <option value="AFD">AFD</option>
                                            <option value="ENG">ENG</option>
                                            <option value="HR">HR</option>
                                            <option value="ITD">ITD</option>
                                            <option value="MKGT">MKGT</option>
                                            <option value="RD">RD</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 150px;"><label>Name</label><span style="color: red;">*</span> <span title="Designation name" style="cursor: help;">&#x1F6C8;</span></td>
                                    <td><input type="text" name="name"></td>
                                </tr>
                            </table>
                        </fieldset>
                        <br>
                        <fieldset>
                            <legend>Compensation Details</legend>
                            <table>
                                <tr>
                                    <td style="width: 150px;"><label>Bonus Eligibility</label> <span title="Whether the designation is eligible for a bonus" style="cursor: help;">&#x1F6C8;</span></td>
                                    <td style="display: flex; align-items: center;">
                                        <input type="radio" name="bonus" value="yes" style="margin-right: 5px;" checked><span style="margin-right: 10px;">Yes</span>
                                        <input type="radio" name="bonus" value="no" style="margin-right: 5px;"><span>No</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 150px;">
                                        <label>Overtime Rate</label>
                                    </td>
                                    <td>
                                        <select name="overtime">
                                            <option value="30.00">30.00</option>
                                            <option value="35.00">35.00</option>
                                            <option value="40.00">40.00</option>
                                            <option value="45.00">45.00</option>
                                            <option value="50.00">50.00</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                        <br>
                        <fieldset>
                            <legend>Miscellaneous</legend>
                            <table>
                                <tr>
                                    <td rowspan="5" style="vertical-align: top; width: 150px;"><label>Work Arrangement</label></td>
                                    <td style="display: flex; align-items: center;"><input type="radio" name="remoteWork" value="officeBasedOnly" checked><span style="margin-left: 5px;">Office-based only</span></td>
                                </tr>
                                <tr>
                                    <td style="display: flex; align-items: center;"><input type="radio" name="remoteWork" value="partiallyRemote"><span style="margin-left: 5px;">Partially remote</span></td>
                                </tr>
                                <tr>
                                    <td style="display: flex; align-items: center;"><input type="radio" name="remoteWork" value="fullyRemote"><span style="margin-left: 5px;">Fully remote</span></td>
                                </tr>
                                <tr>
                                    <td style="display: flex; align-items: center;"><input type="radio" name="remoteWork" value="customOption"><span style="margin-left: 5px;">Others</span></td>
                                </tr>
                            </table>
                            <table>
                                <tr>
                                    <td style="vertical-align: top; width: 100px;"><label>Descriptions</label> <span title="A description of the designation" style="cursor: help;">&#x1F6C8;</span></td>
                                    <td><textarea name="descriptions" placeholder="Enter some descriptions here (optional)" cols="30" rows="2"></textarea></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: top; width: 100px;"><label>Remarks</label> <span title="Additional remarks about the designation" style="cursor: help;">&#x1F6C8;</span></td>
                                    <td><textarea name="remarks" placeholder="Enter some remarks here (optional)" cols="30" rows="2"></textarea></td>
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
                    const deptCode = row.querySelector('td:nth-child(2)').textContent;
                    const name = row.querySelector('td:nth-child(3)').textContent;
                    const bonus = row.querySelector('td:nth-child(4)').textContent;
                    const overtime = row.querySelector('td:nth-child(5)').textContent;

                    // Set the values in the modal fields
                    document.querySelector('#updateId').value = updateId;
                    document.querySelector('#deptCode').value = deptCode;
                    document.querySelector('#name').value = name;
                    document.querySelector('#bonus').value = bonus.toLowerCase();
                    document.querySelector('#originalBonus').value = bonus.toLowerCase();
                    document.querySelector('#overtime').value = overtime;
                    document.querySelector('#originalOvertime').value = overtime;

                    // Open the modal window
                    document.querySelector("#updateModal").style.display = "block";

                } else if (event.target.classList.contains('fa-trash')) {
                    const row = event.target.closest('tr');
                    const deleteId = row.querySelector('td:first-child').textContent;
                    const name = row.querySelector('td:nth-child(3)').textContent;

                    document.querySelector('#deleteId').value = deleteId;
                    document.querySelector('#deleteName').textContent = name;

                    document.querySelector('#deleteModal').style.display = "block";
                }
            });

            // update functionalities
            document.querySelector('#okButton-update').addEventListener('click', function(event) {
                event.preventDefault();

                // Get the form values
                let updateId = document.querySelector('#updateId').value;
                let bonus = document.querySelector('#bonus').value;
                let overtime = document.querySelector('#overtime').value;
                let originalBonus = document.querySelector('#originalBonus').value;
                let originalOvertime = document.querySelector('#originalOvertime').value;
                let name = document.querySelector('#name').value;

                if (bonus == originalBonus && overtime == originalOvertime) {
                    closeModal('updateModal');
                    return;
                } 

                // Create a FormData object
                const formData = new FormData();
                formData.append('id', updateId);
                formData.append('bonus', bonus);
                formData.append('originalBonus', originalBonus);
                formData.append('overtime', overtime);
                formData.append('originalOvertime', originalOvertime);
                formData.append('name', name);

                // Send the form data to update-resignation.php using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../scripts/update-designation.php');
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

                // Create a FormData object
                const formData = new FormData();
                formData.append('id', deleteId);
                formData.append('deleteName', deleteName);

                // Send the form data to update-resignation.php using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../scripts/delete-designation.php');
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
                document.querySelector('#create-message').textContent = '';
            });

            document.querySelector('#okButton-create').addEventListener('click', function(event) {
                event.preventDefault();

                // Get the form values
                const deptCode = document.querySelector('select[name="deptCode"]').value;
                const name = document.querySelector('input[name="name"]').value;
                const bonus = document.querySelector('input[name="bonus"]:checked').value;
                const overtime = document.querySelector('select[name="overtime"]').value;

                // Check if any of the specified fields are empty
                if (!name) {
                    // Display an error message
                    document.querySelector('#create-message').textContent = 'Name is required.';
                    return;
                }

                // Create a FormData object
                const formData = new FormData();
                formData.append('deptCode', deptCode);
                formData.append('name', name);
                formData.append('bonus', bonus);
                formData.append('overtime', overtime);

                // // Send the form data to the PHP script using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../scripts/create-designation.php');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Parse the JSON response
                        const response = JSON.parse(xhr.responseText);

                        // Check if the database update was successful
                        if (response.success) {
                            // Clear the form fields
                            document.querySelector('#create-form').reset();

                            closeModal('createModal');
                            // Display a success message
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
                xhr.open('GET', '../scripts/fetch-get/fetch-designations.php');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Parse the JSON response
                        const newData = JSON.parse(xhr.responseText);

                        // Extract the required fields from the newData and create a new array
                        const updatedData = newData.map(item => [
                            item.id,
                            item.department_code,
                            item.name,
                            item.bonus_eligible ? 'Yes' : 'No',
                            item.overtime_rate,
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
