<?php
$ini = parse_ini_file("params.ini", true);
date_default_timezone_set($ini['common']['timezone']);
$date = date('Y-m-d H:i:s', time());
$connection = mysqli_connect($ini['mysql']['mysql_hostname'],$ini['mysql']['mysql_username'],$ini['mysql']['mysql_password'],$ini['mysql']['mysql_database'])
    or die("Connection Error " . mysqli_error($connection));

$sql = "SELECT SUM(nest_heat_state) AS IS_HEATING, SUM(if(auto_away = '1', 0, 1)) AS AT_HOME_HOURS, SUM(if(auto_away = '1', 1, 0)) AS AUTO_AWAY_HOURS, 
        (SELECT COUNT(*) AS leaf FROM (SELECT leaf FROM status WHERE leaf = 1 AND (DATE(date) BETWEEN (date('".$_GET['start']."')) AND (date('".$_GET['end']."'))) 
        GROUP BY date(date) ORDER BY date ASC) status) AS LEAF FROM status WHERE (DATE(date) BETWEEN (date('".$_GET['start']."')) AND (date('".$_GET['end']."')));";

//$sql = "select SUM(nest_heat_state) AS IS_HEATING, @AHH:=SUM(if(auto_away = '1', 1, 0)) AS AT_HOME_HOURS, @AAH:=SUM(if(auto_away = '1', 0, 1)) AS AUTO_AWAY_HOURS, 
//		@TDIFF:=ROUND(@AAH+@AHH) AS TIME_DIFF, ROUND((@AHH/@TDIFF)*100) AS AT_HOME_PERC, 
//		ROUND((@AAH/@TDIFF)*100) AS AUTO_AWAY_PERC, (SELECT COUNT(*) AS leaf FROM (SELECT leaf FROM status WHERE leaf = 1 
//		AND (DATE(date) BETWEEN (date('".$_GET['start']."')) AND (date('".$_GET['end']."'))) GROUP BY date(date) ORDER BY date ASC) status) AS LEAF from status 
//		WHERE (DATE(date) BETWEEN (date('".$_GET['start']."')) AND (date('".$_GET['end']."')));";

$result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
$rows = array();
while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}
$total = $rows[0]["AT_HOME_HOURS"] + $rows[0]["AUTO_AWAY_HOURS"];
$rows[0]["AT_HOME_PERC"] = strval(round(($rows[0]["AT_HOME_HOURS"]/$total)*100));
$rows[0]["AUTO_AWAY_PERC"] = strval(round(($rows[0]["AUTO_AWAY_HOURS"]/$total)*100));
$rows[0]["HEATING_PERC"] = strval(round(($rows[0]["IS_HEATING"]/$total)*100));
mysqli_close($connection);
echo json_encode($rows);
?>


