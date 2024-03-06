<?php
    include 'connectdb.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login successful
        $response = ['status' => 'success'];
        $_SESSION['username'] = $username;
    } else {
        // Login failed
        $response = ['status' => 'error', 'message' => 'Invalid username or password'];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);

    $conn->close();
?>