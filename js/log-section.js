document.addEventListener("DOMContentLoaded", function () {
    const logTable = document.getElementById('logTable');
    const messageElement2 = document.getElementById('general-message2');

    document.querySelector('#clearLogButton').addEventListener('click', function(event) {
        event.preventDefault();

        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Handle the response here if necessary
                messageElement2.innerHTML = 'Log cleared. Refresh to see changes.'
            }
        };
        xhr.open("GET", "../scripts/fetch-get/clear-log.php", true);
        xhr.send();
    });

    // Add a click event listener to the button
    document.querySelector('#refreshButton2').addEventListener('click', function () {
        // Send an AJAX request to fetch the updated data
        let xhr = new XMLHttpRequest();
        xhr.open('GET', '../scripts/fetch-get/fetch-log.php');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let logs = JSON.parse(xhr.responseText);
                let tableBody = document.querySelector('#logTable tbody');
                tableBody.innerHTML = ''; // Clear the existing table rows
                for (let i = 0; i < logs.length; i++) {
                    let row = '<tr>' +
                        '<td>' + logs[i].timestamp + '</td>' +
                        '<td>' + logs[i].operation + '</td>' +
                        '<td>' + logs[i].object + '</td>' +
                        '<td>' + logs[i].details + '</td>' +
                        '</tr>';
                    tableBody.innerHTML += row; // Append a new table row
                }

                messageElement2.innerHTML = 'Refreshed.';
            }
        };
        xhr.send();
    });

    document.querySelector('#exportButton').addEventListener('click', saveToFile);
});

