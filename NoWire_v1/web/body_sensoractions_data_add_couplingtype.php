<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 2/12/15
 * Time: 06:55
 */
session_start();
require_once "functions.php";

echo '
        <div id="sensAction_addCouplingtype_alert" class="alert alert-info">To select a coupling type, you can simply click on the item in the table.</div>
        <table id="sensAction_addCouplingtype_table" class="table table-hover-color table-condensed tableHeadBack-blue">
            <thead>
                <tr>
                    <th>Condition</th>
                    <th>Action</th>
                    <th class="invis">ID</th>
                </tr>
            </thead>

            <tbody>
';
require 'dbconn.php';

$sql = "SELECT `koppelingstype`.`ID`, `koppelingstype`.`voorwaarde`, `koppelingstype`.`actie` FROM NoWire.koppelingstype;";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    while($row = $result->fetch_assoc()){
        $koppelingstype_id = $row["ID"];
        $koppelingstype_vw = $row["voorwaarde"];
        $koppelingstype_act = $row["actie"];

        echo "
        <tr>
        <td>$koppelingstype_vw</td>
        <td>$koppelingstype_act</td>
        <td class=\"invis\">$koppelingstype_id</td>
        </tr>";

    }
}

$conn->close();
echo '
            </tbody>
        </table>';