<?php 
    include '../scripts/connectdb.php'; 

    // Set the page name to a session variable
    $_SESSION['page'] = 'Payroll';

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
        #payrollListTable {
            cursor: default;
        }
        #payrollListTable tr td {
            padding: 5px;
        }
        #payrollListTable tr.selected {
            background-color: lightblue;
        }
    </style>
    <style>
        #payrollReviewTable tr td {
            padding: 2px;
            border: 0.5px solid black;
            width: 25%;
            padding-left: 5px;
            padding-right: 5px;
        }
    </style>
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>
    <main>
    <p style="text-align: right; font-style: italic; position: relative; bottom: 10px;">logged in as <?php echo $_SESSION['username']; ?></p>
        <h2>Payroll</h2>
        <hr>
        <div style="border-radius: 5px; border: 2px groove white; padding: 10px;">
            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reference Number</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $result = $conn->query("SELECT * FROM payroll");

                        // Output data in the tbody section of the table
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['reference_no'] . "</td>";
                            echo "<td>" . $row['payroll_period'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "<td><i class='fas fa-edit'></i></td>";
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
        <h4>Review Payroll</h4>
        <hr>
        <div id="review-payroll" style="display: grid; grid-template-columns: 200px auto; gap: 20px; border-radius: 5px; border: 2px groove white; padding: 10px;">
            <div id="time" style="grid-column: span 2;"></div>
            <div>
                <label>Month:</label>
                <input type="month" style="height: 25px; margin-bottom: 5px;">
                <label>Status:</label>
                <select style="height: 25px;">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="All">All</option>
                </select>
                <br><br>
                <div style="max-height: 270px; overflow-x: auto; overflow-y: auto; border: 0.1px solid black;">
                    <table id="payrollListTable" style="width: 100%;">
                        <!-- contents will be inserted here -->
                    </table>
                </div>
            </div>
            <div>
                <table id="payrollReviewTable" style="width: 100%;">
                    <tr>
                        <td colspan="4" style="text-align: center; background-color: #1f2731; color: white; font-weight: bold;">Payroll Summary Report</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="background-color: #d3d6e0;">Employee No</td>
                        <td id="payroll-employeeNo"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="background-color: #d3d6e0;">Reference No</td>
                        <td id="payroll-referenceNo"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="background-color: #d3d6e0;">Period</td>
                        <td id="payroll-period"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="background-color: #d3d6e0;">Status</td>
                        <td id="payroll-status" style="font-style: italic;"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="background-color: #d3d6e0;">Basic Salary</td>
                        <td>
                            <span style="float: left;">$</span>
                            <span id="payroll-basic" style="float: right;">0.00</span>
                        </td>
                    </tr>
                    <tr style="background-color: #2b3648; color: white; font-weight: bold;">
                        <td colspan="3">Earnings</td>
                        <td>
                            <span style="float: left;">$</span>
                            <span id="payroll-earnings" style="float: right;">0.00</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right; background-color: #d3d6e0;">Bonus</td>
                        <td>
                            <span style="float: left;">$</span>
                            <span id="payroll-bonus" style="float: right;">0.00</span>
                        </td>
                        <td rowspan="3" style="background-color: #f1f1f0;"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right; background-color: #d3d6e0;">Overtime</td>
                        <td>
                            <span style="float: left;">$</span>
                            <span id="payroll-overtime" style="float: right;">0.00</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right; background-color: #d3d6e0;">Allowance</td>
                        <td>
                            <span style="float: left;">$</span>
                            <span id="payroll-allowance" style="float: right;">0.00</span>
                        </td>
                    </tr>
                    <tr style="background-color: #2b3648; color: white; font-weight: bold;">
                        <td colspan="3">Deductions</td>
                        <td>
                            <span style="float: left;">$</span>
                            <span id="payroll-deductions" style="float: right;">0.00</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right; background-color: #d3d6e0;">Income Tax</td>
                        <td>
                            <span style="float: left;">$</span>
                            <span id="payroll-incomeTax" style="float: right;">0.00</span>
                        </td>
                        <td rowspan="2" style="background-color: #f1f1f0;"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right; background-color: #d3d6e0;">Social Security</td>
                        <td>
                            <span style="float: left;">$</span>
                            <span id="payroll-socialSecurity" style="float: right;">0.00</span>
                        </td>
                    </tr>
                    <tr style="background-color: #2b3648; color: white; font-weight: bold;">
                        <td colspan="3" style="text-align: right;">Net Pay</td>
                        <td>
                            <span style="float: left;">$</span>
                            <span id="payroll-netPay" style="float: right;">0.00</span>
                        </td>
                    </tr>
                </table>
                <p style="font-style: italic; color: red; text-align: justify;">Note: Any pending payroll must be ensured that the relevant values in the cells are accurately filled in, reflecting the employee's financial details, before submitting approval.</p>
            </div>
        </div>
        <div id="general-message2" style="height: 25px; padding-left: 5px; font-style: italic; border-radius: 5px; border: 2px groove white;"></div>
        <div style="display: flex; justify-content: space-between; background-color: #d3dce3; padding: 10px; border-radius: 5px; border: 2px groove white;">
            <button id="clearReportButton" class="button-8">Clear Report</button>
            <div>
                <button id="printButton" class="button-8" onclick = "generatePaySlip()">Print</button>
                <button id="approveButton" class="button-8">Approve</button>
            </div>
        </div>
        <script>
            function generatePaySlip() {
                // Retrieve the necessary data from the review section
                const referenceNo = document.getElementById('payroll-referenceNo').textContent;

                // Redirect to the payslip.php page with referenceNo as a URL parameter
                window.location.href = 'payslip.php?referenceNo=' + encodeURIComponent(referenceNo);
            }
        </script>
        <br><br><br>
        
        <div id="updateModal" class="modal">
            <div class="modal-content" style="width: 325px;">
                <div class="modal-header">
                    <p>Update Payroll</p>
                    <span class="close" onclick="closeModal('updateModal')">×</span>
                </div>
                <div class="modal-body">
                    <form id="update-form">
                        <input type="hidden" id="updateId">
                        <input type="hidden" id="originalPeriod">
                        <fieldset>
                            <legend>Update Details</legend>
                            <table>
                                <tr>
                                    <td><label>Reference No</label></td>
                                    <td><input id="referenceNo" type="text" class="non-editable" readonly tabindex="-1"></td>
                                </tr>
                                <tr>
                                    <td><label>Period</label> <span title="Period for the payroll" style="cursor: help;">&#x1F6C8;</span></td>
                                    <td><input id="period" type="month" style="height: 21px;"></td>
                                </tr>
                                <tr>
                                    <td><label>Status</label> <span title="Approval status of the payroll. Pending or approved" style="cursor: help;">&#x1F6C8;</span></td>
                                    <td><input id="status" type="text" class="non-editable" readonly tabindex="-1"></td>
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
        <div id="createModal" class="modal">
            <div class="modal-content" style="width: 800px; margin: 0% auto;">
                <div class="modal-header">
                    <p>Create Payroll</p>
                    <span class="close" onclick="closeModal('createModal')">×</span>
                </div>
                <div class="modal-body">
                    <?php
                        // Query to count the number of employees
                        $sql = "SELECT COUNT(*) AS employee_count FROM employees";

                        // Execute the query
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Fetch the count from the result
                            $row = $result->fetch_assoc();
                            $employeeCount = $row["employee_count"];
                        } else {
                            $employeeCount = 0;
                        }
                    ?>
                    <form id="create-form">
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 5px;">
                            <fieldset style="grid-column: span 2;">
                                <legend>Payroll Details</legend>
                                <table>
                                    <tr>
                                        <td style="width: 150px;"><label>Reference No</label></td>
                                        <td><input type="text" name="referenceNo" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Payroll Period</label><span style="color: red;">*</span> <span title="The period for which the payroll is being processed. Enter in YYYY-MM format." style="cursor: help;">&#x1F6C8;</span></td>
                                        <td><input type="month" name="payrollPeriod" style="height: 21px;"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Employee No</label><span style="color: red;">*</span></td>
                                        <td><input type="text" name="employeeNo"></td>
                                    </tr>
                                </table>
                                <br>
                                <div style="display: flex; justify-content: space-between;">
                                    <button id="generateButton" style="width: 150px;">Generate Details</button>
                                    <div id="generate-message" style="height: 20px; text-align: right; font-style: italic; color: red;"></div>
                                </div>
                            </fieldset>
                            <fieldset style="grid-column: span 2; grid-row: span 2;">
                                <legend>Employer Details</legend>
                                <table>
                                    <tr>
                                        <td style="width: 150px;"><label>Company Name</label></td>
                                        <td><input type="text" name="companyName" value="ABC Corporation" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Address</label></td>
                                        <td><input type="text" name="companyAddress" value="123 Main Street" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Contact Number</label></td>
                                        <td><input type="text" name="companyContact" value="+1 123-456-7890" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Email</label></td>
                                        <td><input type="text" name="companyEmail" value="info@abccorp.com" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Industry</label></td>
                                        <td><input type="text" name="industry" value="Technology" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Number of Employees</label></td>
                                        <td><input type="text" name="employeeCount" value="<?php echo $employeeCount; ?>" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                </table>
                            </fieldset>
                            <fieldset style="grid-column: span 2; grid-row: span 2;">
                                <legend>Employee Details</legend>
                                <table>
                                    <tr>
                                        <td style="width: 150px;"><label>First Name</label></td>
                                        <td><input type="text" name="firstName" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Last Name</label></td>
                                        <td><input type="text" name="lastName" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Gender</label></td>
                                        <td><input type="text" name="gender" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>NRIC</label></td>
                                        <td><input type="text" name="nric" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Date Hired</label></td>
                                        <td><input type="text" name="dateHired" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Designation</label></td>
                                        <td><input type="text" name="designation" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                </table>
                            </fieldset>
                            <fieldset style="grid-column: span 2;">
                                <legend>Compensation Details</legend>
                                <table>
                                    <tr>
                                        <td style="width: 150px;"><label>Bonus Eligibility</label></td>
                                        <td><input type="text" name="bonusEligible" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Bonus Amount</label> <span title="Provide the additional bonus amount for the employee" style="cursor: help;">&#x1F6C8;</span></td>
                                        <td><input type="number" name="bonusAmt" step="50" value="0.00"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Overtime Rate</label></td>
                                        <td><input type="text" name="overtimeRate" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 150px;"><label>Overtime Hours</label></td>
                                        <td><input type="number" name="overtimeHours" step="0.5" value="0.00"></td>
                                    </tr>
                                </table>
                            </fieldset>
                            <fieldset style="max-height: 140px; overflow-y: auto;">
                                <legend>Allowance Details</legend>
                                <table id="allowanceDetailsTable">
                                    <!-- contents will be generated here -->
                                </table>
                            </fieldset>
                            <fieldset style="grid-column: span 3;">
                                <table>
                                    <tr>
                                        <td style="width: 100px;">Basic Salary</td>
                                        <td><input type="number" name="basicSalary" placeholder="0.00" class="non-editable" readonly tabindex="-1"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100px;">Earnings <span title="Sum of compensations and allowances" style="cursor: help;">&#x1F6C8;</span></td>
                                        <td><input type="number" name="earnings" placeholder="0.00" class="non-editable" readonly tabindex="-1"></td>
                                        <td style="text-align: right;">Gross Pay $</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><input type="number" name="grossPay" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100px;">Deductions <span title="20% income tax and 15% social security" style="cursor: help;">&#x1F6C8;</span></td>
                                        <td><input type="number" name="deductions" placeholder="0.00" class="non-editable" readonly tabindex="-1"></td>
                                        <td style="text-align: right;">Net Pay $</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><input type="number" name="netPay" class="non-editable" readonly tabindex="-1"></td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                        <p><span style="color: red;">*</span> indicates a mandatory field.</p>
                        <div id="create-message" style="height: 20px; text-align: right; font-style: italic; color: red;"></div>
                        <hr style="margin-top: 0;">
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <button type="button" id="resetButton">Reset</button>
                                <button type="button" id="computeButton">Compute</button>
                            </div>
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
    <script src="../js/dateTime.js"></script>
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
                    const referenceNo = row.querySelector('td:nth-child(2)').textContent;
                    const period = row.querySelector('td:nth-child(3)').textContent;
                    const status = row.querySelector('td:nth-child(4)').textContent;

                    // Set the values in the modal fields
                    document.querySelector('#updateId').value = updateId;
                    document.querySelector('#referenceNo').value = referenceNo;
                    document.querySelector('#period').value = period;
                    document.querySelector('#status').value = status;
                    document.querySelector('#originalPeriod').value = period;

                    if (status === 'Approved') {
                        document.querySelector('#period').classList.add("non-editable");
                        document.querySelector('#period').readOnly = true;
                        document.querySelector('#period').tabIndex = -1;
                    } else {
                        document.querySelector('#period').classList.remove("non-editable");
                        document.querySelector('#period').readOnly = false;
                        document.querySelector('#period').tabIndex = 0;
                    }
                    
                    // Open the modal window
                    document.querySelector("#updateModal").style.display = "block";
                } 
            });

            // update functionalities
            document.querySelector('#okButton-update').addEventListener('click', function(event) {
                event.preventDefault();

                // Get the form values
                const updateId = document.querySelector('#updateId').value;
                const period = document.querySelector('#period').value;
                const originalPeriod = document.querySelector('#originalPeriod').value;

                if (period === originalPeriod) {
                    closeModal('updateModal');
                    return;
                }

                if (!period) {
                    return;
                }

                // Create a FormData object
                const formData = new FormData();
                formData.append('id', updateId);
                formData.append('period', period);

                // Send the form data to update-resignation.php using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../scripts/update-payroll.php');
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

            // create functionalities
            document.querySelector('#createButton').addEventListener('click', function(event) {

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.querySelector("input[name='referenceNo']").value = this.responseText;

                        document.querySelector("#createModal").style.display = "block";
                    }
                };
                xhr.open("GET", "../scripts/fetch-get/getReferenceNo.php", true);
                xhr.send();
            });

            document.querySelector('#resetButton').addEventListener('click', function(event) {
                document.querySelector('#create-form').reset();
                document.querySelector('#create-message').textContent = '';
                document.querySelector('#generate-message').textContent = '';

                document.querySelector("input[name='bonusAmt']").classList.remove("non-editable");
                document.querySelector("input[name='bonusAmt']").readOnly = false;
                document.querySelector("input[name='bonusAmt']").tabIndex = 0;

                let xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.querySelector("input[name='referenceNo']").value = this.responseText;
                    }
                };
                xhr.open("GET", "../scripts/fetch-get/getReferenceNo.php", true);
                xhr.send();
            });

            document.querySelector("#generateButton").addEventListener("click", function(event) {
                event.preventDefault();
                // Get the employee number from the form
                let employeeNo = document.querySelector("input[name='employeeNo']").value;

                // Send a request to the PHP script to get the employee details
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "../scripts/fetch-get/get-employee-details.php");
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Parse the JSON response
                        let response = JSON.parse(xhr.responseText);

                        if (response.error) {
                            // Employee number not found
                            document.querySelector("#generate-message").textContent = response.error;
                        } else {
                            document.querySelector("#generate-message").textContent = '';
                            // Update the form with the employee details
                            document.querySelector("input[name='firstName']").value = response.firstname;
                            document.querySelector("input[name='lastName']").value = response.lastname;
                            document.querySelector("input[name='gender']").value = response.gender;
                            document.querySelector("input[name='nric']").value = response.nric;
                            document.querySelector("input[name='dateHired']").value = response.date_hired;
                            document.querySelector("input[name='designation']").value = response.name;
                            document.querySelector("input[name='basicSalary']").value = response.basic_salary;

                            document.querySelector("input[name='bonusEligible']").value = response.bonus_eligible ? "True" : "False";;
                            document.querySelector("input[name='overtimeRate']").value = response.overtime_rate;

                            let bonusAmtInput = document.querySelector("input[name='bonusAmt']");
                            if (response.bonus_eligible === 0) {
                                bonusAmtInput.classList.add("non-editable");
                                bonusAmtInput.readOnly = true;
                                bonusAmtInput.tabIndex = -1;
                            } else {
                                bonusAmtInput.classList.remove("non-editable");
                                bonusAmtInput.readOnly = false;
                                bonusAmtInput.tabIndex = 0;
                            }

                            // Generate the rows for the allowances table
                            let allowanceDetailsTable = document.querySelector("#allowanceDetailsTable");
                            allowanceDetailsTable.innerHTML = ""; // Clear existing rows

                            if (response.allowances.length === 0) {
                                // handle
                            } else {
                                // Loop through the allowances and create a row for each allowance
                                for (let i = 0; i < response.allowances.length; i++) {
                                    let allowance = response.allowances[i];
                                    let row = allowanceDetailsTable.insertRow(-1);
                                    let cell1 = row.insertCell(0);
                                    let cell2 = row.insertCell(1);

                                    // Create a label for the allowance name
                                    let nameLabel = document.createElement("label");
                                    nameLabel.textContent = allowance.name;
                                    cell1.appendChild(nameLabel);

                                    // Create an input for the allowance amount
                                    let amountInput = document.createElement("input");
                                    amountInput.type = "number";
                                    amountInput.value = allowance.amount;
                                    amountInput.classList.add("non-editable");
                                    amountInput.readOnly = true;
                                    amountInput.tabIndex = -1;
                                    cell2.appendChild(amountInput);
                                }
                            }
                        }
                    }
                };
                xhr.send(`employeeNo=${employeeNo}`);
            });

            // Add event listener to compute button
            document.getElementById("computeButton").addEventListener("click", function() {
                // Calculate earnings
                let bonusAmount = parseFloat(document.querySelector("input[name='bonusAmt']").value);
                let overtimeRate = parseFloat(document.querySelector("input[name='overtimeRate']").value);
                let overtimeHours = parseFloat(document.querySelector("input[name='overtimeHours']").value);
                let basicSalary = parseFloat(document.querySelector("input[name='basicSalary']").value); 
                let allowanceTable = document.querySelector("#allowanceDetailsTable");
                let allowanceRows = allowanceTable.querySelectorAll("tr");
                let allowanceTotal = 0;

                // Check if the table is empty
                if (allowanceRows.length === 0) {
                    allowanceTotal = 0;
                } else {
                    // Calculate sum of allowance amounts
                    for (let i = 0; i < allowanceRows.length; i++) {
                        let allowanceAmount = parseFloat(allowanceRows[i].querySelector("input[type='number']").value);
                        if (!isNaN(allowanceAmount)) {
                            allowanceTotal += allowanceAmount;
                        }
                    }
                }

                // Calculate earnings
                let bonusEarnings = bonusAmount || 0;
                let overtimeEarnings = overtimeRate * overtimeHours;
                let totalEarnings = bonusEarnings + overtimeEarnings + allowanceTotal;

                // Update earnings input field
                document.querySelector("input[name='earnings']").value = totalEarnings.toFixed(2);

                // Calculate gross pay
                let grossPay = basicSalary + totalEarnings;

                // Calculate deductions
                let deductions = grossPay * 0.35; 

                // Calculate net pay
                let netPay = grossPay - deductions;

                // Update deductions and net pay input fields
                document.querySelector("input[name='grossPay']").value = grossPay.toFixed(2);
                document.querySelector("input[name='deductions']").value = deductions.toFixed(2);
                document.querySelector("input[name='netPay']").value = netPay.toFixed(2);
            });

            document.querySelector('#okButton-create').addEventListener('click', function(event) {
                event.preventDefault();

                let referenceNo = document.querySelector('[name="referenceNo"]').value;
                let payrollPeriod = document.querySelector('[name="payrollPeriod"]').value;
                let employeeNo = document.querySelector('[name="employeeNo"]').value;
                let basicSalary = document.querySelector("input[name='basicSalary']").value;
                let earnings = document.querySelector('input[name="earnings"]').value;
                let deductions = document.querySelector('input[name="deductions"]').value;
                let grossPay = document.querySelector('input[name="grossPay"]').value;
                let netPay = document.querySelector('input[name="netPay"]').value;

                // Check if any of the specified fields are empty
                if (!payrollPeriod) {
                    // Display an error message
                    document.querySelector('#create-message').textContent = 'Payroll Period is required.';
                    return;
                }
                if (!employeeNo) {
                    // Display an error message
                    document.querySelector('#create-message').textContent = 'Employee No is required.';
                    return;
                }
                if (!netPay) {
                    // Display an error message
                    document.querySelector('#create-message').textContent = 'Please compute the amount first.';
                    return;
                }

                // Send a request to the PHP script to check if the employee already exists for the given payroll period
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '../scripts/check-employee-period.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Parse the JSON response from the PHP script
                        let response = JSON.parse(xhr.responseText);

                        // Check if there was an error
                        if (response.error) {
                            // Display an error message
                            document.querySelector('#create-message').textContent = response.error;
                        } else {
                            // Get the values from the payroll items form fields
                            let bonusAmt = document.querySelector('[name="bonusAmt"]').value;
                            let overtimeRate = document.querySelector('[name="overtimeRate"]').value;
                            let overtimeHours = document.querySelector('[name="overtimeHours"]').value;

                            // Get the sum of the allowances
                            let allowanceInputs = document.querySelectorAll('#allowanceDetailsTable input[type="number"]');
                            let allowanceTotal = 0;
                            allowanceInputs.forEach(function(input) {
                                allowanceTotal += parseFloat(input.value);
                            });

                            // Create an array of objects representing the records to be inserted into the payroll_items table
                            let payrollItemsRecords = [];
                            payrollItemsRecords.push({item: 'Basic Salary', amount: basicSalary});

                            if (bonusAmt != 0) {
                                payrollItemsRecords.push({item: 'Bonus', amount: bonusAmt});
                            }
                            if (overtimeRate * overtimeHours != 0) {
                                payrollItemsRecords.push({item: 'Overtime', amount: overtimeRate * overtimeHours});
                            }
                            if (allowanceTotal != 0) {
                                payrollItemsRecords.push({item: 'Allowance', amount: allowanceTotal});
                            }
                            if (grossPay * 0.20 != 0) {
                                payrollItemsRecords.push({item: 'Income Tax', amount: (grossPay * 0.20)});
                            }
                            if (grossPay * 0.15 != 0) {
                                payrollItemsRecords.push({item: 'Social Security', amount: (grossPay * 0.15)});
                            }

                            // Send the data to the server-side script for insertion into the database
                            let xhr2 = new XMLHttpRequest();
                            xhr2.open('POST', '../scripts/create-payroll.php');
                            xhr2.setRequestHeader('Content-Type', 'application/json');
                            xhr2.send(JSON.stringify({
                                referenceNo: referenceNo,
                                payrollPeriod: payrollPeriod,
                                employeeNo: employeeNo,
                                earnings: earnings,
                                deductions: deductions,
                                netPay: netPay,
                                payrollItemsRecords: payrollItemsRecords
                            }));

                            // Clear the form fields
                            document.querySelector('#create-form').reset();

                            closeModal('createModal');
                            // Display a success message
                            document.querySelector('#general-message').textContent = 'Create successful. Refresh the table to see changes.';
                        }
                    }
                };
                xhr.send(encodeURI('employeeNo=' + employeeNo + '&payrollPeriod=' + payrollPeriod));
            });

            // refresh functionalities
            document.querySelector('#refreshButton').addEventListener('click', function(event) {
                event.preventDefault();

                // Fetch updated data from server
                const xhr = new XMLHttpRequest();
                xhr.open('GET', '../scripts/fetch-get/fetch-payrolls.php');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Parse the JSON response
                        const newData = JSON.parse(xhr.responseText);

                        // Extract the required fields from the newData and create a new array
                        const updatedData = newData.map(item => [
                            item.id,
                            item.reference_no,
                            item.payroll_period,
                            item.status,
                            '<i class="fas fa-edit"></i></i>'
                        ]);

                        // Get the current page index
                        const currentPage = table.page();

                        // Clear the DataTable and add the updated data
                        table.clear().rows.add(updatedData).draw();

                        // Go back to the current page
                        table.page(currentPage).draw('page');

                        document.querySelector('#general-message').textContent = 'Table refreshed.';
                    } else {
                        // Handle error
                    }
                };
                xhr.send();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // get references to month input and status select elements
            let monthInput = document.querySelector('#review-payroll input[type="month"]');
            let statusSelect = document.querySelector('#review-payroll select');
            let table = document.querySelector('#payrollListTable');

            // handle change event on month input
            monthInput.addEventListener('change', function() {
                updateTable();
            });

            // handle change event on status select
            statusSelect.addEventListener('change', function() {
                updateTable();
            });

            // function to update table with data for selected month and status
            function updateTable() {
                // get selected month and status
                let month = monthInput.value;
                let status = statusSelect.value;

                // check if month input has a value
                if (month) {
                // create AJAX request to PHP script to get data for selected month and status
                let xhr = new XMLHttpRequest();
                xhr.open('GET', '../scripts/fetch-get/get-month-payrolls.php?month=' + month + '&status=' + status);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // parse JSON response
                        let data = JSON.parse(xhr.responseText);

                        // clear table contents
                        table.innerHTML = '';

                        // loop through data and add rows to table
                        data.forEach(function(row) {
                            let tr = document.createElement('tr');
                            tr.innerHTML =
                            '<td style="display: none;">' + row.id + '</td>' +
                            '<td>' + row.reference_no + '</td>';

                            // add click event listener to row
                            tr.addEventListener('click', function() {
                            
                                // remove selected class from any previously selected rows
                                let selectedRows = table.querySelectorAll('.selected');
                                selectedRows.forEach(function(row) {
                                    row.classList.remove('selected');
                                });

                                // add selected class to clicked row
                                this.classList.add('selected');

                                // get id value from first cell in row
                                let id = this.cells[0].textContent;

                                // create AJAX request to PHP script with id value as query parameter
                                let xhr = new XMLHttpRequest();
                                xhr.open('GET', '../scripts/fetch-get/get-payroll-items.php?id=' + id);
                                xhr.onload = function() {
                                    if (xhr.status === 200) {
                                        // Parse the JSON response
                                        let data = JSON.parse(xhr.responseText);

                                        // Assign the values to variables
                                        let referenceNo = data.referenceNo;
                                        let period = data.period;
                                        let status = data.status;
                                        let employeeNo = data.employeeNo;
                                        let earnings = data.earnings;
                                        let deductions = data.deductions;
                                        let netPay = data.netPay;
                                        let payrollItems = data.payrollItems;

                                        // Assign the values to the corresponding td elements
                                        document.querySelector('#payroll-employeeNo').textContent = employeeNo;
                                        document.querySelector('#payroll-referenceNo').textContent = referenceNo;
                                        document.querySelector('#payroll-period').textContent = period;
                                        document.querySelector('#payroll-status').textContent = status;
                                        document.querySelector('#payroll-earnings').textContent = earnings;
                                        document.querySelector('#payroll-deductions').textContent = deductions;
                                        document.querySelector('#payroll-netPay').textContent = netPay;

                                        for (let i = 0; i < payrollItems.length; i++) {
                                            // Check if the item property is 'Basic Salary'
                                            if (payrollItems[i].item === 'Basic Salary') {
                                                // Assign the amount property to the basicSalary variable
                                                document.querySelector('#payroll-basic').textContent = payrollItems[i].amount;
                                            }
                                            if (payrollItems[i].item === 'Bonus') {
                                                // Assign the amount property to the basicSalary variable
                                                document.querySelector('#payroll-bonus').textContent = payrollItems[i].amount;
                                            }
                                            if (payrollItems[i].item === 'Overtime') {
                                                // Assign the amount property to the basicSalary variable
                                                document.querySelector('#payroll-overtime').textContent = payrollItems[i].amount;
                                            }
                                            if (payrollItems[i].item === 'Allowance') {
                                                // Assign the amount property to the basicSalary variable
                                                document.querySelector('#payroll-allowance').textContent = payrollItems[i].amount;
                                            }
                                            if (payrollItems[i].item === 'Income Tax') {
                                                // Assign the amount property to the basicSalary variable
                                                document.querySelector('#payroll-incomeTax').textContent = payrollItems[i].amount;
                                            }
                                            if (payrollItems[i].item === 'Social Security') {
                                                // Assign the amount property to the basicSalary variable
                                                document.querySelector('#payroll-socialSecurity').textContent = payrollItems[i].amount;
                                            }
                                        }
                                    }
                                };
                                xhr.send();
                            });

                            table.appendChild(tr);
                        });
                    }
                };
                xhr.send();
                } else {
                    // clear table contents
                    table.innerHTML = '';  
                }
            }
        });
    </script>
    <script>
        // clear functionalities
        function resetPayrollReviewTable() {
            // Reset the text content of the td elements with an ID
            document.querySelector('#payroll-employeeNo').textContent = '';
            document.querySelector('#payroll-referenceNo').textContent = '';
            document.querySelector('#payroll-period').textContent = '';
            document.querySelector('#payroll-status').textContent = '';
            document.querySelector('#payroll-basic').textContent = '$ 0.00';
            document.querySelector('#payroll-earnings').textContent = '$ 0.00';
            document.querySelector('#payroll-bonus').textContent = '$ 0.00';
            document.querySelector('#payroll-overtime').textContent = '$ 0.00';
            document.querySelector('#payroll-allowance').textContent = '$ 0.00';
            document.querySelector('#payroll-deductions').textContent = '$ 0.00';
            document.querySelector('#payroll-incomeTax').textContent = '$ 0.00';
            document.querySelector('#payroll-socialSecurity').textContent = '$ 0.00';
            document.querySelector('#payroll-netPay').textContent = '$ 0.00';
        }

        // Add an event listener to the clearReportButton
        document.querySelector('#clearReportButton').addEventListener('click', function(event) {
            // Call the resetPayrollReviewTable function
            resetPayrollReviewTable();

            document.querySelector('#general-message2').textContent = 'Report cleared.';
        });

        // JavaScript code to send a request to the PHP script when the approveButton is clicked
        document.querySelector('#approveButton').addEventListener('click', function(event) {
            // Get the reference number
            let referenceNo = document.querySelector('#payroll-referenceNo').textContent;
            let status = document.querySelector('#payroll-status').textContent;

            if (status === 'Approved' || status === '') {
                return;
            }

            // Create a FormData object to hold the data to send to the PHP script
            let formData = new FormData();
            formData.append('referenceNo', referenceNo);

            // Send a POST request to the PHP script with the FormData object
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Handle the response here if necessary
                    resetPayrollReviewTable();

                    document.querySelector('#general-message2').textContent = 'Payroll approved.';
                }
            };
            xhr.open("POST", "../scripts/approve-payroll.php", true);
            xhr.send(formData);
        });
    </script>
</body>
</html>
