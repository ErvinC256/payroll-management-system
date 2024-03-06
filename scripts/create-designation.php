<?php
    include 'connectdb.php';

    // Get data from request body
    $deptCode = $_POST['deptCode'];
    $name = $_POST['name'];
    $bonus = $_POST['bonus'] === 'yes' ? 1 : 0;
    $overtime = $_POST['overtime'];

    // Check if the name already exists in the database
    $stmt = $conn->prepare('SELECT COUNT(*) FROM designations WHERE name = ?');
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
        $stmt = $conn->prepare('INSERT INTO designations (department_code, name, bonus_eligible, overtime_rate) VALUES (?, ?, ?, ?)');
        $stmt->bind_param("ssid", $deptCode, $name, $bonus, $overtime);

        if ($stmt->execute()) {
            // Insert into temp_log table
            $operation = 'Create';
            $object = $name;
            $details = 'Created with Department Code: ' . $deptCode . ', Bonus Eligibility: ' . ucfirst($_POST['bonus']) . ', Overtime Rate: ' . $overtime;
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
