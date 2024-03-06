<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "payroll_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT YEAR(date_hired) AS year_hired, COUNT(*) AS count FROM employees GROUP BY YEAR(date_hired)";
    $result = $conn->query($sql);

    $data = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($data);
?>
