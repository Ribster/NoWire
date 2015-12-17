<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 6/11/15
 * Time: 03:50
 */

//start the session
session_start();
require_once "functions.php";

require 'dbconn.php';

$modulelist_existing = intval($conn->real_escape_string($_GET['m']));
$modulelist_unexisting = intval($conn->real_escape_string($_GET['u']));
if(isset($_SESSION['uID'])){
    $modulelist_currentuserID = $_SESSION['uID'];
} else {
    $modulelist_currentuserID = 12;
}



// get information from sensor if access to it
    // if no access -> 0


$sql = "
SELECT
`wifimodule`.`moduleIdentifier`,
`wifimodule`.`ID`,
`wifimodule`.`online`,
(SELECT count(`sensor`.`ID`) FROM `NoWire`.`sensor` WHERE `wifimodule`.`ID` = `sensor`.`IDwifimodule`) AS sensorcount
FROM NoWire.wifimodule_gebruikers
JOIN `NoWire`.`wifimodule` ON `wifimodule_gebruikers`.`IDwifimodule` = `wifimodule`.`ID`
WHERE `wifimodule_gebruikers`.`IDgebruiker` = $modulelist_currentuserID OR `wifimodule_gebruikers`.`IDgebruiker` = 12
";

$result = $conn->query($sql);

$resultAllowed = False;

if ($result->num_rows >0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $modulelist_id = $row["ID"];
        if ($modulelist_id == $modulelist_existing){
            $resultAllowed = True;
        }
    }
}

if($resultAllowed == False){
    $modulelist_existing = 0;
}

if ($modulelist_existing != 0 && $modulelist_unexisting != 0){
    $modulelist_unexisting = 0;
}

$conn->close();
?>


<div class="col-md-12">
    <div class="panel panel-group panel-grey">
<?php
    // get if public list is empty

require 'dbconn.php';

$sql = "
SELECT count(mID) as publiccount
FROM
(SELECT
`wifimodule`.`moduleIdentifier` as mID,
`wifimodule`.`ID`,
`wifimodule`.`online`,
(SELECT count(`sensor`.`ID`) FROM `NoWire`.`sensor` WHERE `wifimodule`.`ID` = `sensor`.`IDwifimodule`) as sensorcount
FROM NoWire.wifimodule_gebruikers
LEFT JOIN `NoWire`.`wifimodule` ON `wifimodule_gebruikers`.`IDwifimodule` = `wifimodule`.`ID`
WHERE `wifimodule_gebruikers`.`IDgebruiker` = 12) as temptable
";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    if ($row = $result->fetch_assoc()) {
        $modulelist_id = $row["publiccount"];
        if ($modulelist_id > 0){
            require("body_modulelist_public.php");
        }
    }
}

$conn->close();

    // get if private list is empty

if($modulelist_currentuserID != 12){
    require 'dbconn.php';

    $sql = "
    SELECT count(mID) as privatecount
    FROM
    (SELECT
    `wifimodule`.`moduleIdentifier` as mID,
    `wifimodule`.`ID`,
    `wifimodule`.`online`,
    (SELECT count(`sensor`.`ID`) FROM `NoWire`.`sensor` WHERE `wifimodule`.`ID` = `sensor`.`IDwifimodule`) AS sensorcount
    FROM NoWire.wifimodule_gebruikers
    JOIN `NoWire`.`wifimodule` ON `wifimodule_gebruikers`.`IDwifimodule` = `wifimodule`.`ID`
    WHERE `wifimodule_gebruikers`.`IDgebruiker` = $modulelist_currentuserID) as temptable
    ";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        if ($row = $result->fetch_assoc()) {
            $modulelist_id = $row["privatecount"];
            if ($modulelist_id > 0){
                echo '<div class="barGrey"></div>';
                require("body_modulelist_private.php");
            }
        }
    }

    $conn->close();
}







    // get if unlinked list is empty
require 'dbconn.php';

