<?php
    // approvePayroll.php

    include 'connectdb.php';

    // Get the reference number from the POST data
    $referenceNo = $_POST['referenceNo'];

    // Update the status of the payroll with the specified reference number
    $stmt = $conn->prepare('UPDATE payroll SET status = ? WHERE reference_no = ?');
    $status = 'Approved';
    $stmt->bind_param('ss', $status, $referenceNo);
    $stmt->execute();

    // Close the database connection
    $conn->close();
?>
