<?php
    include 'connectdb.php';

    // Get data from request body
    $id = $_POST['id'];
    $bonus = $_POST['bonus'] === 'yes' ? 1 : 0;
    $originalBonus = $_POST['originalBonus'];
    $overtime = $_POST['overtime'];
    $originalOvertime = $_POST['originalOvertime'];
    $name = $_POST['name'];

    // Update database
    $stmt = $conn->prepare('UPDATE designations SET bonus_eligible = ?, overtime_rate = ? WHERE id = ?');
    $stmt->bind_param('idi', $bonus, $overtime, $id);
    
    if ($stmt->execute()) {
        // Insert a record into temp_log table
        $operation = 'Update';
        $object = $name;
        $details = "Bonus changed from " . ucfirst($originalBonus) . " to " . ucfirst($_POST['bonus']) . ". Overtime changed from " . $originalOvertime . " to " . $overtime;
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
