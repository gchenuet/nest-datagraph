google.charts.load('current', {'packages':['corechart', 'bar']});
//Load Charts
google.charts.setOnLoadCallback(leafChart);
google.charts.setOnLoadCallback(heatingChart);
google.charts.setOnLoadCallback(presenceChart);
google.charts.setOnLoadCallback(tempChart);
google.charts.setOnLoadCallback(humChart);

function leafChart() {

    var jsonData = $.ajax({
        url: "php/getLeaf.php?start="+start_date+"&end="+end_date+"",
        dataType: "json",
        async: false
    }).responseText;


    var data = new google.visualization.DataTable(jsonData);

    var options = {
        hAxis: {title: 'Days', format: 'MMM d, y'},
        vAxis: {title: 'Leaf', minValue: 0, ticks: [{v:0, f:'Missed'}, {v:1, f:'Acquired'}]},
        colors: ['#9BC645']
    };

    var chart = new google.visualization.AreaChart(document.getElementById('nest-leaf'));
    chart.draw(data, options);
}

function heatingChart() {

    var jsonData = $.ajax({
        url: "php/getHeat.php?start="+start_date+"&end="+end_date+"",
        dataType: "json",
        async: false
    }).responseText;


    var data = new google.visualization.DataTable(jsonData);

    var options = {
        hAxis: {title: 'Days', format: 'MMM d, y'},
        vAxis: {title: 'Hours', minValue: 0, maxValue: 24, gridlines: {count: -1}, viewWindow: {min: 1},},
        colors: ['#E54725', '#9BC645'],
        isStacked: true,
        bar: {groupWidth: "20"},
    };

    var chart = new google.visualization.SteppedAreaChart(document.getElementById('nest-heat'));
    chart.draw(data, options);
}

function presenceChart() {

    var jsonData = $.ajax({
        url: "php/getPresence.php?start="+start_date+"&end="+end_date+"",
        dataType: "json",
        async: false
    }).responseText;

    var data = new google.visualization.DataTable(jsonData);

    var options = {
        hAxis: {title: 'Days', format: 'MMM d, y'},
        vAxis: {title: 'Hours', minValue: 0, maxValue: 24},
        colors: ['#00afd8', '#7b858e'],
        isStacked: true,
        bar: {groupWidth: "20"},
    };

    var chart = new google.visualization.SteppedAreaChart(document.getElementById('nest-away'));
    chart.draw(data, options);
}

function tempChart() {
    var jsonData = $.ajax({
        url: "php/getTemp.php?start="+start_date+"&end="+end_date+"",
        dataType: "json",
        async: false
    }).responseText;

    var options = {
        title: 'Temperature History',
        vAxis: { title: 'Temperature (Celsius)'},
        hAxis: { title: 'Date',format: 'MMM d, H:mm'},
        legend: { position: 'bottom' },
        colors: ['#7b858e', '#00afd8', '#E54725']
    };

    var data = new google.visualization.DataTable(jsonData);
    var chart = new google.visualization.LineChart(document.getElementById('nest-temp'));

    chart.draw(data, options);
}

function humChart() {
    var jsonData = $.ajax({
        url: "php/getHum.php?start="+start_date+"&end="+end_date+"",
        dataType: "json",
        async: false
    }).responseText;

    var options = {
        title: 'Humidity History',
        vAxis: { title: 'Humidity (%)'},
        hAxis: { title: 'Date', format: 'MMM d, H:mm'},
        legend: { position: 'bottom' },
        colors: ['#7b858e', '#00afd8', '#E54725']
    };

    var data = new google.visualization.DataTable(jsonData);
    var chart = new google.visualization.LineChart(document.getElementById('nest-hum'));

    chart.draw(data, options);
}

function updateData() {
    $.getJSON( "php/getEnergyStats.php?start="+start_date+"&end="+end_date+"", function( json ) {
        document.getElementById("heating-value").textContent=json[0].IS_HEATING;
        document.getElementById("leaf-value").textContent=json[0].LEAF;
        document.getElementById("home-value").textContent=json[0].AT_HOME;
        document.getElementById("away-value").textContent=json[0].AUTO_AWAY;
    });
}
