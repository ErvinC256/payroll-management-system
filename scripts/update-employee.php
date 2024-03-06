<?php
    include 'connectdb.php';

    // Get data from request body
    $id = $_POST['id'];
    $salary = $_POST['salary'];
    $originalSalary = $_POST['originalSalary'];
    $employeeNo = $_POST['employeeNo'];

    // Update database
    $stmt = $conn->prepare('UPDATE employees SET basic_salary = ? WHERE id = ?');
    $stmt->bind_param('di', $salary, $id);
    
    if ($stmt->execute()) {
        // Insert a record into temp_log table
        $operation = 'Update';
        $object = $employeeNo;
        $details = 'Salary changed from ' . $originalSalary . ' to ' . $salary;
        $page = $_SESSION['page'];
        
        $logStmt = $conn->prepare('INSERT INTO temp_log (operation, object, details, page) VALUES (?, ?, ?, ?)');
        $logStmt->bind_param('ssss', $operation, $object, $details, $page);
        $logStmt->execute();

        // Send a JSON response indicating success
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        // Send a JSON response indicating failure
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Database update failed']);
    }

    $conn->close();
?>