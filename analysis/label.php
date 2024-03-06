<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "payroll_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch Total Employees
    $sqlEmployees = "SELECT COUNT(*) AS totalEmployees FROM employees";
    $resultEmployees = $conn->query($sqlEmployees);
    $totalEmployees = 0;
    if ($resultEmployees && $resultEmployees->num_rows > 0) {
        $row = $resultEmployees->fetch_assoc();
        $totalEmployees = $row['totalEmployees'];
    }

    // Fetch Branch of Department
    $sqlBranches = "SELECT COUNT(DISTINCT name) AS totalBranches FROM designations";
    $resultBranches = $conn->query($sqlBranches);
    $totalBranches = 0;
    if ($resultBranches && $resultBranches->num_rows > 0) {
        $row = $resultBranches->fetch_assoc();
        $totalBranches = $row['totalBranches'];
    }

    // Fetch Total Salary
    $sqlSalary = "SELECT SUM(basic_salary) AS totalSalary FROM employees";
    $resultSalary = $conn->query($sqlSalary);
    $totalSalary = 0;
    if ($resultSalary && $resultSalary->num_rows > 0) {
        $row = $resultSalary->fetch_assoc();
        $totalSalary = $row['totalSalary'];
    }

    // Fetch Total Overtime Rate
    $sqlOvertimeRate = "SELECT SUM(overtime_rate) AS totalOvertimeRate FROM designations";
    $resultOvertimeRate = $conn->query($sqlOvertimeRate);
    $totalOvertimeRate = 0;
    if ($resultOvertimeRate && $resultOvertimeRate->num_rows > 0) {
        $row = $resultOvertimeRate->fetch_assoc();
        $totalOvertimeRate = $row['totalOvertimeRate'];
    }

    $conn->close();

    // Prepare the data as an associative array
    $data = [
        'totalEmployees' => $totalEmployees,
        'totalBranches' => $totalBranches,
        'totalSalary' => $totalSalary,
        'totalOvertimeRate' => $totalOvertimeRate
    ];

    // Set the response header to JSON
    header('Content-Type: application/json');

    // Encode the data as JSON and echo it
    echo json_encode($data);
?>
