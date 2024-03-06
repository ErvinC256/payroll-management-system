<?php
    include 'connectdb.php';

    // Get data from request body
    $name = $_POST['name'];
    $type = ucfirst( $_POST['type']);
    $baseAmt = $_POST['baseAmt'];

    // Check if the allowance type already exists in the database
    $stmt = $conn->prepare('SELECT COUNT(*) FROM allowances WHERE name = ?');
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // Send a JSON response indicating failure
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'The name already exists in the database.']);
    } else {
        // Update database
        $stmt = $conn->prepare('INSERT INTO allowances (name, type, base_amount) VALUES (?, ?, ?)');
        $stmt->bind_param("ssd", $name, $type, $baseAmt);

        if ($stmt->execute()) {
            // Insert into temp_log table
            $operation = 'Create';
            $object = $name;
            $details = 'Created with Type: ' . $type . ', Base Amount: ' . $baseAmt;
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
    }

    $conn->close();
?>