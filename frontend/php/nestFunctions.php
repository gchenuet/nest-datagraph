<?php

function getLastRecord() {
    $ini = parse_ini_file(realpath("../config/settings.ini"), true);
    $connection = mysqli_connect($ini['mysql']['mysql_hostname'],$ini['mysql']['mysql_username'],$ini['mysql']['mysql_password'],$ini['mysql']['mysql_database'])
        or die("Connection Error " . mysqli_error($connection));
    $sql = "SELECT * FROM status ORDER BY id DESC LIMIT 1;";
    $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
    $sql_array = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $sql_array[] = $row;
    }
    mysqli_close($connection);
    return json_encode($sql_array);
}


function getDayRecord() {
    $ini = parse_ini_file(realpath("../config/settings.ini"), true);
    date_default_timezone_set($ini['common']['timezone']);
    $date = date('Y-m-d H:i:s', time());
    $connection = mysqli_connect($ini['mysql']['mysql_hostname'],$ini['mysql']['mysql_username'],$ini['mysql']['mysql_password'],$ini['mysql']['mysql_database'])
        or die("Connection Error " . mysqli_error($connection));
    $sql = "SELECT * from status WHERE (DATE(date) BETWEEN (date_sub(date('$date'), INTERVAL 1 DAY)) AND CURDATE());";
    $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));
    $sql_array = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $sql_array[] = $row;
    }
    mysqli_close($connection);
    return json_encode($sql_array);
}

function getMessages($protects) {
	$status = array();
	foreach ($protects['protect'] as $protect) {
		$status[$protect['smoke_status']][] = array("name" => $protect['name'], "description" => $protect['description'], "type" => "smoke_status");
		$status[$protect['co_status']][] = array("name" => $protect['name'], "description" => $protect['description'],"type" => "co_status");
		$status[$protect['battery_status']][] = array("name" => $protect['name'], "description" => $protect['description'],"type" => "battery_status");
	}
	return json_encode($status);
}

function printStatus($protects) {
	$messages = json_decode(getMessages($protects), true);
	
	$status_map = array(
		1 => "warning",
		2 => "warning",
		3 => "danger",
		4 => "danger"
	);
	
	$msg_map = array(
		"" => ""	
	);
	
	$protect = array();
	echo "<div class='list-group'>";
	for ($n = 4; $n >= 1; $n--) {
		foreach($messages[$n] as $m) {
			if (in_array($m['description'], $protect) == false) {
				echo "<a href='#' class='list-group-item list-group-item-".$status_map[$n]."'>";
				echo "<img src='images/icon_emergency.png' style='height=10% width=10%' class='img-responsive' alt='Emergency'>";
				echo "<strong>".$m['name']." (".$m['description'].")</strong>:".$m['type']."</a>";
				array_push($protect, $m['description']);
			} 
		}
	}
	echo "</div>";
}

?>

