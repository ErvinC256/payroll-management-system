<?php
    include 'connectdb.php';

    // Get data from request body
    $data = json_decode(file_get_contents('php://input'), true);
    $updateId2 = $data['updateId2'];
    $allowanceDetails = $data['allowanceDetails'];

    // Loop through allowance details and update database
    foreach ($allowanceDetails as $detail) {
        $name = $detail['name'];
        $amount = $detail['amount'];
        
        // Update employee_allowances table
        $stmt = $conn->prepare('UPDATE employee_allowances INNER JOIN allowances ON employee_allowances.allowance_id = allowances.id SET employee_allowances.amount = ? WHERE employee_allowances.employee_id = ? AND allowances.name = ?');
        $stmt->bind_param('dis', $amount, $updateId2, $name);
        $stmt->execute();
    }

    // Send a JSON response indicating success
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);

    $conn->close();
?>
