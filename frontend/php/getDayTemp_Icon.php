<?php
$ini = parse_ini_file("params.ini", true);
date_default_timezone_set($ini['common']['timezone']);
$date = date('Y-m-d H:i:s', time());
$connection = mysqli_connect($ini['mysql']['mysql_hostname'],$ini['mysql']['mysql_username'],$ini['mysql']['mysql_password'],$ini['mysql']['mysql_database'])
    or die("Connection Error " . mysqli_error($connection));
//$sql = "SELECT date, cur_weather_icon from status WHERE (DATE(date) BETWEEN (date_sub(date('$date'), INTERVAL 1 DAY)) AND CURDATE());";
$sql = "SELECT date, cur_weather_icon, cur_weather_status_detail from status WHERE (MINUTE(DATE) < 1 AND DATE(date) BETWEEN (date_sub(date('$date'), INTERVAL 1 DAY)) AND CURDATE())";
$result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
//$sql_array = array("label"=>"Date", "type"=>"datetime"),array("label"=>"WICON","type"=>"string"), 
                                   ;
                                   
$lasticon = "";
while($row =mysqli_fetch_assoc($result))
{
	$phpdate = strtotime($row['date']);
//	$dateicon = "date('Y', $phpdate).",".(date('n', $phpdate)-1).",".date('d', $phpdate).",".date('H', $phpdate).",".date('i', $phpdate)."";
  	$dateicon = "".date('d', $phpdate)."-".(date('n', $phpdate))."-".date('Y', $phpdate)." ".date('H', $phpdate).":".date('i', $phpdate)."";

$thisicon = $row[cur_weather_status_detail];
if ($thisicon <> $lasticon)
	{  
   echo "<img title='".$row[cur_weather_status_detail]." | ".$dateicon."'width='2.3%' src='http://openweathermap.org/img/w/".$row[cur_weather_icon].".png'>
    ";
	} else{
   echo "<img title='".$row[cur_weather_status_detail]." | ".$dateicon."'width='2.3%' src='/images/1x1.gif'>
    ";
    };	 
	$lasticon = $thisicon;  
}
mysqli_close($connection);
?>
