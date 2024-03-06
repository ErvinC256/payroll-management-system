<?php
    include 'connectdb.php';

    // Get data from request body
    $id = $_POST['id'];
    $name = $_POST['name'];
    $baseAmt = $_POST['baseAmt'];
    $originalBaseAmt = $_POST['originalBaseAmt'];

    // Update database
    $stmt = $conn->prepare('UPDATE allowances SET base_amount = ? WHERE id = ?');
    $stmt->bind_param('di', $baseAmt, $id);
    
    if ($stmt->execute()) {
        // Update employee_allowances table
        $stmt = $conn->prepare('UPDATE employee_allowances SET amount = ? WHERE allowance_id = ?');
        $stmt->bind_param('di', $baseAmt, $id);
        $stmt->execute();

        // Insert a record into temp_log table
        $operation = 'Update';
        $object = $name;
        $details = 'Base amount changed from ' . $originalBaseAmt . ' to ' . $baseAmt;
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
