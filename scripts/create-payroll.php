<?php
    include 'connectdb.php';

    // Get the data from the request body
    $data = json_decode(file_get_contents('php://input'));

    // Get the payroll data from the data object
    $referenceNo = $data->referenceNo;
    $payrollPeriod = $data->payrollPeriod;
    $employeeNo = $data->employeeNo;
    $earnings = $data->earnings;
    $deductions = $data->deductions;
    $netPay = $data->netPay;

    // Get the employee_id from the employees table using the employee_no value from the form data
    $query = "SELECT id FROM employees WHERE employee_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $employeeNo);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $employeeId = $row['id'];

    // Insert a new row into the payroll table with the specified values
    $query = "INSERT INTO payroll (reference_no, employee_id, payroll_period, earnings, deductions, net_pay) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sisddd", $referenceNo, $employeeId, $payrollPeriod, $earnings, $deductions, $netPay);
    if ($stmt->execute()) {
        // Get the last insert id (i.e., the id of the newly inserted payroll record)
        $payrollId = $conn->insert_id;

        // Close the first prepared statement
        $stmt->close();

        // Get the payroll items data from the data object
        $payrollItemsRecords = $data->payrollItemsRecords;

        // Prepare an INSERT statement for the payroll_items table
        $stmt = $conn->prepare('INSERT INTO payroll_items (payroll_id, item, amount) VALUES (?, ?, ?)');

        // Bind the parameters
        $stmt->bind_param('isd', $payrollId, $item, $amount);

        // Loop through each record and execute the INSERT statement
        foreach ($payrollItemsRecords as $record) {
            $item = $record->item;
            $amount = $record->amount;
            if (!$stmt->execute()) {
                die('Execute failed: ' . $stmt->error);
            }
        }

        // Return a JSON response indicating that the database update was successful
        echo json_encode(["success" => true]);
    } else {
        // Return a JSON response indicating that there was an error
        echo json_encode(["error" => "There was an error inserting the data into the database."]);
    }

    $conn->close();
?>

