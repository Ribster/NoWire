<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 25/11/15
 * Time: 16:58
 */
//start the session
session_start();
require "functions.php";

require "dbconn.php";

$getIDSensor = intval($conn->real_escape_string($_GET['id']));
$sensorSelectedDataview = intval($_SESSION['sensorDataSelected']);

    // do query for value, unit and type


    if($getIDSensor == 0 && $sensorSelectedDataview == 0){


        echo json_encode( array( "value"=>"","unit"=>"","type"=>"","totVal"=>"","online"=>"" ) );
    } else {
        // do query and select values

        if($getIDSensor == 0){
            $getIDSensor = $sensorSelectedDataview;
        }

        $sql = "
SELECT `sensor`.`value`, `sensortype`.`sieenheid`, `sensorsoort`.`soort`, `wifimodule`.`online`
    FROM `NoWire`.`sensor`
    LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID`
    LEFT JOIN `NoWire`.`sensorsoort` ON `sensortype`.`soort` = `sensorsoort`.`ID`
	LEFT JOIN `NoWire`.`wifimodule` ON `sensor`.`IDwifimodule` = `wifimodule`.`ID`
    WHERE `sensor`.`ID` = $getIDSensor
    ";

        $result = $conn->query($sql);

        if ($result->num_rows >0) {
            // output data of each row
            if($row = $result->fetch_assoc()){
                $uinfo_val = floatval($row["value"]);
                $uinfo_sie = $row["sieenheid"];
                $uinfo_soort = $row["soort"];
                $uinfo_online = intval($row["online"]);

                $uinfo_TotVal = getFormattedValue($uinfo_soort, $uinfo_val, $uinfo_sie);

                if($uinfo_online == 0){
                    $uinfo_val = 0;
                }

                echo json_encode( array( "value"=>"$uinfo_val","unit"=>"$uinfo_sie","type"=>"$uinfo_soort","totVal"=>"$uinfo_TotVal","online"=>"$uinfo_online" ) );
            } else {
                echo json_encode( array( "value"=>"","unit"=>"","type"=>"","totVal"=>"","online"=>"" ) );
            }
        } else {
            echo json_encode( array( "value"=>"","unit"=>"","type"=>"","totVal"=>"","online"=>"" ) );
        }

    }

    $conn->close();