<?php
/**
 * This file loads content from four different data tables depending on the required time range.
 * The stockquotes table containts 1.7 million data points. Since we are loading OHLC data and
 * MySQL has no concept of first and last in a data group, we have extracted groups by hours, days
 * and months into separate tables. If we were to load a line series with average data, we wouldn't
 * have to do this.
 *
 * @param callback {String} The name of the JSONP callback to pad the JSON within
 * @param start {Integer} The starting point in JS time
 * @param end {Integer} The ending point in JS time
 */

// get the parameters

//start the session
session_start();

$sensorSelectedDataview = intval($_SESSION['sensorDataSelected']);

$callback = $_GET['callback'];
if (!preg_match('/^[a-zA-Z0-9_]+$/', $callback)) {
    die('Invalid callback name');
}

$start = @$_GET['start'];
if ($start && !preg_match('/^[0-9]+$/', $start)) {
    die("Invalid start parameter: $start");
}

$end = @$_GET['end'];
if ($end && !preg_match('/^[0-9]+$/', $end)) {
    die("Invalid end parameter: $end");
}
if (!$end) $end = time() * 1000;



// connect to MySQL
require "dbconn.php";


// set UTC time

// set some utility variables
$range = $end - $start;
$startTime = strftime('%Y-%m-%d %H:%M:%S', $start / 1000);
$endTime = strftime('%Y-%m-%d %H:%M:%S', $end / 1000);



$sql = "
SELECT `sensor_data`.`value`, `sensor_data`.`from`, `sensor_data`.`to`
FROM NoWire.sensor_data
WHERE `sensor_data`.`IDsensor` = $sensorSelectedDataview
  AND (`sensor_data`.`from` between '$startTime' and '$endTime')
ORDER BY `sensor_data`.`from`
    ";

$result = $conn->query($sql);


$rows = array();
while ($row = $result->fetch_assoc()) {
    extract($row);

    //$value,$from,$to
    $timestampFrom = strtotime($from);
    $timestampTo = strtotime($to);



    if($to === NULL){

        $newTime = $timestampFrom * 1000;
        $rows[] = "[$newTime, $value]";

        $newTime = time() * 1000;
        $rows[] = "[$newTime, $value]";

/*        for($x = $timestampFrom; $x < time(); $x+=5){
            //$newTime = gmstrftime('%Y-%m-%d %H:%M:%S', $x);
            $newTime = $x * 1000;
            $rows[] = "[$newTime, $value]";
        }*/
    } else {
        $newTime = $timestampFrom * 1000;
        $rows[] = "[$newTime, $value]";

        $newTime = ($timestampTo-1) * 1000;
        $rows[] = "[$newTime, $value]";
/*
        for($x = $timestampFrom; $x < $timestampTo; $x+=5){
            //$newTime = gmstrftime('%Y-%m-%d %H:%M:%S', $x);
            $newTime = $x * 1000;
            $rows[] = "[$newTime, $value]";
        }*/
    }





}

$conn->close();

// print it
header('Content-Type: text/javascript');

echo "/* console.log(' start = $start, end = $end, startTime = $startTime, endTime = $endTime '); */";
echo $callback ."([\n" . join(",\n", $rows) ."\n]);";
