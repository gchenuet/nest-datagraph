<?php
$ini = parse_ini_file("params.ini", true);
date_default_timezone_set($ini['common']['timezone']);
$date = date('Y-m-d H:i:s', time());
$connection = mysqli_connect($ini['mysql']['mysql_hostname'],$ini['mysql']['mysql_username'],$ini['mysql']['mysql_password'],$ini['mysql']['mysql_database'])
    or die("Connection Error " . mysqli_error($connection));
$sql = "SELECT date, ROUND(SUM(auto_away)) AS auto_away FROM status WHERE (DATE(date) BETWEEN (date('".$_GET['start']."')) AND (date('".$_GET['end']."'))) 
		GROUP BY DAY(date) ORDER BY date ASC;";
$result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
$sql_array = array("cols" => array(array("label"=>"Date", "type"=>"datetime"),array("label"=>"Auto-Away","type"=>"number"), array("label"=>"At Home","type"=>"number")));
while($row =mysqli_fetch_assoc($result))
{
    $phpdate = strtotime($row['date']);
    $date_array = "Date(".date('Y', $phpdate).",".(date('n', $phpdate)-1).",".date('d', $phpdate).")";
    $sql_array["rows"][] = array("c" => array(array("v" => $date_array), array("v" => intval($row["auto_away"])), array("v" => (24 - $row["auto_away"]))));
}
mysqli_close($connection);
echo json_encode($sql_array);
?>
