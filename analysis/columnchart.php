<!DOCTYPE HTML>
<html>
<head>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script>
window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        theme: "light2",
        title: {
            text: "Overtime Total"
        },
        axisY: {
            title: "Overtime Rate"
        },
        axisX: {
            title: "Department Code"
        },
        data: [{
            type: "column",
            dataPoints: []
        }]
    });

    fetch('columnchartana.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(row => {
                var departmentCode = row.department_code;
                var overtimeTotal = parseFloat(row.overtime_total);
                chart.options.data[0].dataPoints.push({ label: departmentCode, y: overtimeTotal });
            });

            chart.render();
        })
        .catch(error => {
            console.log(error);
        });
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
</body>
</html>
