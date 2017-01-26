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
        vAxis: {title: 'Hours', minValue: 0, maxValue: 24, gridlines: {count: -1}, viewWindow: {min: 1, max: 24},},
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
        hAxis: {title: 'Days', format: 'MMM d, H:mm'},
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
    
        
    var TempFullTarget = document.getElementById("TempFullTarget");
	TempFullTarget.onclick = function()
	{
    	view = new google.visualization.DataView(data);
		view.hideColumns([1]); 
		chart.draw(view, options);
   	}

    var TempFullInt = document.getElementById("TempFullInt");
	TempFullInt.onclick = function()
	{
		view = new google.visualization.DataView(data);
		view.hideColumns([2]); 
		chart.draw(view, options);
   }
 
    var TempFullExt = document.getElementById("TempFullExt");
	TempFullExt.onclick = function()
	{
		view = new google.visualization.DataView(data);
		view.hideColumns([3]); 
		chart.draw(view, options);
   }

    var TempFullAll = document.getElementById("TempFullAll");
	TempFullAll.onclick = function()
	{
		view = new google.visualization.DataView(data);
		chart.draw(view, options);
   }
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
    
    var HumFullTarget = document.getElementById("HumFullTarget");
	HumFullTarget.onclick = function()
	{
     	view = new google.visualization.DataView(data);
	  	view.hideColumns([1]); 
	  	chart.draw(view, options);
   	}

    var HumFullInt = document.getElementById("HumFullInt");
	HumFullInt.onclick = function()
	{
		view = new google.visualization.DataView(data);
		view.hideColumns([2]); 
		chart.draw(view, options);
   }
 
    var HumFullExt = document.getElementById("HumFullExt");
	HumFullExt.onclick = function()
	{
		view = new google.visualization.DataView(data);
		view.hideColumns([3]); 
		chart.draw(view, options);
   }

    var HumFullAll = document.getElementById("HumFullAll");
	HumFullAll.onclick = function()
	{
		view = new google.visualization.DataView(data);
		chart.draw(view, options);
   }
}

function updateData() {
    $.getJSON( "php/getEnergyStats.php?start="+start_date+"&end="+end_date+"", function( json ) {
       	document.getElementById("heating-perc-value").title=json[0].IS_HEATING+"h";
        document.getElementById("heating-perc-value").textContent=json[0].HEATING_PERC;
        document.getElementById("leaf-value").textContent=json[0].LEAF;
        document.getElementById("home-perc-value").textContent=json[0].AT_HOME_PERC;
        document.getElementById("away-perc-value").textContent=json[0].AUTO_AWAY_PERC;
        document.getElementById("home-perc-value").title=json[0].AT_HOME_HOURS+"h";
        document.getElementById("away-perc-value").title=json[0].AUTO_AWAY_HOURS+"h";
    });
}
