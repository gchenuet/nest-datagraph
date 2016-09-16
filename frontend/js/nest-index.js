google.charts.load('current', {'packages':['corechart', 'gauge']});
//Load Charts
google.charts.setOnLoadCallback(targetGauge);
google.charts.setOnLoadCallback(intGauge);
google.charts.setOnLoadCallback(extGauge);
google.charts.setOnLoadCallback(dayTempChart);
google.charts.setOnLoadCallback(dayHumChart);

function targetGauge() {

    var options = {
        redFrom: 50, redTo: 60,
        yellowFrom:40, yellowTo: 50,
        minorTicks: 1, max: 60,
    };


    var jsonData = $.ajax({
        url: "php/getTargetTemp.php",
        dataType: "json",
        async: false
    }).responseText;

    var data = new google.visualization.DataTable(jsonData);

    var chart = new google.visualization.Gauge(document.getElementById('target-gauge'));

    chart.draw(data, options);
}

function intGauge() {

    var jsonData = $.ajax({
        url: "php/getIntTemp.php",
        dataType: "json",
        async: false
    }).responseText;

    var options = {
        redFrom: 50, redTo: 60,
        yellowFrom:40, yellowTo: 50,
        minorTicks: 1, max: 60
    };

    var data = new google.visualization.DataTable(jsonData);

    var chart = new google.visualization.Gauge(document.getElementById('int-gauge'));

    chart.draw(data, options);
}

function extGauge() {

    var jsonData = $.ajax({
        url: "php/getExtTemp.php",
        dataType: "json",
        async: false
    }).responseText;

    var options = {
        redFrom: 50, redTo: 60,
        yellowFrom:40, yellowTo: 50,
        minorTicks: 1, max: 60
    };

    var data = new google.visualization.DataTable(jsonData);

    var chart = new google.visualization.Gauge(document.getElementById('ext-gauge'));

    chart.draw(data, options);
}

function dayTempChart() {
    var jsonData = $.ajax({
        url: "php/getDayTemp.php",
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
    var chart = new google.visualization.LineChart(document.getElementById('nest-daytemp'));

    chart.draw(data, options);
    
    var TempDayTarget = document.getElementById("TempDayTarget");
	TempDayTarget.onclick = function()
	{
      view = new google.visualization.DataView(data);
      view.hideColumns([1]); 
      chart.draw(view, options);
   	}

    var TempDayInt = document.getElementById("TempDayInt");
	TempDayInt.onclick = function()
	{
		view = new google.visualization.DataView(data);
		view.hideColumns([2]); 
		chart.draw(view, options);
   }
 
    var TempDayExt = document.getElementById("TempDayExt");
	TempDayExt.onclick = function()
	{
		view = new google.visualization.DataView(data);
		view.hideColumns([3]); 
		chart.draw(view, options);
   }

    var TempDayAll = document.getElementById("TempDayAll");
	TempDayAll.onclick = function()
	{
		view = new google.visualization.DataView(data);
		chart.draw(view, options);
   }

}

function dayHumChart() {
    var jsonData = $.ajax({
        url: "php/getDayHum.php",
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
    var chart = new google.visualization.LineChart(document.getElementById('nest-dayhum'));

    chart.draw(data, options);
    
    var HumDayTarget = document.getElementById("HumDayTarget");
	HumDayTarget.onclick = function()
	{
      view = new google.visualization.DataView(data);
      view.hideColumns([1]); 
      chart.draw(view, options);
   	}

    var HumDayInt = document.getElementById("HumDayInt");
	HumDayInt.onclick = function()
	{
		view = new google.visualization.DataView(data);
		view.hideColumns([2]); 
		chart.draw(view, options);
   }
 
    var HumDayExt = document.getElementById("HumDayExt");
	HumDayExt.onclick = function()
	{
		view = new google.visualization.DataView(data);
		view.hideColumns([3]); 
		chart.draw(view, options);
   }

    var HumDayAll = document.getElementById("HumDayAll");
	HumDayAll.onclick = function()
	{
		view = new google.visualization.DataView(data);
		chart.draw(view, options);
   }


}
