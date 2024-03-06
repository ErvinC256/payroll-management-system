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
            text: "Bonus Total"
        },
        axisY: {
            title: "Bonus Eligible"
        },
        axisX: {
            title: "Department Code"
        },
        data: [{
            type: "column",
            dataPoints: []
        }]
    });

    fetch('columnchartana2.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(row => {
                var departmentCode = row.department_code;
                var bonusTotal = parseFloat(row.bonus_total);
                chart.options.data[0].dataPoints.push({ label: departmentCode, y: bonusTotal });
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
