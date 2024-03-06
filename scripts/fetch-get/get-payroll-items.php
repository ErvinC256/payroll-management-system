<?php
    include '../connectdb.php';

    // Get the id from the query string
    $id = $_GET['id'];

    // Query the payroll table to get the payroll data for the specified id
    $query = "SELECT p.reference_no, p.payroll_period, p.status, e.employee_no, p.earnings, p.deductions, p.net_pay FROM payroll p JOIN employees e ON p.employee_id = e.id WHERE p.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $referenceNo = $row['reference_no'];
    $period = $row['payroll_period'];
    $status = $row['status'];
    $employeeNo = $row['employee_no'];
    $earnings = $row['earnings'];
    $deductions = $row['deductions'];
    $netPay = $row['net_pay'];

    // Query the payroll_items table to get the payroll items for the specified id
    $query = "SELECT item, amount FROM payroll_items WHERE payroll_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $payrollItems = [];
    while ($row = $result->fetch_assoc()) {
        array_push($payrollItems, [
            'item' => $row['item'],
            'amount' => $row['amount']
        ]);
    }

    // Return a JSON response containing the payroll data
    echo json_encode([
        'referenceNo' => $referenceNo,
        'period' => $period,
        'status' => $status,
        'employeeNo' => $employeeNo,
        'earnings' => $earnings,
        'deductions' => $deductions,
        'netPay' => $netPay,
        'payrollItems' => $payrollItems
    ]);

    $conn->close();
?>
