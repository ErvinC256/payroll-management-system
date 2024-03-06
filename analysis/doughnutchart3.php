<!DOCTYPE HTML>
<html>
<head>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script>
window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title: {
            text: "Salary of the Employees",
            horizontalAlign: "center"
        },
        data: [{
            type: "doughnut",
            startAngle: 60,
            //innerRadius: 60,
            indexLabelFontSize: 17,
            indexLabel: "{label} - #percent%",
            toolTipContent: "<b>{label}:</b> {y} (#percent%)",
            dataPoints: []
        }]
    });

    fetch('doughnutchartana3.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(row => {
                var gender = row.gender;
                var salarySum = parseFloat(row.salary_sum);
                chart.options.data[0].dataPoints.push({ label: gender, y: salarySum });
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
