<?php 
    include '../scripts/connectdb.php'; 

    // Set the page name to a session variable
    $_SESSION['page'] = 'Employees';

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
        #selectedEmployeesTable {
            font-size: 12px;
            cursor: default;
        }
        #selectedEmployeesTable tr.selected {
            background-color: lightblue;
        }
    </style>
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>
    <main>
    <p style="text-align: right; font-style: italic; position: relative; bottom: 10px;">logged in as <?php echo $_SESSION['username']; ?></p>
        <h2>Employees</h2>
        <hr>
        <div style="border-radius: 5px; border: 2px groove white; padding: 10px;">
            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee No</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Gender</th>
                        <th>NRIC</th>
                        <th>Date Hired</th>
                        <th>Designation</th>
                        <th>Basic Salary $</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $result = $conn->query("SELECT e.*, d.name AS designation_name FROM employees e
                                                LEFT JOIN designations d ON e.designation = d.id");

                        // Output data in the tbody section of the table
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['employee_no'] . "</td>";
                            echo "<td>" . $row['firstname'] . "</td>";
                            echo "<td>" . $row['lastname'] . "</td>";
                            echo "<td>" . $row['gender'] . "</td>";
                            echo "<td>" . $row['nric'] . "</td>";
                            echo "<td>" . $row['date_hired'] . "</td>";
                            echo "<td>" . $row['designation_name'] . "</td>";
                            echo "<td>" . $row['basic_salary'] . "</td>";
                            echo "<td><i class='fas fa-edit'></i> | <i class='fas fa-trash'></i></td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div id="general-message" style="height: 25px; padding-left: 5px; font-style: italic; border-radius: 5px; border: 2px groove white;"></div>
        <div style="display: flex; justify-content: space-between; background-color: #d3dce3; padding: 10px; border-radius: 5px; border: 2px groove white;">
            <button id="clearSelectionButton" class="button-8" style="display: none;">Clear Selection</button>
            <button id="createButton" class="button-8">Create</button>
            <div>
                <button id="applyButton" class="button-8" style="display: none;">Assign Allowance</button>
                <button id="selectAllButton" class="button-8" style="display: none;">Select All</button>
                <button id="selectCurrentButton" class="button-8" style="display: none;">Select Current</button>
                <button id="cancelButton" class="button-8" style="display: none;">Cancel</button>
                <button id="makeSelectButton" class="button-8">Make Selectable</button>
                <button id="refreshButton" class="button-8">Refresh</button>
            </div>
        </div>
        <br><br><br>
        <?php include '../includes/log-section.php'; ?>
        <br><br><br>
  
        <div id="updateModal" class="modal">
            <div class="modal-content" style="width: 350px; margin: 5% auto;">
                <div class="modal-header">
                    <p>Update Employee</p>
                    <span class="close" onclick="closeModal('updateModal')">×</span>
                </div>
                <div class="modal-body">
                    <form id="update-form">
                        <input type="hidden" id="updateId">
                        <input type="hidden" id="originalSalary">

                        <fieldset>
                            <legend>Update Details</legend>
                            <table>
                                <tr>
                                    <td>Employee No</td>
                                    <td><input id="employeeNo" type="text" class="non-editable" readonly tabindex="-1"></td>
                                </tr>
                                <tr>
                                    <td>First Name</td>
                                    <td><input id="firstName" type="text" class="non-editable" readonly tabindex="-1"></td>
                                </tr>
                                <tr>
                                    <td>Last Name</td>
                                    <td><input id="lastName" type="text" class="non-editable" readonly tabindex="-1"></td>
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <td><input id="gender" type="text" class="non-editable" readonly tabindex="-1"></td>
                                </tr>
                                <tr>
                                    <td>NRIC</td>
                                    <td><input id="nric" type="text" class="non-editable" readonly tabindex="-1"></td>
                                </tr>
                                <tr>
                                    <td>Date Hired</td>
                                    <td><input id="dateHired" type="text" class="non-editable" readonly tabindex="-1"></td>
                                </tr>
                                <tr>
                                    <td>Designation</td>
                                    <td><input id="designation" type="text" class="non-editable" readonly tabindex="-1"></td>
                                </tr>
                                <tr>
                                    <td>Basic Salary</td>
                                    <td><input id="salary" type="number" style="height: 21px;" step="50"></td>
                                </tr>
                            </table>
                        </fieldset>
                        <hr>
                        <div style="display: flex; justify-content: space-between;">
                            <button type="button" id="allowancesButton">Allowances</button>
                            <div>
                                <button type="submit" id="okButton-update">OK</button>
                                <button type="button" onclick="closeModal('updateModal')">Cancel</button>
                            </div>
                        </div> 
                    </form>
                </div>  
            </div>
        </div>
        <div id="allowancesModal" class="modal">
            <div class="modal-content" style="width: 400px;">
                <div class="modal-header">
                    <p>View Allowances</p>
                    <span class="close" onclick="closeModal('allowancesModal')">×</span>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="updateId2">
                        <fieldset>
                            <legend>Allowance Details</legend>
                            <table id="allowanceDetailsTable">
                                <!-- contents will be generated here -->
                            </table>
                        </fieldset>
                        <hr>
                        <div style="display: flex; justify-content: space-between;">
                            <div></div>
                            <div>
                                <button type="submit" id="okButton-allowances">OK</button>
                                <button type="button" onclick="closeModal('allowancesModal')">Cancel</button>
                            </div>
                        </div> 
                    </form>
                </div>  
            </div>
        </div>
        <div id="deleteModal" class="modal">
            <div class="modal-content" style="width: 400px;">
                <div class="modal-header">
                    <p>Delete Employee</p>
                    <span class="close" onclick="closeModal('deleteModal')">×</span>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="deleteId">
                        <p>Are you sure you want to delete <span id="deleteEmployeeNo"></span>?</p>
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
            <div class="modal-content" style="width: 400px; margin: 5% auto;">
                <div class="modal-header">
                    <p>Create Employee</p>
                    <span class="close" onclick="closeModal('createModal')">×</span>
                </div>
                <div class="modal-body">
                    <form id="create-form">
                        <fieldset>
                            <legend>Personal Information</legend>
                            <table>
                                <tr>
                                    <td style="width: 150px;"><label>Employee No</label></td>
                                    <td><input type="text" name="employeeNo" class="non-editable" readonly tabindex="-1"></td>
                                </tr>
                                <tr>
                                    <td style="width: 150px;"><label>First Name</label><span style="color: red;">*</span></td>
                                    <td><input type="text" name="firstName"></td>
                                </tr>
                                <tr>
                                    <td style="width: 150px;"><label>Last Name<span style="color: red;">*</span></label></td>
                                    <td><input type="text" name="lastName"></td>
                                </tr>
                                <tr>
                                    <td style="width: 150px;"><label>Gender</label></td>
                                    <td style="display: flex; align-items: center;">
                                        <input type="radio" name="gender" value="male" style="margin-right: 5px;" checked><span style="margin-right: 10px;">Male</span>
                                        <input type="radio" name="gender" value="female" style="margin-right: 5px;"><span>Female</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 150px;"><label>NRIC</label><span style="color: red;">*</span> <span title="The general format is a letter (S, T, F, or G) followed by 7 digits and ending with a letter" style="cursor: help;">&#x1F6C8;</span></td>
                                    <td><input type="text" name="nric"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="display: flex; align-items: center; justify-content: flex-end;"><label style="margin-right: 5px;">Check NRIC duplicates</label><span title="Check with existing records on duplicates" style="cursor: help; margin-right: 5px;">&#x1F6C8;</span><input name="nricCheck" type="checkbox" checked></td>
                                </tr>
                            </table>
                        </fieldset>
                        <br>
                        <fieldset>
                            <legend>Employment Information</legend>
                            <table>
                                <tr>
                                    <td style="width: 150px;"><label>Designation</label></td>
                                    <td>
                                        <select name="designation">
                                            <?php
                                                // Assume the connection object is $conn
                                                // Query the database for available designations
                                                $result = $conn->query("SELECT name FROM designations");

                                                // Echo the options for the select box
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 150px;"><label>Basic Salary</label><span style="color: red;">*</span> <span title="Pre-allowance salary" style="cursor: help;">&#x1F6C8;</span></td>
                                    <td><input type="number" name="salary" style="height: 21px;" step="50" placeholder="0.00"></td>
                                </tr>
                                <tr>
                                    <td style="width: 150px;"><label>Date Hired</label><span style="color: red;">*</span></td>
                                    <td><input type="date" name="date" style="height: 24px;"></td>
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
        <div id="assignModal" class="modal">
            <div class="modal-content" style="width: 600px; margin: 10% auto;">
                <div class="modal-header">
                    <p>Assign Allowance</p>
                    <span class="close" onclick="closeModal('assignModal')">×</span>
                </div>
                <div class="modal-body">
                    <form id="assign-form">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 5px;">
                            <div style="background-color: white; max-height: 135px; overflow-x: auto; overflow-y: auto; border: 0.1px solid black;">
                                <table id="selectedEmployeesTable">
                                    <!-- contents will be inserted here -->
                                </table>
                            </div>
                            <fieldset>
                                <legend>Allowance Details</legend>
                                <table>
                                    <tr>
                                        <td style="width: 100px;"><label>Name</label> <span title="Allowance name" style="cursor: help;">&#x1F6C8;</span></td>
                                        <td>
                                            <select id="assignName" name="assignName">
                                                <?php 
                                                    $result = $conn->query("SELECT name FROM allowances");

                                                    // Echo the options for the select box
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100px;"><label>Type</label> <span title="Allowance type" style="cursor: help;">&#x1F6C8;</span></td>
                                        <td><input type="text" id="assignType" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100px;"><label>Base Amount</label></td>
                                        <td><input type="text" id="assignBaseAmt" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100px;"><label>Password</label><span style="color: red;">*</span></td>
                                        <td><input type="password" id="assignPassword"></td>
                                    </tr>
                                    <script>
                                        function updateAllowanceInfo(name) {
                                            let xhr = new XMLHttpRequest();
                                            xhr.open('POST', '../scripts/fetch-get/get-allowance-details.php');
                                            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                                            xhr.onload = function() {
                                                if (this.status == 200) {
                                                    let data = JSON.parse(this.responseText);
                                                    document.querySelector('#assignType').value = data.type;
                                                    document.querySelector('#assignBaseAmt').value = data.base_amount;
                                                }
                                            };
                                            xhr.send('name=' + name);
                                        }

                                        window.addEventListener('load', function() {
                                            let name = document.querySelector('#assignName').value;
                                            updateAllowanceInfo(name);
                                        });

                                        document.getElementById('assignName').addEventListener('change', function() {
                                            let name = this.value;
                                            updateAllowanceInfo(name);
                                        });
                                    </script>
                                </table>
                            </fieldset>
                        </div>
                        <p><span style="color: red;">*</span> indicates a mandatory field.</p>
                        <div id="assign-message" style="height: 20px; text-align: right; font-style: italic; color: red;"></div>
                        <hr style="margin-top: 0;">
                        <div style="display: flex; justify-content: space-between;">
                            <div></div>
                            <div>
                                <button type="submit" id="assignButton">Assign</button>
                                <button type="button" onclick="closeModal('assignModal')">Cancel</button>
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
                    const employeeNo = row.querySelector('td:nth-child(2)').textContent;
                    const firstName = row.querySelector('td:nth-child(3)').textContent;
                    const lastName = row.querySelector('td:nth-child(4)').textContent;
                    const gender = row.querySelector('td:nth-child(5)').textContent;
                    const nric = row.querySelector('td:nth-child(6)').textContent;
                    const dateHired = row.querySelector('td:nth-child(7)').textContent;
                    const designation = row.querySelector('td:nth-child(8)').textContent;
                    const salary = row.querySelector('td:nth-child(9)').textContent;

                    // Set the values in the modal fields
                    document.querySelector('#updateId').value = updateId;
                    document.querySelector('#employeeNo').value = employeeNo;
                    document.querySelector('#firstName').value = firstName;
                    document.querySelector('#lastName').value = lastName;
                    document.querySelector('#gender').value = gender;
                    document.querySelector('#nric').value = nric;
                    document.querySelector('#dateHired').value = dateHired;
                    document.querySelector('#designation').value = designation;
                    document.querySelector('#salary').value = salary;
                    document.querySelector('#originalSalary').value = salary;

                    // Open the modal window
                    document.querySelector("#updateModal").style.display = "block";

                } else if (event.target.classList.contains('fa-trash')) {
                    const row = event.target.closest('tr');
                    const deleteId = row.querySelector('td:first-child').textContent;
                    const employeeNo = row.querySelector('td:nth-child(2)').textContent;

                    document.querySelector('#deleteId').value = deleteId;
                    document.querySelector('#deleteEmployeeNo').textContent = employeeNo;

                    document.querySelector('#deleteModal').style.display = "block";
                }
            });

            // update functionalities
            document.querySelector('#okButton-update').addEventListener('click', function(event) {
                event.preventDefault();

                // Get the form values
                let updateId = document.querySelector('#updateId').value;
                let salary = document.querySelector('#salary').value;  
                let originalSalary = document.querySelector('#originalSalary').value;
                let employeeNo = document.querySelector('#employeeNo').value;

                salary = Number(salary).toFixed(2);
                originalSalary = Number(originalSalary).toFixed(2);

                if (salary === originalSalary) {
                    closeModal('updateModal');
                    return;
                } 
                    
                // Create a FormData object
                const formData = new FormData();
                formData.append('id', updateId);
                formData.append('salary', salary);
                formData.append('originalSalary', originalSalary);
                formData.append('employeeNo', employeeNo);

                // Send the form data to update-resignation.php using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../scripts/update-employee.php');
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

            document.querySelector('#allowancesButton').addEventListener('click', function(event) {
                event.preventDefault();

                // Get the table element
                const updateId = document.querySelector('#updateId').value;
                document.querySelector('#updateId2').value = updateId;
                const allowanceDetailsTable = document.querySelector('#allowanceDetailsTable');
                allowanceDetailsTable.innerHTML = '';

                const xhr = new XMLHttpRequest();

                // Open a new POST request to the server
                xhr.open('POST', '../scripts/fetch-get/get-employee-allowances.php');
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                // Add an event listener for when the request is loaded
                xhr.onload = function() {
                    // Check if the request was successful
                    if (this.status == 200) {
                        // Parse the response data
                        const data = JSON.parse(this.responseText);

                        if (data.length === 0) {
                            // No data in the response
                            let row = allowanceDetailsTable.insertRow(-1);
                            let cell = row.insertCell(0);
                            cell.colSpan = 2;
                            cell.textContent = "No allowances associated with this employee";
                        } else {
                            // Loop through the data and create a new row for each entry
                            data.forEach(entry => {
                                const row = document.createElement('tr');
                                let inputAttributes = '';
                                
                                // Set input attributes if type is 'Fixed'
                                if (entry.type === 'Fixed') {
                                    inputAttributes = 'class="non-editable" readonly tabindex="-1"';
                                }
                                
                                row.innerHTML = `
                                    <td><label>${entry.name}</label></td>
                                    <td><input type="number" value="${entry.amount}" ${inputAttributes} step="50"></td>
                                `;
                                allowanceDetailsTable.appendChild(row);
                            });
                        }
                    }
                };

                // Send the request to the server with the updateId value as a POST parameter
                xhr.send(`updateId=${updateId}`);

                document.getElementById('allowancesModal').style.display = 'block';
            });

            document.querySelector('#okButton-allowances').addEventListener('click', function(event) {
                event.preventDefault();
                
                // Get updateId2 value
                const updateId2 = document.querySelector('#updateId2').value;
                
                // Get allowance details
                const allowanceDetails = [];
                let noChanges = true;
                const allowanceRows = document.querySelectorAll('#allowanceDetailsTable tr');

                allowanceRows.forEach(row => {
                    const label = row.querySelector('label');
                    const input = row.querySelector('input');
                    
                    if (label && input) {
                        const name = label.textContent;
                        const amount = input.value;
                        
                        // Set noChanges to false if amount has changed
                        if (Number(amount) !== Number(input.defaultValue)) {
                            noChanges = false;
                        }
                        
                        allowanceDetails.push({ name, amount });
                    }
                });

                // Close modal and return if no changes were made
                if (noChanges) {
                    closeModal('allowancesModal');
                    return;
                }
                
                // Send data to server
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../scripts/update-employee-allowances.php');
                xhr.setRequestHeader('Content-type', 'application/json');

                // Add an event listener for when the request is loaded
                xhr.addEventListener('load', function() {
                    if (this.status === 200) {
                        // Parse the response data
                        const data = JSON.parse(this.responseText);
                        
                        // Check if the update was successful
                        if (data.success) {
                            closeModal('allowancesModal');
                        } else {
                            // handle
                        }
                    }
                });

                xhr.send(JSON.stringify({updateId2, allowanceDetails}));
            });

            // delete functionalities
            document.querySelector('#confirmButton').addEventListener('click', function(event) {
                event.preventDefault();

                // Get the form values
                let deleteId = document.querySelector('#deleteId').value;
                let deleteEmployeeNo = document.querySelector('#deleteEmployeeNo').textContent;

                // Create a FormData object
                const formData = new FormData();
                formData.append('id', deleteId);
                formData.append('deleteEmployeeNo', deleteEmployeeNo);

                console.log(deleteId);
                console.log(deleteEmployeeNo);

                // Send the form data to update-resignation.php using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../scripts/delete-employee.php');
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

                let xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.querySelector("input[name='employeeNo']").value = this.responseText;
            
                        document.querySelector("#createModal").style.display = "block";
                    }
                };
                xhr.open("GET", "../scripts/fetch-get/getEmployeeNo.php", true);
                xhr.send();
            });

            document.querySelector('#resetButton').addEventListener('click', function(event) {
                document.querySelector('#create-form').reset();
                document.querySelector('#create-message').textContent = '';

                let xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.querySelector("input[name='employeeNo']").value = this.responseText;
                    }
                };
                xhr.open("GET", "../scripts/fetch-get/getEmployeeNo.php", true);
                xhr.send();
            });

            document.querySelector('#okButton-create').addEventListener('click', function(event) {
                event.preventDefault();

                // Get the form values
                let employeeNo = document.querySelector('input[name="employeeNo"]').value;
                let firstName = document.querySelector('input[name="firstName"]').value;
                let lastName = document.querySelector('input[name="lastName"]').value;
                let gender = document.querySelector('input[name="gender"]:checked').value;
                let nric = document.querySelector('input[name="nric"]').value;
                let date = document.querySelector('input[name="date"]').value;
                let designation = document.querySelector('select[name="designation"]').value;
                let salary = document.querySelector('input[name="salary"]').value;
                let nricCheck = document.querySelector('input[name="nricCheck"]').checked;

                // Check if any of the specified fields are empty
                if (!firstName) {
                    document.querySelector('#create-message').textContent = 'First name is required.';
                    return;
                }
                if (!lastName) {
                    document.querySelector('#create-message').textContent = 'Last name is required.';
                    return;
                }
                if (!nric) {
                    document.querySelector('#create-message').textContent = 'NRIC is required.';
                    return;
                }
                if (!date) {
                    document.querySelector('#create-message').textContent = 'Date is required.';
                    return;
                }
                if (!salary) {
                    document.querySelector('#create-message').textContent = 'Salary is required.';
                    return;
                }

                // Check if the NRIC is in a valid format
                const regex = /^[STFG]\d{7}[A-Z]$/;
                if (!regex.test(nric)) {
                    document.querySelector('#create-message').textContent = 'Invalid NRIC format.';
                    return;
                }

                salary = Number(salary).toFixed(2);
                
                // Create a FormData object
                const formData = new FormData();
                formData.append('employeeNo', employeeNo);
                formData.append('firstName', firstName);
                formData.append('lastName', lastName);
                formData.append('gender', gender);
                formData.append('nric', nric);
                formData.append('date', date);
                formData.append('designation', designation);
                formData.append('salary', salary);
                formData.append('nricCheck', nricCheck);

                // Send the form data to the PHP script using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../scripts/create-employee.php'); 
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

                // Fetch updated data from the server
                const xhr = new XMLHttpRequest();
                xhr.open('GET', '../scripts/fetch-get/fetch-employees.php');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Parse the JSON response
                        const newData = JSON.parse(xhr.responseText);

                        // Extract the required fields from the newData and create a new array
                        const updatedData = newData.map(item => [
                            item.id,
                            item.employee_no,
                            item.firstname,
                            item.lastname,
                            item.gender,
                            item.nric,
                            item.date_hired,
                            item.designation_name,
                            item.basic_salary,
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            
            // selection functionalities
            function updateSelectedEmployeesTable() {
                // Get the selectedEmployeesTable element
                const selectedEmployeesTable = document.querySelector('#selectedEmployeesTable');

                // Clear the table
                selectedEmployeesTable.textContent = '';

                // Get all rows in the datatable (across all pages)
                let rows = $('#example').DataTable().rows().nodes();

                // Loop through each row
                rows.each(function(row) {
                    // Get the checkbox in this row
                    let checkbox = row.querySelector('input[type="checkbox"]');

                    // Check if the checkbox is checked
                    if (checkbox.checked) {
                        // Create a new row in the selectedEmployeesTable
                        const newRow = selectedEmployeesTable.insertRow(-1);

                        for (let i = 1; i <= 3; i++) {
                            // Create a new cell in the newRow and set its text content to the text content of the corresponding cell in the row
                            const newCell = newRow.insertCell(i - 1);
                            newCell.textContent = row.cells[i].textContent;
                        }
                    }
                });
            }

            function updateSelectionCount(checkbox, row) {
                // Get the row's ID
                let rowId = row.querySelector('td:first-child').textContent;
                // Check if the checkbox is checked or unchecked
                if (checkbox.checked) {
                    // If it's checked, add the row's ID to the checkedRows array (if it's not already there)
                    if (!checkedRows.includes(rowId)) {
                        checkedRows.push(rowId);
                    }
                } else {
                    // If it's unchecked, remove the row's ID from the checkedRows array (if it's there)
                    let index = checkedRows.indexOf(rowId);
                    if (index > -1) {
                        checkedRows.splice(index, 1);
                    }
                }
                // Update the text in the #general-message div
                document.querySelector('#general-message').textContent = checkedRows.length + ' rows selected';

                // Update the selectedEmployeesTable
                updateSelectedEmployeesTable();
            }

            let checkedRows = [];

            document.querySelector('#makeSelectButton').addEventListener('click', function() {
                // Hide the #makeSelectButton, #createButton, and #refreshButton
                this.style.display = 'none';
                document.querySelector('#createButton').style.display = 'none';
                document.querySelector('#refreshButton').style.display = 'none';
                // Show the #selectAllButton, #selectCurrentButton, #clearSelectionButton, and #cancelButton
                document.querySelector('#applyButton').style.display = 'inline-block';
                document.querySelector('#selectAllButton').style.display = 'inline-block';
                document.querySelector('#selectCurrentButton').style.display = 'inline-block';
                document.querySelector('#clearSelectionButton').style.display = 'inline-block';
                document.querySelector('#cancelButton').style.display = 'inline-block';
                // Remove the "Action" word from the table header
                document.querySelector('#example thead th:last-child').textContent = '';
                // Get all rows in the table (across all pages)
                let rows = $('#example').DataTable().rows().nodes();
                // Loop through each row
                rows.each(function(row) {
                    // Get the action cell in this row
                    let cell = row.querySelector('td:last-child');
                    // Create a new checkbox element
                    let checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    // Add an event listener to the checkbox to update the selection count when it changes
                    checkbox.addEventListener('change', function() {
                        updateSelectionCount(this, row);
                    });
                    // Replace the cell's content with the checkbox
                    cell.textContent = '';
                    cell.appendChild(checkbox);
                });
            });

            document.querySelector('#selectAllButton').addEventListener('click', function() {
                // Get all rows in the table (across all pages)
                let rows = $('#example').DataTable().rows().nodes();
                // Loop through each row
                rows.each(function(row) {
                    // Get the checkbox in this row
                    let checkbox = row.querySelector('input[type="checkbox"]');
                    // Check the checkbox
                    checkbox.checked = true;
                    // Update the selection count
                    updateSelectionCount(checkbox, row);
                });
            });

            document.querySelector('#selectCurrentButton').addEventListener('click', function() {
                // Get all rows in the current page of the table
                let rows = $('#example').DataTable().rows({page: 'current'}).nodes();
                // Loop through each row
                rows.each(function(row) {
                    // Get the checkbox in this row
                    let checkbox = row.querySelector('input[type="checkbox"]');
                    // Check the checkbox
                    checkbox.checked = true;
                    // Update the selection count
                    updateSelectionCount(checkbox, row);
                });
            });

            document.querySelector('#clearSelectionButton').addEventListener('click', function() {
                // Clear the checkedRows array
                checkedRows = [];
                // Update the text in the #general-message div
                document.querySelector('#general-message').textContent = checkedRows.length + ' rows selected';
                // Get all rows in the table (across all pages)
                let rows = $('#example').DataTable().rows().nodes();
                // Loop through each row
                rows.each(function(row) {
                    // Get the checkbox in this row
                    let checkbox = row.querySelector('input[type="checkbox"]');
                    // Uncheck the checkbox
                    checkbox.checked = false;
                });
            });

            document.querySelector('#cancelButton').addEventListener('click', function() {
                // Show the #makeSelectButton, #createButton, and #refreshButton
                document.querySelector('#makeSelectButton').style.display = 'inline-block';
                document.querySelector('#createButton').style.display = 'inline-block';
                document.querySelector('#refreshButton').style.display = 'inline-block';
                // Hide the #selectAllButton, #selectCurrentButton, #clearSelectionButton, and #cancelButton
                document.querySelector('#applyButton').style.display = 'none';
                document.querySelector('#selectAllButton').style.display = 'none';
                document.querySelector('#selectCurrentButton').style.display = 'none';
                document.querySelector('#clearSelectionButton').style.display = 'none';
                this.style.display = 'none';
                
                document.querySelector('#example thead th:last-child').textContent = 'Action';
                // Get all rows in the table (across all pages)
                let rows = $('#example').DataTable().rows().nodes();
                // Loop through each row
                rows.each(function(row) {
                    // Get the action cell in this row
                    let cell = row.querySelector('td:last-child');
                    // Replace the cell's content with action icons
                    cell.innerHTML = "<i class='fas fa-edit'></i> | <i class='fas fa-trash'></i>";
                });

                document.querySelector('#general-message').textContent = 'Selection mode exited';
            });            

            // assign functionalities
            document.querySelector('#applyButton').addEventListener('click', function(event) {
                // Get all the checkboxes in the datatable
                const checkboxes = document.querySelectorAll('#example tbody input[type="checkbox"]');

                // Check if any of the checkboxes are checked
                let anyChecked = false;
                for (let i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                        anyChecked = true;
                        break;
                    }
                }

                if (anyChecked) {
                    // Open the modal window
                    document.querySelector("#assignModal").style.display = "block";
                } else {
                    // Display an alert message
                    
                }
            });

            document.getElementById('assignButton').addEventListener('click', function(event) {
                // Prevent the form from submitting
                event.preventDefault();

                let assignPassword = document.querySelector('#assignPassword').value;

                // Send a request to the server to verify the password
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '../scripts/verify-password.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Parse the JSON response
                        let response = JSON.parse(xhr.responseText);

                        if (response.success) {
                            // Password is correct

                            // Get the selected employee numbers
                            let employeeNos = [];
                            let rows = document.querySelectorAll('#selectedEmployeesTable tr');
                            for (let i = 0; i < rows.length; i++) {
                                let row = rows[i];
                                let employeeNo = row.querySelector('td:first-child').textContent;
                                employeeNos.push(employeeNo);
                            }

                            // Get the form values
                            let assignName = document.querySelector('#assignName').value;
                            let assignType = document.querySelector('#assignType').value;
                            let assignBaseAmt = document.querySelector('#assignBaseAmt').value;

                            assignBaseAmt = Number(assignBaseAmt).toFixed(2);

                            // Send the data to PHP
                            let xhr2 = new XMLHttpRequest();
                            xhr2.open('POST', '../scripts/assign-allowance.php');
                            xhr2.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                            xhr2.onload = function() {
                                if (this.status == 200) {
                                    
                                    document.querySelector('#assignPassword').value = '';
                                    document.querySelector('#assign-message').textContent = '';
                                    // Handle the response here
                                    closeModal('assignModal');
                                    document.querySelector('#general-message').textContent = 'Assign allowance successful.';

                                }
                            };
                            xhr2.send(`employeeNos=${JSON.stringify(employeeNos)}&assignName=${assignName}&assignType=${assignType}&assignBaseAmt=${assignBaseAmt}`);
                        } else {
                            // Password is incorrect
                            document.querySelector('#assign-message').textContent = 'Incorrect password.';
                        }
                    }
                };
                xhr.send(`password=${assignPassword}`);
            });
        });
    </script>
</body>
</html>
