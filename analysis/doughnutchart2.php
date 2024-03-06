<!DOCTYPE HTML>
<html>
<head>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script>
window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title: {
            text: "Employee",
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

    fetch('doughnutchartana2.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(row => {
                var yearHired = row.year_hired;
                var employeeCount = parseInt(row.count);
                chart.options.data[0].dataPoints.push({ label: yearHired, y: employeeCount });
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
