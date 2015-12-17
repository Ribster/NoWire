<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 27/11/15
 * Time: 13:34
 */
//start the session
session_start();
require "functions.php";

$sensorSelected = intval($_SESSION['sensorDataSelected']);

if(isset($_SESSION['uID'])){
    echo '      <ul class="nav nav-pills-blue nav-justified">';
    require "dbconn.php";

// do query and select values

    $uID = $_SESSION['uID'];

    $sql = "
    SELECT `sensor`.`ID`, `sensor`.`description`, `sensor`.`value`, `sensortype`.`sieenheid`, `sensorsoort`.`soort`, `wifimodule`.`online`, `wifimodule`.`ID` as IDWIFI FROM `NoWire`.`sensor`
    LEFT JOIN `NoWire`.`wifimodule_gebruikers` ON `sensor`.`IDwifimodule` = `wifimodule_gebruikers`.`IDwifimodule`
	LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID`
	LEFT JOIN `NoWire`.`sensorsoort` ON `sensortype`.`soort` = `sensorsoort`.`ID`
	LEFT JOIN `NoWire`.`wifimodule` ON `sensor`.`IDwifimodule` = `wifimodule`.`ID`
    WHERE `wifimodule_gebruikers`.`IDgebruiker` = $uID
    ORDER BY `sensor`.`description` ASC
    ";


    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        while($row = $result->fetch_assoc()){
            $sensdata_id = $row["ID"];
            $sensdata_descr = $row["description"];
            $sensdata_val = $row["value"];
            $sensdata_siunit = $row["sieenheid"];
            $sensdata_sort = $row["soort"];
            $sensdata_online = intval($row["online"]);
            $sensdata_wifi = intval($row["IDWIFI"]);

            $sensdata_descr = ucwords($sensdata_descr);

            $sensdata_TotVal = getFormattedValue($sensdata_sort, $sensdata_val, $sensdata_siunit);

            if($sensorSelected == $sensdata_id){
                echo '<li class="active">';
            } else {
                echo '<li>';
            }
            echo '<a href="' . "index.php?p=1&s=$sensdata_id&w=$sensdata_wifi" . '"><div>';
            if($sensdata_online == 0){
                echo '<span class="fa fa-circle-o"></span>&nbsp;<strong>';
            } else {
                echo '<span class="fa fa-dot-circle-o"></span>&nbsp;<strong>';
            }
            echo "$sensdata_descr";
            echo '</strong>';
            if($sensorSelected == $sensdata_id){
                echo '<span class="badge badge-dark pull-right">';
            } else {
                if($sensdata_sort == "sensor"){
                    echo '<span class="badge badge-orange pull-right">';
                } else if($sensdata_sort == "schakelaar"){
                    echo '<span class="badge badge-blue pull-right">';
                } else if($sensdata_sort == "licht"){
                    echo '<span class="badge badge-pink pull-right">';
                }

            }
            echo "$sensdata_TotVal";
            echo '</span></div>';
            echo '</a>';
            echo '</li>';
        }
    }

    $conn->close();

    echo '</ul>';
}

