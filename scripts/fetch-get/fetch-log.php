<?php
    include '../connectdb.php';

    // Fetch updated data from the database for the specific page
    $stmt = $conn->prepare('SELECT timestamp, operation, object, details FROM temp_log WHERE page = ? ORDER BY timestamp DESC');
    $stmt->bind_param('s', $_SESSION['page']);
    $stmt->execute();
    $result = $stmt->get_result();

    // Create an array to hold the data
    $data = [];

    // Fetch the data and add it to the array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Send a JSON response with the data
    header('Content-Type: application/json');
    echo json_encode($data);

    $conn->close();
?>
