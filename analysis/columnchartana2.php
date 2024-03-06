<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "payroll_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT department_code, SUM(bonus_eligible) AS bonus_total FROM designations WHERE bonus_eligible = 1 GROUP BY department_code";
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
