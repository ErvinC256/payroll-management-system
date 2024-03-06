<?php
    include '../connectdb.php';
    
    // Get the employee number from the request
    $employeeNo = $_POST["employeeNo"];

    // Query the database for the employee details
    $stmt = $conn->prepare("SELECT e.id, e.firstname, e.lastname, e.gender, e.nric, e.date_hired, e.basic_salary, d.name, d.bonus_eligible, d.overtime_rate FROM employees e INNER JOIN designations d ON e.designation = d.id WHERE e.employee_no=?");
    $stmt->bind_param("s", $employeeNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Employee number is in the database
        $employee = $result->fetch_assoc();

        // Get the employee ID
        $employeeId = $employee["id"];

        // Query to get the allowances
        $allowances_query = "SELECT a.name, ea.amount FROM employee_allowances ea INNER JOIN allowances a ON ea.allowance_id = a.id WHERE ea.employee_id = ?";
        $stmt = $conn->prepare($allowances_query);
        $stmt->bind_param("i", $employeeId);
        $stmt->execute();
        $allowances_result = $stmt->get_result();

        // Create an array to store the allowances
        $allowances = array();

        // Loop through the result and add each allowance to the array
        while ($row = $allowances_result->fetch_assoc()) {
            $allowances[] = $row;
        }

        // Add the allowances to the employee details
        $employee["allowances"] = $allowances;

        // Return the employee details as a JSON object
        header("Content-Type: application/json");
        echo json_encode($employee);
    } else {
        // Employee number is not in the database
        header("Content-Type: application/json");
        echo json_encode(["error" => "Employee number not found"]);
    }

    $conn->close();
?>


