<?php
    include 'connectdb.php';

    // Get the data from the POST request
    $employeeNos = json_decode($_POST['employeeNos']);
    $assignName = $_POST['assignName'];
    $assignBaseAmt = $_POST['assignBaseAmt'];
    $assignType = $_POST['assignType'];

    // Get the allowance ID
    $stmt = $conn->prepare('SELECT id FROM allowances WHERE name = ?');
    $stmt->bind_param('s', $assignName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $allowanceId = $row['id'];

    // Insert the records into the employee_allowances table
    $stmt = $conn->prepare('INSERT INTO employee_allowances (employee_id, allowance_id, amount) VALUES ((SELECT id FROM employees WHERE employee_no = ?), ?, ?)');
    foreach ($employeeNos as $employeeNo) {
        // Check if the employee already has the allowance
        $checkStmt = $conn->prepare('SELECT COUNT(*) FROM employee_allowances WHERE employee_id = (SELECT id FROM employees WHERE employee_no = ?) AND allowance_id = ?');
        $checkStmt->bind_param('si', $employeeNo, $allowanceId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $checkRow = $checkResult->fetch_assoc();
        if ($checkRow['COUNT(*)'] == 0) {
            // If the employee doesn't have the allowance, insert the record
            $stmt->bind_param('sid', $employeeNo, $allowanceId, $assignBaseAmt);
            $stmt->execute();
        }
    }

    // Insert a record into temp_log table
    $operation = 'Assign';
    $object = 'Selected Employees';
    $details = 'Assigned Allowance: ' . $assignName . ', Type: ' . $assignType . ', Base Amount: ' . $assignBaseAmt;
    $page = $_SESSION['page'];
    
    $logStmt = $conn->prepare('INSERT INTO temp_log (operation, object, details, page) VALUES (?, ?, ?, ?)');
    $logStmt->bind_param('ssss', $operation, $object, $details, $page);
    $logStmt->execute();

    // Close the statement and connection
    $stmt->close();

    // Send a response back to JavaScript
    echo 'Records inserted successfully';

    $conn->close();
?>