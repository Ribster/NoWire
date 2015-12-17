<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 26/11/15
 * Time: 00:02
 */
//start the session
session_start();

require "dbconn.php";

$moduleExisting = intval($conn->real_escape_string($_GET['e']));
$moduleFree = intval($conn->real_escape_string($_GET['f']));
$moduleOnline = $conn->real_escape_string($_GET['s']);

if($moduleExisting != 0){
    // do query for value, unit and type


        // do query and select values

        $sql = "
SELECT `wifimodule`.`moduleIdentifier` as naam, `wifimodule`.`online`, (SELECT COUNT(*) FROM (SELECT `sensor`.`ID` FROM NoWire.sensor
LEFT JOIN `NoWire`.`wifimodule` ON `sensor`.`IDwifimodule` = `wifimodule`.`ID`
WHERE `wifimodule`.`ID` = '$moduleExisting') as counttabl ) as senscount  FROM NoWire.wifimodule WHERE `wifimodule`.`ID` = '$moduleExisting';
    ";

        $result = $conn->query($sql);

        if ($result->num_rows >0) {
            // output data of each row
            if($row = $result->fetch_assoc()){
                $uinfo_name = $row["naam"];
                $uinfo_online = $row["online"];
                $uinfo_sensorcount = $row["senscount"];
                echo json_encode( array( "name"=>"$uinfo_name","online"=>"$uinfo_online","sensors"=>"$uinfo_sensorcount","ID"=>"$moduleExisting" ) );
            } else {
                echo json_encode( array( "name"=>"","online"=>"","sensors"=>"","ID"=>"" ) );
            }
        } else {
            echo json_encode( array( "name"=>"","online"=>"","sensors"=>"","ID"=>"" ) );
        }

    $conn->close();
} else if ($moduleFree != 0){
    // do query for value, unit and type
    require "dbconn.php";

    // do query and select values

    $sql = "
    SELECT moduleIdentifier as naam FROM NoWire.wifimodule_online WHERE ID=$moduleFree
    ";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        if($row = $result->fetch_assoc()){
            $uinfo_name = $row["naam"];
            echo json_encode( array( "name"=>"$uinfo_name","online"=>"","sensors"=>"","ID"=>"$moduleFree" ) );
        } else {
            echo json_encode( array( "name"=>"","online"=>"","sensors"=>"","ID"=>"" ) );
        }
    } else {
        echo json_encode( array( "name"=>"","online"=>"","sensors"=>"","ID"=>"" ) );
    }

    $conn->close();
} else if ($moduleOnline != "") {
    // do query for value, unit and type
    require "dbconn.php";

    // do query and select values

    $sql = "
SELECT `wifimodule`.`moduleIdentifier` as naam, `wifimodule`.`online`, (SELECT COUNT(*) FROM (SELECT `sensor`.`ID` FROM NoWire.sensor
LEFT JOIN `NoWire`.`wifimodule` ON `sensor`.`IDwifimodule` = `wifimodule`.`ID`
WHERE `wifimodule`.`moduleIdentifier` = '$moduleOnline') as counttabl ) as senscount, `wifimodule`.`ID` as modID  FROM NoWire.wifimodule WHERE `wifimodule`.`moduleIdentifier` = '$moduleOnline';
    ";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        if($row = $result->fetch_assoc()){
            $uinfo_name = $row["naam"];
            $uinfo_online = $row["online"];
            $uinfo_sensorcount = $row["senscount"];
            $uinfo_id = $row["modID"];
            echo json_encode( array( "name"=>"$uinfo_name","online"=>"$uinfo_online","sensors"=>"$uinfo_sensorcount","ID"=>"$uinfo_id" ) );
        } else {
            echo json_encode( array( "name"=>"","online"=>"","sensors"=>"","ID"=>"" ) );
        }
    } else {
        echo json_encode( array( "name"=>"","online"=>"","sensors"=>"","ID"=>"" ) );
    }

    $conn->close();

} else {
    echo json_encode( array( "name"=>"","online"=>"","sensors"=>"","ID"=>"" ) );
}