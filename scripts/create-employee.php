<?php
    include 'connectdb.php';

    // Get data from request body
    $employeeNo = $_POST['employeeNo'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = ucfirst($_POST['gender']);
    $nric = $_POST['nric'];
    $date = $_POST['date'];
    $designationName = $_POST['designation'];
    $salary = $_POST['salary'];
    $nricCheck = $_POST['nricCheck'] === 'true' ? true : false;

    // Look up the id of the selected designation from the designations table
    $stmt = $conn->prepare('SELECT id FROM designations WHERE name = ?');
    $stmt->bind_param('s', $designationName);
    $stmt->execute();
    $stmt->bind_result($designation);
    if (!$stmt->fetch()) {
        // Send a JSON response indicating failure
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Invalid designation name']);
        exit;
    }
    $stmt->close();
    
    // Check NRIC existence if enabled
    if ($nricCheck) {
        $stmt = $conn->prepare('SELECT COUNT(*) FROM employees WHERE nric = ?');
        $stmt->bind_param('s', $nric);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            // Send a JSON response indicating failure
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'The NRIC already exists in the database.']);
            exit;
        }
    }

    // Prepare the SQL statement to insert the employee record
    $stmt = $conn->prepare('INSERT INTO employees (employee_no, firstname, lastname, gender, nric, date_hired, designation, basic_salary) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssssid', $employeeNo, $firstName, $lastName, $gender, $nric, $date, $designation, $salary);

    if ($stmt->execute()) {
        // Insert into temp_log table
        $operation = 'Create';
        $object = $employeeNo;
        $details = 'Created with Designation: ' . $designationName . ', Salary: ' . $salary . ', Gender: ' . $gender . ', NRIC: ' . $nric . ', Date: ' . $date;
        $page = $_SESSION['page'];

        $logStmt = $conn->prepare('INSERT INTO temp_log (operation, object, details, page) VALUES (?, ?, ?, ?)');
        $logStmt->bind_param("ssss", $operation, $object, $details, $page);
        $logStmt->execute();
        $logStmt->close();

        // Send a JSON response indicating success
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        // Send a JSON response indicating failure
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Database create failed']);
    }
    $stmt->close();

    $conn->close();
?>
