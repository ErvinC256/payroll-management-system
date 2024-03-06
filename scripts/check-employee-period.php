<?php
    include 'connectdb.php';

    $employeeNo = $_POST["employeeNo"];
    $payrollPeriod = $_POST["payrollPeriod"];

    // Replace with your own database query to check if the employee already exists for the given payroll period
    $query = "SELECT * FROM payroll WHERE employee_id = (SELECT id FROM employees WHERE employee_no = ?) AND payroll_period = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $employeeNo, $payrollPeriod);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["error" => "Employee already exists for the given payroll period."]);
    } else {
        echo json_encode(["success" => true]);
    }

    $conn->close();
?>
