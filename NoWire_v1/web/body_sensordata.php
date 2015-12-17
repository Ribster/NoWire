<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 27/11/15
 * Time: 02:49
 */
//start the session
session_start();
require_once "functions.php";

require "dbconn.php";

$sensdata_sensorID = intval($conn->real_escape_string($_GET['s']));
$sensdata_wifi = intval($conn->real_escape_string($_GET['w']));

if($sensdata_sensorID == 0){
    // get the first sensor ID


    // do query and select values

    $sql;

    if(isset($_SESSION['uID'])){
        $idVar = $_SESSION['uID'];

        $sql = "
    SELECT `sensor`.`ID`, `sensor`.`IDwifimodule` FROM `NoWire`.`sensor`
    LEFT JOIN `NoWire`.`wifimodule_gebruikers` ON `sensor`.`IDwifimodule` = `wifimodule_gebruikers`.`IDwifimodule`
    WHERE `wifimodule_gebruikers`.`IDgebruiker` = 12 OR `wifimodule_gebruikers`.`IDgebruiker` = $idVar
    ORDER BY `wifimodule_gebruikers`.`IDgebruiker` DESC, `sensor`.`description` ASC
    LIMIT 1
    ";
    } else {
        $sql = "
    SELECT `sensor`.`ID`, `sensor`.`IDwifimodule` FROM `NoWire`.`sensor`
    LEFT JOIN `NoWire`.`wifimodule_gebruikers` ON `sensor`.`IDwifimodule` = `wifimodule_gebruikers`.`IDwifimodule`
    WHERE `wifimodule_gebruikers`.`IDgebruiker` = 12
    ORDER BY `wifimodule_gebruikers`.`IDgebruiker` DESC, `sensor`.`description` ASC
    LIMIT 1
    ";
    }


    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        if($row = $result->fetch_assoc()){
            $sensdata_sensorID = intval($row["ID"]);
            $sensdata_wifi = intval($row["IDwifimodule"]);
        }
    }

    $conn->close();
}

$_SESSION['sensorDataSelected'] = intval($sensdata_sensorID);
$_SESSION['moduleDataSelected'] = intval($sensdata_wifi);

$sensorSelectedDataview = intval($_SESSION['sensorDataSelected']);
$moduleSelectedDataview = intval($_SESSION['moduleDataSelected']);


echo '
<div class="col-md-12">
    <div class="panel panel-group panel-grey">



            <div id="pubData">

            </div>';

if(isset($_SESSION['uID'])){
    //echo '<div class="panel-heading margin-bottom-5 listheading">Private</div>';
}


echo '
            <div class="barGrey"></div>
            <div id="privData">

            </div>
            <div class="barGrey"></div>
            <div id="sensorControls">

                <div class="druktoets">


                ';
require 'dbconn.php';
$sql = "
            SELECT
            `sensor`.`description`,
            `sensor`.`value`,
            `sensortype`.`beschrijving`,
            `sensortype`.`sieenheid`,
            `sensortype`.`label`,
            `sensorsoort`.`soort`,
            `sensor`.`ID`,
			`wifimodule`.`ID` as wifiID
            FROM NoWire.sensor
            LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID`
            LEFT JOIN `NoWire`.`sensorsoort` ON `sensortype`.`soort` = `sensorsoort`.`ID`
			LEFT JOIN `NoWire`.`wifimodule` ON `sensor`.`IDwifimodule` = `wifimodule`.`ID`
            WHERE `sensor`.`ID` = $sensorSelectedDataview;
            ";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    if ($row = $result->fetch_assoc()) {
        $modulelist_description = $row["description"];
        $modulelist_value = $row["value"];
        $modulelist_beschrijving = $row["beschrijving"];
        $modulelist_sieenheid = $row["sieenheid"];
        $modulelist_label = $row["label"];
        $modulelist_soort = $row["soort"];
        $modulelist_sensID = $row["ID"];
        $modulelist_wifi = $row["wifiID"];
    }


    if($modulelist_soort == "sensor"){
        /*echo '
              <button class="btn btn-orange disabled btn-sm">
              <span class="fa fa-hdd-o"></span>&nbsp;
              ';
        echo 'SENSOR';
        echo '</button>';*/
    } else if ($modulelist_soort == "licht"){
        // this is an output
        //echo '<strong>Toggle Output: </strong>';
        echo '<a onclick="toggleOutput(';
        echo "$sensorSelectedDataview, $moduleSelectedDataview";
        echo ');"><button class="btn btn-pink btn-sm">
                        <span class="fa fa-bolt"></span>&nbsp;
                        ';
        echo 'TOGGLE OUTPUT';
        echo '</button></a>';
    } else if ($modulelist_soort == "schakelaar"){
        //echo '<strong>Toggle Input: </strong>';
        echo '
                        <button class="btn btn-blue btn-sm">
                        <span class="fa fa-power-off"></span>&nbsp;
                        ';
        echo 'TOGGLE INPUT';
        echo '</button>';

        echo '
                        <button class="btn btn-blue btn-sm">
                        <span class="fa fa-power-off"></span>&nbsp;
                        ';
        echo 'TRIGGER INPUT';
        echo '</button>';
    }

    echo '
        <a href="index.php?p=2&m=';
    echo $modulelist_wifi;
    echo '">
        <button class="btn btn-green btn-sm">
        <span class="fa fa-hdd-o"></span>&nbsp;
        ';
    echo 'WIFI MODULE';
    echo '</button></a>';


}

$conn->close();

echo '
                </div>


            </div>
            <div class="barGrey"></div>
            <div id="sensorDataRealtime" class="bckGrey"></div>
    </div>
</div>
';