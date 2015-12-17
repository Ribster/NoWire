<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 2/12/15
 * Time: 06:57
 */
session_start();
require_once "functions.php";



echo '
        <div id="sensAction_addCouplingtarget_alert" class="alert alert-info">To select a coupling target, you can simply click on the item in the table.</div>
        <table id="sensAction_addCouplingtarget_table" class="table table-hover-color table-condensed tableHeadBack-blue">
            <thead>
                <tr>
                    <th>Access</th>
                    <th>Module Identifier</th>
                    <th>Module Description</th>
                    <th>Sensor Type</th>
                    <th>Sensor Description</th>
                    <th>Sensor Category</th>
                    <th>IPv4</th>
                    <th class="invis">ID</th>
                </tr>
                </thead>
                <tbody>
';


require 'dbconn.php';

$sql = "
    SELECT `sensor`.`ID` as id,
    `sensortype`.`beschrijving` as typee,
    `sensor`.`description` as descr,
    `sensorsoort`.`soort` as soort,
    `wifimodule`.`moduleIdentifier`,
	`wifimodule`.`description` as wifimod,
    inet_NTOA(`wifimodule`.`ipv4`) as ipv4,
    `wifimodule_gebruikers`.`IDgebruiker` as wifiID,
    `wifimodule`.`ID` as moduleID,
    `sensortype`.`ID` as typeID
    FROM `NoWire`.`sensor`
    LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID`
    LEFT JOIN `NoWire`.`wifimodule` ON `wifimodule`.`ID` = `sensor`.`IDwifimodule`
    LEFT JOIN `NoWire`.`wifitype` ON `wifimodule`.`IDtype` = `wifitype`.`ID`
    LEFT JOIN `NoWire`.`sensorsoort` ON `sensortype`.`soort` = `sensorsoort`.`ID`
	LEFT JOIN `NoWire`.`wifimodule_gebruikers` ON `wifimodule`.`ID` = `wifimodule_gebruikers`.`IDwifimodule`
	WHERE (`wifimodule_gebruikers`.`IDgebruiker` = 12";

if(isset($_SESSION['uID'])){
    $uID = intval($_SESSION['uID']);
    $sql = "$sql OR `wifimodule_gebruikers`.`IDgebruiker` = $uID) ";
} else {
    $sql = "$sql )";
}
$sql = "$sql AND `sensortype`.`topic` = 'outputdig' ";

$sql = "$sql ORDER BY soort ASC, wifimod ASC, typee ASC";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    while($row = $result->fetch_object()){
        echo "
        <tr>
        ";
        if($row->wifiID == 12){
            echo '<td><span class="badge badge-orange">PUBLIC</span></td>';
        } else {
            echo '<td><span class="badge badge-blue">PRIVATE</span></td>';
        }
        echo "
        <td>$row->moduleIdentifier</td>
        <td>$row->wifimod</td>
        <td>$row->typee</td>
        <td>$row->descr</td>
        <td>$row->soort</td>
        <td>$row->ipv4</td>

        ";
        echo "
        <td class=\"invis\">$row->id, $row->moduleID, $row->typeID</td>
        </tr>";

    }
}

$conn->close();

echo '
            </tbody>
        </table>';