<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 13/11/15
 * Time: 16:10
 */
// start the session
session_start();

require "dbconn.php";

$getID = intval($conn->real_escape_string($_GET['id']));
$getMod = intval($conn->real_escape_string($_GET['mod']));
// get information from database



    if($getID == 0 || $getMod == 0){
        echo json_encode( array( "typeID"=>"$getID","beschr"=>"","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"" ) );
    } else {
        // do query and select values

        $sql = "SELECT concat_ws('/', 'sensors', `wifimodule`.`moduleIdentifier`, '') as topic FROM NoWire.wifimodule WHERE `wifimodule`.`ID`=$getMod;";

        $result = $conn->query($sql);

        if ($result->num_rows >0) {
            // output data of each row
            if($row = $result->fetch_assoc()){
                $uinfo_prefixtopic = $row["topic"];


                $sql = "
                SELECT `sensor`.`ID`, `sensor`.`description`, `sensortype`.`beschrijving`, `sensortype`.`sieenheid`, `sensortype`.`topic`, `sensorsoort`.`soort`, `sensortype`.`label`
                FROM NoWire.sensor
                LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID`
                LEFT JOIN `NoWire`.`sensorsoort` ON `sensortype`.`soort` = `sensorsoort`.`ID`
                WHERE `sensor`.`ID`=$getID;
                ";

                $result = $conn->query($sql);

                if ($result->num_rows >0) {
                    // output data of each row
                    if($row = $result->fetch_assoc()){
                        $uinfo_description = $row["description"];
                        $uinfo_descr = $row["beschrijving"];
                        $uinfo_unit = $row["sieenheid"];
                        $uinfo_topic = $row["topic"];
                        $uinfo_sort = $row["soort"];
                        $uinfo_label = $row["label"];
                        echo json_encode( array( "typeID"=>"$getID","beschr"=>"$uinfo_description","descr"=>"$uinfo_descr","unit"=>"$uinfo_unit","topic"=>"$uinfo_prefixtopic$uinfo_topic","type"=>"$uinfo_sort","label"=>"$uinfo_label" ) );
                    } else {
                        echo json_encode( array( "typeID"=>"$getID","beschr"=>"","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"" ) );
                    }
                } else {
                    echo json_encode( array( "typeID"=>"$getID","beschr"=>"","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"" ) );
                }

            } else {
                echo json_encode( array( "typeID"=>"$getID","beschr"=>"","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"" ) );
            }
        } else {
            echo json_encode( array( "typeID"=>"$getID","beschr"=>"","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"" ) );
        }

    }


    $conn->close();