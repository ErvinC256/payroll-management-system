<?php 
    include '../scripts/connectdb.php'; 

    // Set the page name to a session variable
    $_SESSION['page'] = 'Dashboard';

    // Check if the username value is set in the session
    if (!isset($_SESSION['username'])) {
        // Redirect back to index.php
        header('Location: ../index.php');
        exit;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>PHP Payroll</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styles/button-8.css">
    <link rel="stylesheet" href="../styles/modal.css">
    <link rel="stylesheet" href="../styles/scrollbars.css">
    <link rel="stylesheet" href="../styles/forms.css">
    <link rel="stylesheet" href="../styles/base.css">
    <style>
    .chart-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        flex-direction: column;
    }
    .chart-container iframe {
        width: 48%;
        height: 400px;
        margin-bottom: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        display: block;
    }
    .chart-container iframe .chart-content {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
    .chart-navigation {
        display: flex;
        justify-content: center;
        margin-top: 10px;
    }
    .chart-navigation button {
        border: none;
        margin: 0 5px;
    }
    .label {
        background: #EDF7F6;
        text-align: center;
        display: inline-block;
        width: 240px;
        font-weight: bold;
        font-size: 14px;
        padding: 20px;
        border: 3px solid #000000;
        border-radius: 10px;
        margin-bottom: 15px;
    }
    img {
        width: 10px;
        height: 10px;
        border-radius: 20px;
        margin: 0 auto;
        display: block;
    }
</style>

</head>
<body>
    <?php include '../includes/sidebar.php'; ?>
    <main>
        <p style="text-align: right; font-style: italic; position: relative; bottom: 10px;">logged in as <?php echo $_SESSION['username']; ?></p>
        <h2>Dashboard</h2>
        <hr>
        <table>
            <tr>
                <td>
                    <div class="label">
                        Total employees: <span id="totalEmployees"></span>
                    </div>
                </td>
                <td>
                    <div class="label">
                        Branch of department: <span id="totalBranches"></span>
                    </div>
                </td>
                <td>
                    <div class="label">
                        Total salary: <span id="totalSalary"></span>
                    </div>
                </td>
                <td>
                    <div class="label">
                        Total overtime: <span id="totalOvertimeRate"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan=2>
                <h3 style="margin-bottom: 10px; text-align: center;">Column Chart</h3>
                    <div class="chart-container">
                        <iframe src="../analysis/columnchart.php" id="columnChartFrame" frameborder="0" style="width: 500px;"></iframe>
                    </div>
                    <div>
                        <button onclick="previousColumnChart()"><img src="../images/back.png"></button>
                        <button onclick="nextColumnChart()"><img src="../images/next.png"></button>
                    </div>
                </td>
                <td colspan=2>
                    <h3 style="margin-bottom: 10px; text-align: center;">Doughnut Chart</h3>
                    <div class="chart-container">
                        <iframe src="../analysis/doughnutchart.php" id="doughnutChartFrame" frameborder="0" style="width: 500px;"></iframe>
                    </div>
                    <div>
                        <button onclick="previousDoughnutChart()"><img src="../images/back.png"></button>
                        <button onclick="nextDoughnutChart()"><img src="../images/next.png"></button>
                    </div>
                </td>
            </tr>
        </table>
        <br><br><br>
    </main>
    
    <script>
        var columnChartFrame = document.getElementById('columnChartFrame');
        var doughnutChartFrame = document.getElementById('doughnutChartFrame');

        var columnChartIndex = 0;
        var doughnutChartIndex = 0;

        function previousColumnChart() {
            columnChartIndex--;
            if (columnChartIndex < 0) {
                columnChartIndex = 2; // Adjust the total number of charts for column chart
            }
            columnChartFrame.src = getColumnChartUrl(columnChartIndex);
        }

        function nextColumnChart() {
            columnChartIndex++;
            if (columnChartIndex > 2) { // Adjust the total number of charts for column chart
                columnChartIndex = 0;
            }
            columnChartFrame.src = getColumnChartUrl(columnChartIndex);
        }

        function previousDoughnutChart() {
            doughnutChartIndex--;
            if (doughnutChartIndex < 0) {
                doughnutChartIndex = 2; // Adjust the total number of charts for doughnut chart
            }
            doughnutChartFrame.src = getDoughnutChartUrl(doughnutChartIndex);
        }

        function nextDoughnutChart() {
            doughnutChartIndex++;
            if (doughnutChartIndex > 2) { // Adjust the total number of charts for doughnut chart
                doughnutChartIndex = 0;
            }
            doughnutChartFrame.src = getDoughnutChartUrl(doughnutChartIndex);
        }

        function getColumnChartUrl(index) {
            var charts = [
                '../analysis/columnchart.php',
                '../analysis/columnchart2.php',
                '../analysis/columnchart3.php'
            ];
            return charts[index];
        }

        function getDoughnutChartUrl(index) {
            var charts = [
                '../analysis/doughnutchart.php',
                '../analysis/doughnutchart2.php',
                '../analysis/doughnutchart3.php'
            ];
            return charts[index];
        }

        // Function to fetch data and update labels
        function fetchData() {
            fetch('../analysis/label.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalEmployees').textContent = data.totalEmployees;
                    document.getElementById('totalBranches').textContent = data.totalBranches;
                    document.getElementById('totalSalary').textContent = data.totalSalary;
                    document.getElementById('totalOvertimeRate').textContent = data.totalOvertimeRate;
                })
                .catch(error => {
                    console.log(error);
                });
        }

        // Call the fetchData function to populate labels initially
        fetchData();
    </script>
</body>
</html>
