function saveToFile() {
    var table = document.getElementById('logTable');
    var rows = table.querySelectorAll('tr');

    // Check if there are rows in the table
    if (rows.length <= 1) {
        return;
    }

    // Get the form input values
    var exportForm = document.getElementById('export-form');
    var filename = exportForm.elements.filename.value;
    var remarks = exportForm.elements.remarks.value;
    var fileformat = exportForm.elements.fileformat.value; // Get the selected save as type

    window.jsPDF = window.jspdf.jsPDF;

    if (fileformat === 'pdf') {
        // Save as PDF
        // Modify the code to save as PDF based on your preferred method
        // You can use a library like jsPDF or an API like PDFKit to generate the PDF

        // Example code using jsPDF:
        var doc = new jsPDF();
        var pageHeight = doc.internal.pageSize.height;

        // Create an array to hold the table data
        var tableData = [];

        // Loop through each row of the table
        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].querySelectorAll('th, td');
            var rowContent = [];

            // Loop through each cell of the row
            for (var j = 0; j < cells.length; j++) {
                rowContent.push(cells[j].innerText);
            }

            // Add the row data to the table data array
            tableData.push(rowContent);
        }

        // Generate a table in the PDF document using the autoTable plugin
        doc.autoTable({
            head: [tableData.shift()],
            body: tableData,
            startY: 10,
            margin: { bottom: 30 }
        });

        // Add a gap row between the data and title/remarks
        var finalY = doc.lastAutoTable.finalY;
        doc.text('', 10, finalY + 10);

        // Add remarks to the end of the PDF content
        finalY += 10;
        doc.text('Remarks: ' + remarks, 10, finalY);

        // Save the PDF file
        doc.save(filename + '.pdf');
    } else if (fileformat === 'csv') {
        // Save as CSV
        var csvContent = "data:text/csv;charset=utf-8,";

        // Loop through each row of the table
        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].querySelectorAll('th, td');
            var rowContent = [];

            // Loop through each cell of the row
            for (var j = 0; j < cells.length; j++) {
                rowContent.push(cells[j].innerText);
            }

            // Join the row's cell data with commas and add a new line
            var csvRow = rowContent.join(',');
            csvContent += csvRow + "\r\n";
        }

        // Add a gap row between the data and title/remarks
        csvContent += "\r\n";

        // Add remarks to the end of the CSV content
        csvContent += 'Remarks,' + remarks + "\r\n";

        // Create a temporary link element to download the CSV file
        var link = document.createElement('a');
        link.setAttribute('href', encodeURI(csvContent));
        link.setAttribute('download', filename + '.csv');
        document.body.appendChild(link);

        // Trigger the link to automatically download the CSV file
        link.click();

        // Clean up by removing the temporary link element
        document.body.removeChild(link);
    }
}