$sql = "
SELECT count(onlineID) as unlinkedcount
FROM
(SELECT
`wifimodule_online`.`ID` as onlineID,
`wifimodule_online`.`moduleIdentifier`,
`wifimodule`.`ID` as moduleID
FROM NoWire.wifimodule_online
LEFT JOIN `NoWire`.`wifimodule` ON `wifimodule_online`.`moduleIdentifier` = `wifimodule`.`moduleIdentifier`
WHERE `wifimodule`.`ID` IS NULL) as temptable
";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    if ($row = $result->fetch_assoc()) {
        $modulelist_id = $row["unlinkedcount"];
        if ($modulelist_id > 0){
            echo '<div class="barGrey"></div>';
            require("body_modulelist_unlinked.php");
        }
    }
}

$conn->close();

?>
    <?php

    require 'dbconn.php';

    if($modulelist_existing != 0){
        $sql = "
        SELECT
        `wifimodule`.`ID`,
        `wifimodule`.`description`,
        `wifimodule`.`moduleIdentifier`,
        `wifitype`.`beschrijving`,
        inet_NTOA(`wifimodule`.`ipv4`) as ipv4,
        `wifimodule`.`online`
        FROM NoWire.wifimodule
        LEFT JOIN `NoWire`.`wifitype` ON `wifimodule`.`IDtype` = `wifitype`.`ID`
    ";
        } else if ($modulelist_unexisting != 0){
            $sql = "
    SELECT `wifimodule_online`.`ID`,`wifimodule_online`.`moduleIdentifier`,
    inet_NTOA(`wifimodule_online`.`ipv4`) as ipv4,
    1 as online
    FROM NoWire.wifimodule_online
    WHERE `wifimodule_online`.`ID` = $modulelist_unexisting;
    ";
    }

    if($modulelist_existing != 0 || $modulelist_unexisting != 0){
        $result = $conn->query($sql);

        if ($result->num_rows >0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                if( ($row["ID"] == $modulelist_existing) && ($modulelist_existing != 0) ){
                    $modulelist_description = $row["description"];
                    $modulelist_moduleidentif = $row["moduleIdentifier"];
                    $modulelist_beschrijving = $row["beschrijving"];
                    $modulelist_ipv4 = $row["ipv4"];
                    $modulelist_online = $row["online"];
                } else if( ($row["ID"] == $modulelist_unexisting) && ($modulelist_unexisting != 0) ){
                    $modulelist_description = $row["description"];
                    $modulelist_moduleidentif = $row["moduleIdentifier"];
                    $modulelist_beschrijving = $row["beschrijving"];
                    $modulelist_ipv4 = $row["ipv4"];
                    $modulelist_online = $row["online"];
                }

            }
        }
echo '

    <div class="barGrey"></div>
    <ul id="generalTab" class="nav nav-tabs responsive hidden-xs hidden-sm">
';

        if($modulelist_existing != 0){
            echo '
                    <li class="active"><a href="#modulelist-addsens" data-toggle="tab">Add Sensor</a></li>
                    <li><a href="#modulelist-deletesens" data-toggle="tab">Delete Sensor</a></li>
                    ';
        }

        if($modulelist_unexisting != 0){
            echo '
                    <li class="active"><a href="#modulelist-addmod" data-toggle="tab">Add Module</a></li>
                    ';
        }

        if($modulelist_existing != 0){
            echo '
                    <li><a href="#modulelist-editmod" data-toggle="tab">Edit Module</a></li>
                    <li><a href="#modulelist-deletemod" data-toggle="tab">Delete Module</a></li>
                    ';
        }
echo '

    </ul>
    <div id="generalTabContent" class="tab-content responsive hidden-xs hidden-sm margin-bottom-0 padding-top-0 padding-bottom-0">
