<?php
    include 'connectdb.php';

    // Get the username from the session
    $username = $_SESSION['username'];

    // Get the password from the request
    $password = $_POST['password'];

    // Query the database to verify the password
    $stmt = $conn->prepare('SELECT * FROM admin WHERE username = ? AND password = ?');
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Password is correct
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        // Password is incorrect
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
    }

    $conn->close();
?>
