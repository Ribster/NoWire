<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 27/11/15
 * Time: 19:08
 */
//start the session
session_start();

$sensorSelectedDataview = intval($_SESSION['sensorDataSelected']);

// get name from sensor
// get point name from sensor
require "dbconn.php";

$sql = "SELECT `sensor`.`description` as naam, `sensortype`.`beschrijving`, `sensortype`.`sieenheid`, `sensortype`.`topic`, `sensortype`.`label`,
`sensorsoort`.`soort`, `wifimodule`.`description`, `wifimodule`.`moduleIdentifier`
FROM NoWire.sensor
LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID`
LEFT JOIN `NoWire`.`sensorsoort` ON `sensortype`.`soort` = `sensorsoort`.`ID`
LEFT JOIN `NoWire`.`wifimodule` ON `sensor`.`IDwifimodule` = `wifimodule`.`ID`
WHERE `sensor`.`ID` = $sensorSelectedDataview;
";
$result = $conn->query($sql);


if ($result->num_rows >0) {

    if ($row = $result->fetch_assoc()) {
        $uinfo_label = $row["label"];
        $uinfo_si = $row["sieenheid"];
        $uinfo_descr = $row["naam"];
        $uinfo_soort = $row["soort"];
        $uinfo_beschr = $row["beschrijving"];
        $uinfo_module = $row["moduleIdentifier"];

        if($uinfo_soort == "sensor"){
            echo json_encode( array( "sensID"=>"$sensorSelectedDataview","label"=>"$uinfo_si - $uinfo_label",
                "title"=>"$uinfo_module - $uinfo_descr - $uinfo_label","minVal"=>"0","maxVal"=>"-1","pointName"=>"$uinfo_label [$uinfo_si]","timeIncrement"=>"2500","soort"=>"$uinfo_soort","kleur"=>"#d65116" ) );
        } else if ($uinfo_soort == "licht") {
            echo json_encode( array( "sensID"=>"$sensorSelectedDataview","label"=>"STATE",
                "title"=>"$uinfo_module - $uinfo_descr - $uinfo_label","minVal"=>"0","maxVal"=>"1","pointName"=>"STATE","timeIncrement"=>"2000","soort"=>"$uinfo_soort","kleur"=>"#bf3773" ) );
        } else if ($uinfo_soort == "schakelaar") {
            echo json_encode( array( "sensID"=>"$sensorSelectedDataview","label"=>"STATE",
                "title"=>"$uinfo_module - $uinfo_descr - $uinfo_label","minVal"=>"0","maxVal"=>"1","pointName"=>"STATE","timeIncrement"=>"2000","soort"=>"$uinfo_soort","kleur"=>"#0a819c" ) );
        }


    }
}

$conn->close();

