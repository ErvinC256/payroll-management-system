function updateTime() {
    var currentdate = new Date();
    var hours = currentdate.getHours();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12;
    var minutes = currentdate.getMinutes();
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var datetime = "Current Date and Time: " + currentdate.getDate() + "/"
                    + (currentdate.getMonth()+1)  + "/"
                    + currentdate.getFullYear() + " @ "
                    + hours + ":"
                    + minutes + " "
                    + ampm;
    document.getElementById("time").innerHTML = datetime;
}

updateTime();
setInterval(updateTime, 1000);