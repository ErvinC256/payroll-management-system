<?php
    include 'connectdb.php';

    // Get data from request body
    $id = $_POST['id'];
    $deleteEmployeeNo = $_POST['deleteEmployeeNo'];

    // Delete row from database
    $stmt = $conn->prepare('DELETE FROM employees WHERE id = ?');
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $operation = 'Delete';
        $object = $deleteEmployeeNo;
        $details = "Record deleted";
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
        echo json_encode(['success' => false, 'error' => 'Database delete failed']);
    }

    $conn->close();
?>
