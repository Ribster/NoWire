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
$getSens = intval($conn->real_escape_string($_GET['sens']));
// get information from database

if($getSens == 0){

    if($getID == 0 || $getMod == 0){
        echo json_encode( array( "typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"","moduleIdentifier"=>"","moduleDescription"=>"","sensDescription"=>"" ) );
    } else {
        // do query and select values

        $sql = "SELECT concat_ws('/', 'sensors', `wifimodule`.`moduleIdentifier`, '') as topic, `wifimodule`.`moduleIdentifier`, `wifimodule`.`description` FROM NoWire.wifimodule WHERE `wifimodule`.`ID`=$getMod;";

        $result = $conn->query($sql);

        if ($result->num_rows >0) {
            // output data of each row
            if($row = $result->fetch_assoc()){
                $uinfo_prefixtopic = $row["topic"];
                $uinfo_modident = $row["moduleIdentifier"];
                $uinfo_descrMod = $row["description"];


                $sql = "
                SELECT `sensortype`.`beschrijving`, `sensortype`.`sieenheid`, `sensortype`.`topic`, `sensorsoort`.`soort`, `sensortype`.`label`
                FROM NoWire.sensortype
                LEFT JOIN `NoWire`.`sensorsoort` ON `sensortype`.`soort` = `sensorsoort`.`ID`
                WHERE `sensortype`.`ID`=$getID;
                ";

                $result = $conn->query($sql);

                if ($result->num_rows >0) {
                    // output data of each row
                    if($row = $result->fetch_assoc()){
                        $uinfo_descr = $row["beschrijving"];
                        $uinfo_unit = $row["sieenheid"];
                        $uinfo_topic = $row["topic"];
                        $uinfo_sort = $row["soort"];
                        $uinfo_label = $row["label"];
                        echo json_encode( array( "typeID"=>"$getID","descr"=>"$uinfo_descr","unit"=>"$uinfo_unit","topic"=>"$uinfo_prefixtopic$uinfo_topic","type"=>"$uinfo_sort","label"=>"$uinfo_label","moduleIdentifier"=>"$uinfo_modident","moduleDescription"=>"$uinfo_descrMod","sensDescription"=>"" ) );
                    } else {
                        echo json_encode( array( "typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"","moduleIdentifier"=>"","moduleDescription"=>"","sensDescription"=>"" ) );
                    }
                } else {
                    echo json_encode( array( "typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"","moduleIdentifier"=>"","moduleDescription"=>"","sensDescription"=>"" ) );
                }

            } else {
                echo json_encode( array( "typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"","moduleIdentifier"=>"","moduleDescription"=>"","sensDescription"=>"" ) );
            }
        } else {
            echo json_encode( array( "typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"","moduleIdentifier"=>"","moduleDescription"=>"","sensDescription"=>"" ) );
        }

    }


    $conn->close();
} else {
    if($getID == 0 || $getMod == 0){
        echo json_encode( array( "typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"","moduleIdentifier"=>"","moduleDescription"=>"","sensDescription"=>"" ) );
    } else {
        // do query and select values

        $sql = "
            SELECT concat_ws('/', 'sensors', `wifimodule`.`moduleIdentifier`, '') as topic, `wifimodule`.`moduleIdentifier`, `wifimodule`.`description`, `sensor`.`description` as sensDes
            FROM NoWire.wifimodule
            LEFT JOIN `NoWire`.`sensor` ON `sensor`.`IDwifimodule` = `wifimodule`.`ID`
            WHERE `sensor`.`ID`=$getSens";

        $result = $conn->query($sql);

        if ($result->num_rows >0) {
            // output data of each row
            if($row = $result->fetch_assoc()){
                $uinfo_prefixtopic = $row["topic"];
                $uinfo_modident = $row["moduleIdentifier"];
                $uinfo_descrMod = $row["description"];
                $uinfo_descrSens = $row["sensDes"];

                $sql = "
                SELECT `sensortype`.`beschrijving`, `sensortype`.`sieenheid`, `sensortype`.`topic`, `sensorsoort`.`soort`, `sensortype`.`label`
                FROM NoWire.sensortype
                LEFT JOIN `NoWire`.`sensorsoort` ON `sensortype`.`soort` = `sensorsoort`.`ID`
                WHERE `sensortype`.`ID`=$getID;
                ";

                $result = $conn->query($sql);

                if ($result->num_rows >0) {
                    // output data of each row
                    if($row = $result->fetch_assoc()){
                        $uinfo_descr = $row["beschrijving"];
                        $uinfo_unit = $row["sieenheid"];
                        $uinfo_topic = $row["topic"];
                        $uinfo_sort = $row["soort"];
                        $uinfo_label = $row["label"];
                        echo json_encode( array( "typeID"=>"$getID","descr"=>"$uinfo_descr","unit"=>"$uinfo_unit","topic"=>"$uinfo_prefixtopic$uinfo_topic","type"=>"$uinfo_sort","label"=>"$uinfo_label","moduleIdentifier"=>"$uinfo_modident","moduleDescription"=>"$uinfo_descrMod","sensDescription"=>"$uinfo_descrSens" ) );
                    } else {
                        echo json_encode( array( "typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"","moduleIdentifier"=>"","moduleDescription"=>"","sensDescription"=>"" ) );
                    }
                } else {
                    echo json_encode( array( "typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"","moduleIdentifier"=>"","moduleDescription"=>"","sensDescription"=>"" ) );
                }

            } else {
                echo json_encode( array( "typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"","moduleIdentifier"=>"","moduleDescription"=>"","sensDescription"=>"" ) );
            }
        } else {
            echo json_encode( array( "typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>"","moduleIdentifier"=>"","moduleDescription"=>"","sensDescription"=>"" ) );
        }

    }


    $conn->close();
}
