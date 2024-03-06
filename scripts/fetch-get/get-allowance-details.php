<?php
    include '../connectdb.php';
    
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
        $stmt = $conn->prepare("SELECT type, base_amount FROM allowances WHERE name=?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo json_encode($row);
            }
        }
    }

    $conn->close();
?>