';

        if($modulelist_existing != 0){




            echo '
                <div id="modulelist-addsens" class="tab-pane fade in active">
                    <div class="row">';

            echo '
                        <form method="POST" action="addsensor.php" class="inline-block leftf">
                        <div class="margin-horiz-10">';
                        printInputField("sensorDescription","Sensor Description", "");
            echo '
                        </div>
                        <div id="addSensorTypeInfo" class="inline-block margin-horiz-10 margin-bottom-10 invis leftf" style="vertical-align: top;">
                            <div id="selsens_type"></div>
                            <div id="selsens_description"></div>
                            <div id="selsens_unit"></div>
                            <div id="selsens_topic"></div>
                            <div id="selsens_label"></div>
                                ';
                                printHiddenInputField("module", $modulelist_existing);

                                printHiddenInputField("sensorType", "");

            echo '
                            </div>
                            <div class="form-group margin-horiz-10" >';

            echo '
                                <button type="submit" class="btn btn-green" style="vertical-align: bottom;">Add sensor</button>
                                </div>
                        </form>';



            echo '
                        <div class="inline-block margin-horiz-10 leftf" style="vertical-align: top;">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary">Sensor Selection</button>
                                <button type="button" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true" class="btn btn-primary dropdown-toggle"><i class="fa fa-angle-down"></i></button>
                                <ul class="dropdown-menu">
                                ';

            require 'dbconn.php';
            $sql = "
                SELECT `sensortype`.`ID`, `sensortype`.`beschrijving`, `sensortype`.`sieenheid`, `sensortype`.`topic`, `sensorsoort`.`soort`, `sensortype`.`label`
                FROM NoWire.sensortype
                LEFT JOIN `NoWire`.`sensorsoort` ON `sensortype`.`soort` = `sensorsoort`.`ID`
                ORDER BY `sensortype`.`beschrijving`;
                ";

            $result = $conn->query($sql);

            if ($result->num_rows >0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $tmp_bescr = $row["beschrijving"];
                    $tmp_soort = $row["soort"];
                    $tmp_unit = $row["sieenheid"];
                    $tmp_ID = $row["ID"];

                    echo '<li><a href="#" onclick="getSensorInfo(';
                    echo "$tmp_ID, $modulelist_existing";
                    echo ');return false;">';

                    if($tmp_soort == "sensor"){
                        echo '<span class="label label-orange"><span class="fa fa-hdd-o"></span>&nbsp;SENSOR</span>&nbsp;';
                    } else if($tmp_soort == "licht"){
                        echo '<span class="label label-pink"><span class="fa fa-bolt"></span>&nbsp;LIGHT</span>&nbsp;';
                    } else if($tmp_soort == "schakelaar"){
                        echo '<span class="label label-blue"><span class="fa fa-power-off"></span>&nbsp;SWITCH</span>&nbsp;';
                    }

                    echo "$tmp_bescr";
                    if(!is_null($tmp_unit)){
                        echo " - $tmp_unit";
                    }

                    echo '</a></li>';
                }
            }

            $conn->close();

            echo '
                        </ul>
                            </div>
                        </div>

                    </div>
                </div>
                <div id="modulelist-deletesens" class="tab-pane fade in">
                    <div class="row">
                        ';

            echo '
                        <form method="POST" action="deletesensor.php" class="inline-block leftf">
                        <div id="addSensorTypeInfoDel" class="inline-block margin-horiz-10 invis leftf" style="vertical-align: top;">
                            <div id="selsensDel_type"></div>
                            <div id="selsensDel_sensdescr"></div>
                            <div id="selsensDel_description"></div>
                            <div id="selsensDel_unit"></div>
                            <div id="selsensDel_topic"></div>
                            <div id="selsensDel_label"></div>
                                ';
            printHiddenInputField("module", $modulelist_existing);

            printHiddenInputField("sensorSelected", "");
            echo '
                            </div>
                            <div class="form-group margin-horiz-10" >';

            echo '
                                <button type="submit" class="btn btn-red" style="vertical-align: bottom;">Delete sensor</button>
                                </div>
                        </form>';



            echo '
                        <div class="inline-block margin-horiz-10 leftf" style="vertical-align: top;">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary">Sensor Selection</button>
                                <button type="button" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true" class="btn btn-primary dropdown-toggle"><i class="fa fa-angle-down"></i></button>
                                <ul class="dropdown-menu">
                                ';

            require 'dbconn.php';
            $sql = "
                SELECT `sensor`.`ID`, `sensor`.`description`, `sensortype`.`sieenheid`, `sensorsoort`.`soort`
                FROM NoWire.sensor
                LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID`
                LEFT JOIN `NoWire`.`sensorsoort` ON `sensortype`.`soort` = `sensorsoort`.`ID`
                WHERE `sensor`.`IDwifimodule`=$modulelist_existing;
                ";

            $result = $conn->query($sql);

            if ($result->num_rows >0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $tmp_bescr = $row["description"];
                    $tmp_soort = $row["soort"];
                    $tmp_unit = $row["sieenheid"];
                    $tmp_ID = $row["ID"];

                    echo '<li><a href="#" onclick="getSensorDeleteInfo(';
                    echo "$tmp_ID, $modulelist_existing";
                    echo ');return false;">';

                    if($tmp_soort == "sensor"){
                        echo '<span class="label label-orange"><span class="fa fa-hdd-o"></span>&nbsp;SENSOR</span>&nbsp;';
                    } else if($tmp_soort == "licht"){
                        echo '<span class="label label-pink"><span class="fa fa-bolt"></span>&nbsp;LIGHT</span>&nbsp;';
                    } else if($tmp_soort == "schakelaar"){
                        echo '<span class="label label-blue"><span class="fa fa-power-off"></span>&nbsp;SWITCH</span>&nbsp;';
                    }

                    echo "$tmp_bescr";
                    if(!is_null($tmp_unit)){
                        echo " - $tmp_unit";
                    }

                    echo '</a></li>';
                }
            }

            $conn->close();

            echo '
                        </ul>
                            </div>
                        </div>
                    </div>
                </div>
                    ';
        }


        if($modulelist_unexisting != 0){
            echo '
                <div id="modulelist-addmod" class="tab-pane fade in active">
                    <div class="row margin-horiz-10">
                        <form method="POST" action="addmodule.php" class="inline-block">
                                <div class="form-group margin-horiz-10">';
            printHiddenInputField("module", $modulelist_moduleidentif);
            echo '
                                        <input name="description" type="text" placeholder="Module description" class="form-control margin-bottom-10">
                                        <select name="wifitype" class="form-control  margin-bottom-10">
                                                            <option value="0">WiFi Type</option>';

            printWifiTypeOptions();

            echo '
                                        </select>
                                        <div class="radio margin-horiz-20  margin-bottom-10">';


            printOptionPubPriv();

            echo '
                                        </div>
                                        <button type="submit" class="btn btn-green">Add module</button>
                                </div>
                        </form>';





            echo '


                    </div>
                </div>
                    ';
        }


        if($modulelist_existing != 0){
            echo '
                <div id="modulelist-editmod" class="tab-pane fade in">
                    <div class="row">
                    <div class="row margin-horiz-10  margin-bottom-10">
                        <form method="POST" action="editmodule.php" class="inline-block">
                                <div class="form-group margin-horiz-10">';
            printHiddenInputField("module", $modulelist_moduleidentif);

            printDescriptionSelectFromModule($modulelist_existing);

            echo '

                                        <select name="wifitype" class="form-control  margin-bottom-10">
                                                            <option value="0">WiFi Type</option>';
            printWifiTypeOptionsSelectFromModule($modulelist_existing);

            echo ';
                                        </select>';

            printIpv4SelectFromModule($modulelist_existing);
            echo '
                                        <div class="radio margin-horiz-20  margin-bottom-10">';

            printOptionPubPrivSelectFromModule($modulelist_existing);

            echo '
                                        </div>
                                        <div class="radio margin-horiz-20  margin-bottom-10">';
            printOnlineOfflineSelectFromModule($modulelist_existing);
            echo '
                                        </div>
                                        <button type="submit" class="btn btn-green margin-top-20">Update Module</button>
                                </div>
                        </form>
                    </div>
                    </div>
                </div>
                <div id="modulelist-deletemod" class="tab-pane fade in">
                    <div class="row margin-horiz-10  margin-bottom-10">
                        <form method="POST" action="deletemodule.php" class="form-horizontal">';
            printHiddenInputField("module", $modulelist_existing);
            echo '
                            <button type="submit" class="btn btn-red">Delete Module</button>
                        </form>
                    </div>
                </div>
                    ';
        }

echo '
    </div>
    <div class="barGrey"></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-5">

                        <p><span id="moduleIdentif" style="font-size: 1.4em; font-weight: bold;">';
        echo "$modulelist_moduleidentif";
        echo '</span></p>
';

        if($modulelist_existing != 0){
            echo '<p><strong>Description:</strong><span class="ident">';
            echo "$modulelist_description";
            echo '</span></p>';
        }

        echo '
</div>
<div class="col-md-4">
';

        if($modulelist_existing != 0){
            echo '<p><strong>Type:</strong><span class="ident">';
            echo "$modulelist_beschrijving";
            echo '</span></p>';
        }


        echo '
    <p><strong>IP:</strong><span class="ident">
';
        echo "$modulelist_ipv4";
        echo '
                        </span></p>

</div>';

        if($modulelist_existing>0){
            echo '<div id="moduleOnline" class="col-md-3">';
        } else {
            echo '<div class="col-md-3"><p><strong>Online:</strong><span class="ident"><span class="label label-green"><span class="fa fa-dot-circle-o"></span>&nbsp;ONLINE</span></span></p>';
        }


        echo '
</div>


</div>
</div>
';
        if($modulelist_existing>0){
            // get the list of sensors


            echo '
            <div class="row text-center divider">
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
            `sensor`.`ID`
            FROM NoWire.sensor
            LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID`
            LEFT JOIN `NoWire`.`sensorsoort` ON `sensortype`.`soort` = `sensorsoort`.`ID`
            WHERE `sensor`.`IDwifimodule` = $modulelist_existing;
            ";

            $result = $conn->query($sql);

            if ($result->num_rows >0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $modulelist_description = $row["description"];
                    $modulelist_value = $row["value"];
                    $modulelist_beschrijving = $row["beschrijving"];
                    $modulelist_sieenheid = $row["sieenheid"];
                    $modulelist_label = $row["label"];
                    $modulelist_soort = $row["soort"];
                    $modulelist_sensID = $row["ID"];

                    echo '
                    <div class="col-md-2">
                    <h2><strong>
                        <div class="sensChange" id="sensor-';
                    echo $modulelist_sensID;

                    echo '">';


                    echo '
                        </div>
                        </strong>
                    </h2>
                    <p>
                        ';
                    echo "$modulelist_description";
                    echo '

                    </p>
                    <p>
                        <small>
                        ';
                    echo "$modulelist_label";
                    echo '
                        </small>
                    </p>';

                    echo '<a href="';
                    echo "index.php?p=1&s=$modulelist_sensID&w=$modulelist_existing";
                    echo '">';

                    if($modulelist_soort == "sensor"){
                        echo '
                        <button class="btn btn-orange btn-block">
                        <span class="fa fa-hdd-o"></span>&nbsp;
                        ';
                        echo 'SENSOR';
                        echo '</button>';
                    } else if ($modulelist_soort == "licht"){
                        // this is an output
                        echo '<button class="btn btn-pink btn-block">
                        <span class="fa fa-bolt"></span>&nbsp;
                        ';
                        echo 'LIGHT';
                        echo '</button>';
                    } else if ($modulelist_soort == "schakelaar"){

                        echo '
                        <button class="btn btn-blue btn-block">
                        <span class="fa fa-power-off"></span>&nbsp;
                        ';
                        echo 'SWITCH';
                        echo '</button>';
                    }


                    echo '</a></div>
                    ';
                }
            }

            $conn->close();


            echo '
            </div>
            ';
        }




        echo '
</div>

<div id="moduleOnlineGraph">
</div>
</div>

';

    }

    $conn->close();

    ?>


    <!--

    -->

</div>