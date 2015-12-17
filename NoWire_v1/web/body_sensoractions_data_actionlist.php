<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 2/12/15
 * Time: 07:03
 */
session_start();
require_once "functions.php";

echo '<table class="table table-hover-color table-condensed tableHeadBack-blue">
                                                    <thead>
                                                        <td colspan="2"></td>
                                                        <td colspan="4" class="linkSource"><strong>Source</strong></td>
                                                        <td colspan="4" class="linkTarget"><strong>Target</strong></td>
                                                    </thead>
                                                    <thead>
                                                    <tr>
                                                        <th>Coupling</th>
                                                        <th>Description</th>
                                                        <th>Module</th>
                                                        <th>Description</th>
                                                        <th>Type</th>
                                                        <th>Value</th>
                                                        <th>Module</th>
                                                        <th>Description</th>
                                                        <th>Type</th>
                                                        <th>Value</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
';


require 'dbconn.php';

$uID;
if(isset($_SESSION['uID'])){
    $uID = $_SESSION['uID'];
} else {
    $uID = 12;
}

$sql = "
	SELECT `sensor_koppeling`.`ID`,
    `sensor_koppeling`.`beschrijving` as descr,
	sUser.IDgebruiker as sUserID,
	sMod.description as sModDescr,
	sMod.moduleIdentifier as sModIdent,
    sSource.description as sDescr,
    tSource.beschrijving as sType,
    `sensor_koppeling`.`source_trigger_value` as sVal,
	concat_ws('/', 'sensors', sMod.moduleIdentifier, tSource.`topic`) as sTopic,
	tUser.IDgebruiker as tUserID,
	tMod.description as tModDescr,
	tMod.moduleIdentifier as tModIdent,
    sTarget.description as tDescr,
    tTarget.beschrijving as tType,
    `sensor_koppeling`.`target_assign_value` as tVal,
	concat_ws('/', 'sensors', tMod.moduleIdentifier, tTarget.`topic`) as tTopic,
    `koppelingstype`.`voorwaarde`,
    `koppelingstype`.`actie`
    FROM NoWire.sensor_koppeling
    LEFT JOIN `NoWire`.`sensor` sSource ON sSource.ID = `sensor_koppeling`.`IDsensorBron`
    LEFT JOIN `NoWire`.`sensor` sTarget ON sTarget.ID = `sensor_koppeling`.`IDsensorDoel`
    LEFT JOIN `NoWire`.`sensortype` tSource ON tSource.ID = sSource.IDtype
    LEFT JOIN `NoWire`.`sensortype` tTarget ON tTarget.ID = sTarget.IDtype
    LEFT JOIN `NoWire`.`koppelingstype` ON `koppelingstype`.`ID` = `sensor_koppeling`.`IDkoppelingstype`
	LEFT JOIN `NoWire`.`wifimodule_gebruikers` sUser ON sSource.IDwifimodule = sUser.`IDwifimodule`
	LEFT JOIN `NoWire`.`wifimodule_gebruikers` tUser ON sTarget.IDwifimodule = tUser.`IDwifimodule`
	LEFT JOIN `NoWire`.`wifimodule` sMod ON sUser.IDwifimodule = sMod.`ID`
	LEFT JOIN `NoWire`.`wifimodule` tMod ON tUser.IDwifimodule = tMod.`ID`
	WHERE ( (sUser.IDgebruiker='12' OR sUser.IDgebruiker='$uID') AND (tUser.IDgebruiker='12' OR tUser.IDgebruiker='$uID') )
    ";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    while($row = $result->fetch_assoc()){
        $sensorcoupling_descr = $row["descr"];
        $sensorcoupling_sDescr = $row["sDescr"];
        $sensorcoupling_sType = $row["sType"];
        $sensorcoupling_sVal = $row["sVal"];
        $sensorcoupling_tDescr = $row["tDescr"];
        $sensorcoupling_tType = $row["tType"];
        $sensorcoupling_tVal = $row["tVal"];
        $sensorcoupling_cond = $row["voorwaarde"];
        $sensorcoupling_action = $row["actie"];
        $sensorcoupling_sModDescr = $row["sModDescr"];
        $sensorcoupling_sModIdent = $row["sModIdent"];
        $sensorcoupling_tModDescr = $row["tModDescr"];
        $sensorcoupling_tModIdent = $row["tModIdent"];
        $sensorcoupling_sUID = intval($row["sUserID"]);
        $sensorcoupling_tUID = intval($row["tUserID"]);



        echo "
        <tr>
        <td>$sensorcoupling_descr</td>
        <td>$sensorcoupling_cond - $sensorcoupling_action</td>";
        if($sensorcoupling_sUID == 12){
            echo '<td><span class="badge badge-orange">'; echo "$sensorcoupling_sModDescr - $sensorcoupling_sModIdent"; echo'</span></td>';
        } else {
            echo '<td><span class="badge badge-blue">'; echo "$sensorcoupling_sModDescr - $sensorcoupling_sModIdent"; echo'</span></td>';
        }

        echo "
        <td>$sensorcoupling_sDescr</td>
        <td>$sensorcoupling_sType</td>
        <td>$sensorcoupling_sVal</td>";
        if($sensorcoupling_tUID == 12){
            echo '<td><span class="badge badge-orange">'; echo "$sensorcoupling_tModDescr - $sensorcoupling_tModIdent"; echo'</span></td>';
        } else {
            echo '<td><span class="badge badge-blue">'; echo "$sensorcoupling_tModDescr - $sensorcoupling_tModIdent"; echo'</span></td>';
        }

        echo "
        <td>$sensorcoupling_tDescr</td>
        <td>$sensorcoupling_tType</td>
        <td>$sensorcoupling_tVal</td>
        </tr>";
        //<td>$sensorcoupling_cond</td>
        //<td>$sensorcoupling_action</td>

    }
}

$conn->close();


echo '
                                                    </tbody>
                                                </table>';