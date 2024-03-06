<?php
    // logout.php

    include 'connectdb.php';

    // Clear the username value from the session
    unset($_SESSION['username']);

    // Delete all rows from the temp_log table
    $stmt = $conn->prepare('DELETE FROM temp_log');
    $stmt->execute();

    // Close the database connection
    $conn->close();

    // Redirect back to index.php
    header('Location: ../index.php');
    exit;
?>
