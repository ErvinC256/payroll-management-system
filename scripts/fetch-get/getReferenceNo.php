<?php
    include '../connectdb.php';

    $sql = "SELECT MAX(reference_no) as maxReferenceNo FROM payroll";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $maxReferenceNo = $row["maxReferenceNo"];
            $maxReferenceNo = substr($maxReferenceNo, 3); // remove the "REF" prefix
            $maxReferenceNo = intval($maxReferenceNo); // convert to integer
            $newReferenceNo = $maxReferenceNo + 1; // increment by 1
            $newReferenceNo = str_pad($newReferenceNo, 3, "0", STR_PAD_LEFT); // add leading zeros
            $newReferenceNo = "REF" . $newReferenceNo; // add the "REF" prefix
        }
    } else {
        echo "0 results";
    }

    echo $newReferenceNo;

    $conn->close();
?>
