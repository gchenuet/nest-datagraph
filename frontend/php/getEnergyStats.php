<?php
$ini = parse_ini_file("params.ini", true);
date_default_timezone_set($ini['common']['timezone']);
$date = date('Y-m-d H:i:s', time());
$connection = mysqli_connect($ini['mysql']['mysql_hostname'],$ini['mysql']['mysql_username'],$ini['mysql']['mysql_password'],$ini['mysql']['mysql_database'])
    or die("Connection Error " . mysqli_error($connection));

$sql = "SELECT SUM(nest_heat_state) AS IS_HEATING, SUM(if(auto_away = '1', 1, 0)) AS AT_HOME, SUM(if(auto_away = '0', 1, 0)) AS AUTO_AWAY, 
        (SELECT COUNT(*) AS leaf FROM (SELECT leaf FROM status WHERE leaf = 1 AND (DATE(date) BETWEEN (date('".$_GET['start']."')) AND (date('".$_GET['end']."'))) 
        GROUP BY date(date)) status) AS LEAF FROM status WHERE (DATE(date) BETWEEN (date('".$_GET['start']."')) AND (date('".$_GET['end']."')));";

$result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
$rows = array();
while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}
mysqli_close($connection);
echo json_encode($rows);
?>
