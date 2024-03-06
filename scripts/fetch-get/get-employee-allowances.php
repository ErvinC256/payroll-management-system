<?php
    include '../connectdb.php';

    if (isset($_POST['updateId'])) {
        $updateId = $_POST['updateId'];
        $stmt = $conn->prepare('SELECT allowances.name, allowances.type, employee_allowances.amount FROM employee_allowances INNER JOIN allowances ON employee_allowances.allowance_id = allowances.id WHERE employee_allowances.employee_id = ?');
        $stmt->bind_param('i', $updateId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    $conn->close();
?>
