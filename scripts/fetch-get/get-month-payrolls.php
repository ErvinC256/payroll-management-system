<?php
    include '../connectdb.php';

    // get the selected month and status from the request
    $month = $_GET['month'];
    $status = $_GET['status'];

    // create a prepared statement to query the database
    if ($status === 'All') {
        $stmt = $conn->prepare("SELECT id, reference_no FROM payroll WHERE payroll_period = ?");
        $stmt->bind_param("s", $month);
    } else {
        $stmt = $conn->prepare("SELECT id, reference_no FROM payroll WHERE payroll_period = ? AND status = ?");
        $stmt->bind_param("ss", $month, $status);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // create an array to store the data
    $data = array();

    // loop through the result and add each row to the data array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // close the prepared statement
    $stmt->close();

    // return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);

    $conn->close();
?>