<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 27/11/15
 * Time: 22:09
 */
//start the session
session_start();
require "dbconn.php";
$fullInfo = intval($conn->real_escape_string($_GET['f']));

$sensorSelectedDataview = intval($_SESSION['sensorDataSelected']);

class datapoint {
    public $x = "";
    public $y  = "";
}

$resultArray = array();

if($fullInfo == 1){


    $sql = "
SELECT `sensor_data`.`ID`,
`sensor_data`.`IDsensor`,
`sensor_data`.`value`,
`sensor_data`.`from`,
`sensor_data`.`to`
FROM NoWire.sensor_data
WHERE `sensor_data`.`IDsensor`=$sensorSelectedDataview
    ";

    $result = $conn->query($sql);

    // print prefix

    $eerste = True;


    if ($result->num_rows >0) {

        $lastTime;
        $lastValue;

        while ($row = $result->fetch_assoc()) {
            $uinfo_val = floatval($row["value"]);
            $uinfo_from = $row["from"];
            $uinfo_to = $row["to"];
            $unixFrom = strtotime( $uinfo_from );
            $unixTo;

            if($uinfo_to == ""){
                $unixTo = time();
            } else {
                $unixTo = strtotime( $uinfo_to );
            }


            if(($uinfo_from == $lastTime) && ($uinfo_val != $lastValue)){
                $datPoint = new datapoint();
                //$datPoint->x = 'Date.UTC('.date_format($unixFrom, 'Y,m,d,H,i,s').')';
                $datPoint->x = ($unixFrom * 1000)+1;
                $datPoint->y = $uinfo_val;

                array_push($resultArray, $datPoint);
            } else if($uinfo_from == $lastTime){
                // the same value
            } else {
                $datPoint = new datapoint();
                //$temptime = date_format($unixFrom, 'Y,m,d,H,i,s');
                //$datPoint->x = 'Date.UTC('. $temptime .')';
                $datPoint->x = $unixFrom * 1000;
                $datPoint->y = $uinfo_val;

                array_push($resultArray, $datPoint);
            }

            $lastTime = $uinfo_from;
            $lastValue = $uinfo_val;


            if ( ( $uinfo_from != $unixTo) && ($uinfo_to != "") ){



                if(($unixTo == $lastTime) && ($uinfo_val != $lastValue)){
                    $datPoint = new datapoint();
                    $datPoint->x = ($unixTo * 1000)+1;
                    $datPoint->y = $uinfo_val;

                    array_push($resultArray, $datPoint);
                } else if($unixTo == $lastTime){
                    // the same value
                } else {
                    $datPoint = new datapoint();
                    $datPoint->x = $unixTo * 1000;
                    $datPoint->y = $uinfo_val;

                    array_push($resultArray, $datPoint);
                }

                $lastTime = $unixTo;
                $lastValue = $uinfo_val;
            } else if ($uinfo_to == "") {
                $datPoint1 = new datapoint();
                $datPoint1->x = ($unixFrom * 1000) + 1;
                $datPoint1->y = 0;
                array_push($resultArray, $datPoint1);

                $datPoint = new datapoint();
                $datPoint->x = time() * 1000;
                $datPoint->y = 0;

                array_push($resultArray, $datPoint);
            }


        }

    }


    echo json_encode($resultArray);

    $conn->close();

} else {

// get a list of values with from and to timestamp
    require "dbconn.php";

    $sql = "
SELECT `sensor_data`.`ID`,
`sensor_data`.`IDsensor`,
`sensor_data`.`value`,
`sensor_data`.`from`,
`sensor_data`.`to`
FROM NoWire.sensor_data
WHERE `sensor_data`.`IDsensor`=$sensorSelectedDataview AND (TIMESTAMPDIFF(SECOND, `sensor_data`.`from`, NOW())<61 OR TIMESTAMPDIFF(SECOND, `sensor_data`.`to`, NOW())<61)
    ";
    $result = $conn->query($sql);



    if ($result->num_rows >0) {

        while($row = $result->fetch_assoc()){
            $uinfo_val = $row["value"];
            $uinfo_from = $row["from"];
            $uinfo_to = $row["to"];
            $unixFrom = strtotime( $uinfo_from );
            $unixTo;

            if($uinfo_to == ""){
                $unixTo = time();
            } else {
                $unixTo = strtotime( $uinfo_to );
            }

            $timediff = $unixTo-$unixFrom;

            //$noGreater = time() - 0;

            if($timediff > 120){
                $unixFrom = time() - 120;
                $timediff = 120;
            }

            if($timediff > 0){
                for ($x = 0; $x < $timediff; $x++) {
                    // strech the value out over 500ms increments



                    //}


                }
            }


        }
    } else {
        $sql = "SELECT `sensor_data`.`ID`,
`sensor_data`.`IDsensor`,
`sensor_data`.`value`,
`sensor_data`.`from`,
`sensor_data`.`to`,
(TIMESTAMPDIFF(SECOND, `sensor_data`.`from`, `sensor_data`.`to`))
as diff FROM NoWire.sensor_data
WHERE `sensor_data`.`IDsensor`=$sensorSelectedDataview ORDER BY `sensor_data`.`from` DESC LIMIT 1;";
        $result = $conn->query($sql);
        if ($result->num_rows >0) {
            if($row = $result->fetch_assoc()){
                $uinfo_val = $row["value"];
                $uinfo_from = $row["from"];
                $unixTo = time();

                $timediff = $unixTo-$unixFrom;

                if($timediff > 120){
                    $unixFrom = time() - 120;
                    $timediff = 120;
                }

                for ($x = 0; $x < $timediff; $x++) {
                    // strech the value out over 500ms increments


                    $datPoint = new datapoint();
                    $datPoint->x = ($unixFrom + $x)*1000;
                    $datPoint->y = $uinfo_val;

                    array_push($resultArray, $datPoint);


                }
            }
        }
    }

    echo json_encode($resultArray);

    $conn->close();
}
