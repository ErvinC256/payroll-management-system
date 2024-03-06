<?php
    include 'connectdb.php';

    // Get data from request body
    $id = $_POST['id'];
    $deleteName = $_POST['deleteName'];
    $reason = $_POST['reason'];

    // Delete row from allowances table
    $stmt = $conn->prepare('DELETE FROM allowances WHERE id = ?');
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Insert deleted record into deleted_allowances table
        $insertStmt = $conn->prepare('INSERT INTO deleted_allowances (name, reason) VALUES (?, ?)');
        $insertStmt->bind_param('ss', $deleteName, $reason);

        if ($insertStmt->execute()) {
            // Insert a record into temp_log table
            $operation = 'Delete';
            $object = $deleteName;
            $details = "Record deleted";
            $page = $_SESSION['page'];
            
            $logStmt = $conn->prepare('INSERT INTO temp_log (operation, object, details, page) VALUES (?, ?, ?, ?)');
            $logStmt->bind_param('ssss', $operation, $object, $details, $page);
            $logStmt->execute();

            // Send a JSON response indicating success
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            // Send a JSON response indicating failure to insert into deleted_allowances table
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Database insert into deleted_allowances failed']);
        }
    } else {
        // Send a JSON response indicating failure to delete from allowances table
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Database delete from allowances failed']);
    }

    $conn->close();
?>
