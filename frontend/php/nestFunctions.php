<?php

function getLastRecord() {
    $ini = parse_ini_file("params.ini", true);
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
    $ini = parse_ini_file("params.ini", true);
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
?>
