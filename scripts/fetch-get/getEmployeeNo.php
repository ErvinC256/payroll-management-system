<?php
    include '../connectdb.php';
    
    $sql = "SELECT MAX(employee_no) as maxEmployeeNo FROM employees";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $maxEmployeeNo = $row["maxEmployeeNo"];
            $maxEmployeeNo = substr($maxEmployeeNo, 1); // remove the "E" prefix
            $maxEmployeeNo = intval($maxEmployeeNo); // convert to integer
            $newEmployeeNo = $maxEmployeeNo + 1; // increment by 1
            $newEmployeeNo = str_pad($newEmployeeNo, 4, "0", STR_PAD_LEFT); // add leading zeros
            $newEmployeeNo = "E" . $newEmployeeNo; // add the "E" prefix
        }
    } else {
        echo "0 results";
    }

    echo $newEmployeeNo;

    $conn->close();
?>
