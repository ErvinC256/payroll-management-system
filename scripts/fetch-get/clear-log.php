<?php
    // clearLog.php

    include '../connectdb.php';

    // Delete the log data associated with the page from the temp_log table
    $stmt = $conn->prepare('DELETE FROM temp_log WHERE page = ?');
    $stmt->bind_param('s', $_SESSION['page']);
    $stmt->execute();

    // Close the database connection
    $conn->close();
?>
