<?php
    include 'connectdb.php';

    // Get data from request body
    $id = $_POST['id'];
    $period = $_POST['period'];

    // Update database
    $stmt = $conn->prepare('UPDATE payroll SET payroll_period = ? WHERE id = ?');
    $stmt->bind_param('si', $period, $id);

    if ($stmt->execute()) {
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
