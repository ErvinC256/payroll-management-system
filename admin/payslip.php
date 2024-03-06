
<!DOCTYPE html>
<html>
    <head>
        <title>Pay Slip</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            
            .container {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin-top: 20px;
            }
            
            table {
                border: 1px solid black;
                border-collapse: collapse;
                width: 50%;
            }
            
            th {
                border-bottom: 1px solid black;
                padding: 8px;
                text-align: left;
                background-color: #f2f2f2;
                font-weight: bold;
            }
            
            td {
                padding: 8px;
                text-align: left;
            }
            
            .title {
                font-size: 20px;
                font-weight: bold;
            }

            .final {
                border: 1px solid black;
            }

            /* Add vertical line */
            .separator {
                position: relative;
            }
            
            .separator::after {
                content: '';
                position: absolute;
                top: 0;
                bottom: 0;
                right: -1px;
                width: 1px;
                background-color: black;
            }

            .button-container {
                margin-top: 20px;
            }

            @media print {
                table {
                    width: 100%; /* Full width for print */
                }

                .separator::after {
                    right: 0; /* Adjust position for print */
                }
                
                .print-button {
                    display: none;
                }
            }

            .line-break {
                display: inline-block;
                height: 20px; /* Adjust the height to increase or decrease the space between lines */
            }

        </style>
    </head>
    <html>
<body>
    <div class="container">
            <h1>Pay Slip</h1>
            <table>
                <tr>
                    <th class="final">Reference No: </th>
                    <th>Payment Date:</th>
                </tr>
                <tr>
                    <td class="final">Employee No.:</td>
                    <td class="final"></td>
                </tr>
                <tr>
                    <td class="final">First Name:</td>
                    <td class="final"></td>
                </tr>
                <tr>
                    <td class="final">Last Name:</td>
                    <td class="final"></td>
                </tr>
                <tr>
                    <td class="final">NRIC:</td>
                    <td class="final"></td>
                </tr>
                <tr>
                    <td class="final">Designation:</td>
                    <td class="final"></td>
                </tr>
                <tr>
                    <td class="final">Basic Salary:</td>
                    <td></td>
                </tr>

                <tr>
                    <th class="final">Earnings/Income:</th>
                    <th class="final">Deductions:</th>
                </tr>
                <!-- Existing rows for Basic Pay and Allowance -->
                <tr>
                    <td class="final">Gross Pay:</td>
                    <td class="final">Total Deduction:</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="final">Net Pay:</td>
                </tr>
            </table>

        <div class="button-container">
            <button class="print-button" onclick="printTable()">Print</button>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
    // Get the value of the referenceNo parameter from the URL
    var referenceNo = "<?php echo $_GET['referenceNo']; ?>";

    // Make sure the referenceNo value is not empty
    if (referenceNo) {
        $.ajax({
            url: '../scripts/fetch-get/fetch-payslip.php',
            type: 'GET',
            data: { referenceNo: referenceNo },
            dataType: 'json',
            success: function(data) {
                // Update the DOM elements with the retrieved data
                $('.final:eq(0)').text("Reference No.: " + data.referenceNo);
                $('.final:eq(0)').next().text("Payment Date: " + data.paymentDate);
                $('td:eq(0)').next().text(data.employeeNo);
                $('td:eq(2)').next().text(data.firstName);
                $('td:eq(4)').next().text(data.lastName);
                $('td:eq(6)').next().text(data.nric);
                $('td:eq(8)').next().text(data.designation);
                $('td:eq(10)').next().text(data.basicSalary);
                $('td:eq(12)').text("Gross Pay: $" + data.grossPay);
                $('td:eq(13)').text("Total Deduction: $" + data.totalDeduction);
                $('td:eq(15)').text("Net Pay: $" + data.netPay);
            },
            error: function() {
                console.log("Error occurred while retrieving payslip data");
            }
        });
    } else {
        console.log("Reference No. is missing in the URL");
    }

    if (referenceNo) {
        $.ajax({
            url: '../scripts/fetch-get/fetch-earnings-deductions.php',
            type: 'GET',
            data: { referenceNo: referenceNo },
            dataType: 'json',
            success: function(data) {
                // Update the DOM elements with the retrieved data
                var earningsHTML = "Earnings/Income <br><span class='line-break'></span>";
                var deductionsHTML = "Deductions <br><span class='line-break'></span>";

                // Check if earnings data exists
                if (data.earnings.length > 0) {
                    data.earnings.forEach(function(earning, index) {
                        var item = earning.split(" ")[0];
                        var amount = earning.split(" ")[1] || 0;
                        earningsHTML += (index + 1) + ". " + item + ": $" + amount + "<br> ";
                    });
                } else {
                    earningsHTML += "No earnings data found.";
                }

                // Check if deductions data exists
                if (data.deductions.length > 0) {
                    data.deductions.forEach(function(deduction, index) {
                        var item = deduction.substring(0, deduction.lastIndexOf(" "));
                        var amount = deduction.substring(deduction.lastIndexOf(" ") + 1);
                        deductionsHTML += (index + 1) + ". " + item + ": $" + amount + "<br> ";
                    });
                } else {
                    deductionsHTML += "No deductions data found.";
                }

                // Update the DOM elements
                $('th:eq(2)').html(earningsHTML);
                $('th:eq(3)').html(deductionsHTML);
            },
            error: function() {
                console.log("Error occurred while retrieving earnings and deductions data");
            }
        });
    }
    });
    </script>
</body>
</html>