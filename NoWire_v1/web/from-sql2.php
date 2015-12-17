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

require "dbconn.php";

$sensorSelectedDataview = intval($conn->real_escape_string($_GET['mod']));
if($sensorSelectedDataview == 0){
    $sensorSelectedDataview = intval($_SESSION['moduleDataSelected']);
}

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



// set UTC time

// set some utility variables
$range = $end - $start;
$startTime = strftime('%Y-%m-%d %H:%M:%S', $start / 1000);
$endTime = strftime('%Y-%m-%d %H:%M:%S', $end / 1000);



$sql = "
SELECT fromOnline, toOnline FROM NoWire.wifimodule_data WHERE IDwifimodule = '$sensorSelectedDataview'
  AND (fromOnline between '$startTime' and '$endTime')
ORDER BY fromOnline
    ";

$result = $conn->query($sql);


$rows = array();
while ($row = $result->fetch_assoc()) {
    extract($row);

    //$value,$from,$to
    $timestampFrom = strtotime($fromOnline);
    $timestampTo = strtotime($toOnline);

/*    if($timestampTo == 0){
        $timestampTo = time.time() * 1000;
    }

    for($x = $timestampFrom; $x < $timestampTo; $x+=5){
        //$newTime = gmstrftime('%Y-%m-%d %H:%M:%S', $x);
        $newTime = $x * 1000;
        $rows[] = "[$newTime, 1]";
    }*/

    if($toOnline === NULL){
        $newTime = ($timestampFrom-1) * 1000;
        $rows[] = "[$newTime, 0]";

        $newTime = ($timestampFrom) * 1000;
        $rows[] = "[$newTime, 1]";

        for($x = $timestampFrom; $x < (time()-1800); $x+=1800){
            //$newTime = gmstrftime('%Y-%m-%d %H:%M:%S', $x);
            $newTime = $x * 1000;
            $rows[] = "[$newTime, 1]";
        }

        $newTime = time() * 1000;
        $rows[] = "[$newTime, 1]";

    } else {
        $newTime = ($timestampFrom - 1) * 1000;
        $rows[] = "[$newTime, 0]";

        for ($x = $timestampFrom; $x < ($timestampTo-1800); $x += 1800) {
            //$newTime = gmstrftime('%Y-%m-%d %H:%M:%S', $x);
            $newTime = $x * 1000;
            $rows[] = "[$newTime, 1]";
        }
        $newTime = ($timestampTo) * 1000;
        $rows[] = "[$newTime, 1]";
        $newTime = ($timestampTo + 1) * 1000;
        $rows[] = "[$newTime, 0]";
    }


}

$conn->close();

// print it
header('Content-Type: text/javascript');

echo "/* console.log(' start = $start, end = $end, startTime = $startTime, endTime = $endTime '); */";
echo $callback ."([\n" . join(",\n", $rows) ."\n]);";
