<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "payroll_db";

    $connection = new mysqli($servername, $username, $password, $dbname);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $referenceNo = $_GET['referenceNo']; // Retrieve the referenceNo from GET parameters

    $query = "SELECT pi.item, pi.amount
            FROM payroll_items pi
            JOIN payroll p ON pi.payroll_id = p.id
            WHERE p.reference_no = '$referenceNo'";

    // Execute the query
    $result = mysqli_query($connection, $query);

    // Check if the query was successful
    if ($result) {
        // Initialize earnings and deductions arrays
        $earnings = [];
        $deductions = [];

        // Fetch the earnings and deductions data
        while ($row = mysqli_fetch_assoc($result)) {
            $item = $row['item'];
            $amount = $row['amount'];

            if ($item == 'Allowance' || $item == 'Overtime' || $item == 'Bonus') {
                // Add to earnings array
                $earnings[] = "$item $amount";
            } elseif ($item == 'Income Tax' || $item == 'Social Security') {
                // Add to deductions array
                $deductions[] = "$item $amount";
            }
        }

        // Create an associative array to store the data
        $data = array(
            'referenceNo' => $referenceNo,
            'earnings' => $earnings,
            'deductions' => $deductions
        );

        // Convert the array to JSON
        $jsonData = json_encode($data);

        // Send the JSON response
        header('Content-Type: application/json');
        echo $jsonData;
    } else {
        // Handle query error
        echo "Failed to retrieve earnings and deductions: " . mysqli_error($connection);
    }

    // Close the database connection
    mysqli_close($connection);
?>
