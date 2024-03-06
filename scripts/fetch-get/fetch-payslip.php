<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "payroll_db";

    $connection = new mysqli($servername, $username, $password, $dbname);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $referenceNo = $_GET['referenceNo']; // Retrieve the referenceNo from GET parameters
    
    // Get the current date
    $paymentDate = date("d-m-Y");

    // Retrieve the employee information from the employees table
    $query = "SELECT e.employee_no, e.firstname, e.lastname, e.nric, e.designation, e.basic_salary
            FROM employees e
            JOIN payroll p ON e.id = p.employee_id
            JOIN payroll_items pi ON p.id = pi.payroll_id
            WHERE p.reference_no = '$referenceNo'";

    $result = mysqli_query($connection, $query);

    // Check if the query was successful
    if ($result) {
        // Initialize earnings and deductions arrays
        $earnings = [];
        $deductions = [];

        // Fetch the employee data
        while ($row = mysqli_fetch_assoc($result)) {
            $employeeNo = $row['employee_no'];
            $firstName = $row['firstname'];
            $lastName = $row['lastname'];
            $nric = $row['nric'];
            $designation = $row['designation'];
            $basicSalary = $row['basic_salary'];
        }

        // Fetch the total deduction and net pay from the payroll table
        $query = "SELECT deductions, net_pay FROM payroll";
        $result = mysqli_query($connection, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $totalDeduction = $row['deductions'];
            $netPay = $row['net_pay'];

            // Calculate the gross pay
            $grossPay = $totalDeduction + $netPay;

            // Create an associative array to store the employee data
            $data = array(
                'referenceNo' => $referenceNo,
                'paymentDate' => $paymentDate,
                'employeeNo' => $employeeNo,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'nric' => $nric,
                'designation' => $designation,
                'basicSalary' => $basicSalary,
                'grossPay' => $grossPay,
                'totalDeduction' => $totalDeduction,
                'netPay' => $netPay 
            );

            // Convert the array to JSON
            $jsonData = json_encode($data);

            // Send the JSON response
            header('Content-Type: application/json');
            echo $jsonData;
        } else {
            // Handle query error
            echo "Failed to retrieve total deduction and net pay from payroll table: " . mysqli_error($connection);
        }
    } else {
        // Handle query error
        echo "Failed to retrieve employee information from employees table: " . mysqli_error($connection);
    }

    // Close the database connection
    mysqli_close($connection);
?>
