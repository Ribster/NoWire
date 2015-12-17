<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 3/11/15
 * Time: 12:34
 */

//start the session
session_start();

echo '
    <div id="tab-general">
                        <div class="row mbl">
                            <div class="col-lg-12">
                            <div class="panel panel-grey">
                        <ul id="generalTab" class="nav nav-tabs">
                            <li class="active"><a href="#tab-users" data-toggle="tab">gebruikers</a></li>
                            <li class=""><a href="#tab-logins" data-toggle="tab">inlogpoging</a></li>
                            <li class=""><a href="#tab-sensors" data-toggle="tab">sensor</a></li>
                            <li class=""><a href="#tab-sensorcoupling" data-toggle="tab">sensor_koppeling</a></li>
                            <li class=""><a href="#tab-sensorcouplingtypes" data-toggle="tab">koppelingstype</a></li>
                            <li class=""><a href="#tab-sensortypes" data-toggle="tab">sensortype</a></li>
                            <li class=""><a href="#tab-accesslevel" data-toggle="tab">toegangsniveau</a></li>
                            <li class=""><a href="#tab-wifimodule" data-toggle="tab">wifimodule</a></li>
                            <li class=""><a href="#tab-wifimoduleusers" data-toggle="tab">wifimodule_gebruikers</a></li>
                            <li class=""><a href="#tab-wifimoduleonline" data-toggle="tab">wifimodule_online</a></li>
                            <li class=""><a href="#tab-wifitype" data-toggle="tab">wifitype</a></li>
                        </ul>
                        <div id="generalTabContent" class="tab-content responsive hidden-xs hidden-sm margin-bottom-0 padding-top-0 padding-bottom-0">
                            <div id="tab-users" class="tab-pane fade active in">
                                <div class="row">
                                    <div class="col-lg-12">
                                                <table class="table table-hover table-condensed">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>Password</th>
                                                        <th>E-Mail</th>
                                                        <th>Access Level</th>
                                                        <th>Last Login</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
';
require 'dbconn.php';

$sql = "SELECT `gebruikers`.`ID`,
    `gebruikers`.`voornaam`,
    `gebruikers`.`achternaam`,
    `gebruikers`.`passwoord`,
    `gebruikers`.`email`,
    `gebruikers`.`lastlogin`,
    `toegangsniveau`.`beschrijving`
    FROM `NoWire`.`gebruikers`
    LEFT JOIN `NoWire`.`toegangsniveau` ON `gebruikers`.`IDtoegangsniveau` = `toegangsniveau`.`ID`";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    while($row = $result->fetch_assoc()){
        $user_id = $row["ID"];
        $user_first = $row["voornaam"];
        $user_last = $row["achternaam"];
        $user_pw = $row["passwoord"];
        $user_email = $row["email"];
        $user_descr = $row["beschrijving"];
        $user_ll = $row["lastlogin"];



        echo "
        <tr>
        <td>$user_id</td>
        <td>$user_first</td>
        <td>$user_last</td>
        <td>$user_pw</td>
        <td>$user_email</td>
        ";

        if($user_descr == "ADMIN"){
            echo "<td><span class=\"label label-sm label-danger\">$user_descr</span></td>";
        } else if($user_descr == "USER"){
            echo "<td><span class=\"label label-sm label-info\">$user_descr</span></td>";
        } else if($user_descr == "GUEST"){
            echo "<td><span class=\"label label-sm label-warning\">$user_descr</span></td>";
        } else {
            echo "<td>$user_descr</td>";
        }


        echo "
        <td>$user_ll</td>
        </tr>";



    }
}
$conn->close();

echo '
                                                    </tbody>
                                                </table>
                                            </div>
                                </div>
                            </div>
                            <div id="tab-logins" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-lg-12">
                                                <table class="table table-hover table-condensed">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>User Name</th>
                                                        <th>User E-Mail</th>
                                                        <th>Login Type</th>
                                                        <th>Access Level</th>
                                                        <th>Time Stamp</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
';


require 'dbconn.php';

$sql = "SELECT `inlogpoging`.`ID` AS ident, concat_ws(' ', `gebruikers`.`voornaam`, `gebruikers`.`achternaam`) as uName, `gebruikers`.`email` as mail, `inlogpoging`.`ingelogd` as logged, `inlogpoging`.`timestamp` as tstamp, `toegangsniveau`.`beschrijving` as accessl FROM NoWire.inlogpoging LEFT JOIN `NoWire`.`gebruikers` ON `inlogpoging`.`IDgebruiker` = `gebruikers`.`ID` LEFT JOIN `NoWire`.`toegangsniveau` ON `gebruikers`.`IDtoegangsniveau` = `toegangsniveau`.`ID` ORDER BY ident desc;";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    while($row = $result->fetch_assoc()){
        $inlog_id = $row["ident"];
        $inlog_name = $row["uName"];
        $inlog_mail = $row["mail"];
        $inlog_logged = $row["logged"];
        $inlog_time = $row["tstamp"];
        $inlog_level = $row["accessl"];

        echo "
        <tr>
        <td>$inlog_id</td>
        <td>$inlog_name</td>
        <td>$inlog_mail</td>
        ";

        if($inlog_logged == "0"){
            echo "<td><span class=\"label label-sm label-danger\">BAD LOGIN!</span></td>";
        } else {
            echo "<td><span class=\"label label-sm label-success\">SUCCESSFUL LOGIN</span></td>";
        }

        if($inlog_level == "ADMIN"){
            echo "<td><span class=\"label label-sm label-danger\">$inlog_level</span></td>";
        } else if($inlog_level == "USER"){
            echo "<td><span class=\"label label-sm label-info\">$inlog_level</span></td>";
        } else if($inlog_level == "GUEST"){
            echo "<td><span class=\"label label-sm label-warning\">$inlog_level</span></td>";
        } else {
            echo "<td>$inlog_level</td>";
        }


        echo "
        <td>$inlog_time</td>
        </tr>";

    }
}

$conn->close();

echo '
                                                    </tbody>
                                                </table>
                                            </div>
                                </div>
                            </div>
                            <div id="tab-sensors" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-lg-12">
                                                <table class="table table-hover table-condensed">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Type of Sensor</th>
                                                        <th>Description of Sensor</th>
                                                        <th>Value</th>
                                                        <th>Type of Wifi Module</th>
                                                        <th>IPv4</th>
                                                        <th>IPv6</th>
                                                        <th>Online?</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
';


require 'dbconn.php';

$sql = "
    SELECT `sensor`.`ID` as id,
    `sensortype`.`beschrijving` as typee,
    `sensor`.`description` as descr,
    `sensor`.`value` as val,
    `wifitype`.`beschrijving` as wifitype,
    inet_NTOA(`wifimodule`.`ipv4`) as ipv4,
    inet_NTOA(`wifimodule`.`ipv6`) as ipv6,
    `wifimodule`.`online` as onl
    FROM `NoWire`.`sensor`
    LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID`
    LEFT JOIN `NoWire`.`wifimodule` ON `wifimodule`.`ID` = `sensor`.`IDwifimodule`
    LEFT JOIN `NoWire`.`wifitype` ON `wifimodule`.`IDtype` = `wifitype`.`ID`;
";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    while($row = $result->fetch_assoc()){
        $sensor_id = $row["id"];
        $sensor_type = $row["typee"];
        $sensor_descr = $row["descr"];
        $sensor_val = $row["val"];
        $sensor_wifitype = $row["wifitype"];
        $sensor_ipv4 = $row["ipv4"];
        $sensor_ipv6 = $row["ipv6"];
        $sensor_onl = $row["onl"];
        echo "
        <tr>
        <td>$sensor_id</td>
        <td>$sensor_type</td>
        <td>$sensor_descr</td>
        <td>$sensor_val</td>
        <td>$sensor_wifitype</td>
        <td>$sensor_ipv4</td>
        <td>$sensor_ipv6</td>
        ";

        if($sensor_onl == "0"){
            echo "<td><span class=\"label label-sm label-danger\">OFFLINE</span></td>";
        } else {
            echo "<td><span class=\"label label-sm label-success\">ONLINE</span></td>";
        }

        echo "
        </tr>";

    }
}

$conn->close();

echo '
                                                    </tbody>
                                                </table>
                                     </div>
                                </div>
                            </div>
                            <div id="tab-sensorcoupling" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-lg-12">
                                                <table class="table table-hover table-condensed">
                                                    <thead>
                                                        <td colspan="2"></td>
                                                        <td colspan="3">Source</td>
                                                        <td colspan="3">Target</td>
                                                        <td colspan="2">Coupling</td>
                                                    </thead>
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Description</th>
                                                        <th>Description</th>
                                                        <th>Type</th>
                                                        <th>Trigger</th>
                                                        <th>Description</th>
                                                        <th>Type</th>
                                                        <th>Assignment</th>
                                                        <th>Condition</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
';


require 'dbconn.php';

$sql = "
    SELECT `sensor_koppeling`.`ID`,
    `sensor_koppeling`.`beschrijving` as descr,
    sSource.description as sDescr,
    tSource.beschrijving as sType,
    `sensor_koppeling`.`source_trigger_value` as sVal,
    sTarget.description as tDescr,
    tTarget.beschrijving as tType,
    `sensor_koppeling`.`target_assign_value` as tVal,
    `koppelingstype`.`voorwaarde`,
    `koppelingstype`.`actie`
    FROM NoWire.sensor_koppeling
    LEFT JOIN `NoWire`.`sensor` sSource ON sSource.ID = `sensor_koppeling`.`IDsensorBron`
    LEFT JOIN `NoWire`.`sensor` sTarget ON sTarget.ID = `sensor_koppeling`.`IDsensorDoel`
    LEFT JOIN `NoWire`.`sensortype` tSource ON tSource.ID = sSource.IDtype
    LEFT JOIN `NoWire`.`sensortype` tTarget ON tTarget.ID = sTarget.IDtype
    LEFT JOIN `NoWire`.`koppelingstype` ON `koppelingstype`.`ID` = `sensor_koppeling`.`IDkoppelingstype`;
    ";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    while($row = $result->fetch_assoc()){
        $sensorcoupling_ID = $row["ID"];
        $sensorcoupling_descr = $row["descr"];
        $sensorcoupling_sDescr = $row["sDescr"];
        $sensorcoupling_sType = $row["sType"];
        $sensorcoupling_sVal = $row["sVal"];
        $sensorcoupling_tDescr = $row["tDescr"];
        $sensorcoupling_tType = $row["tType"];
        $sensorcoupling_tVal = $row["tVal"];
        $sensorcoupling_cond = $row["voorwaarde"];
        $sensorcoupling_action = $row["actie"];



        echo "
        <tr>
        <td>$sensorcoupling_ID</td>
        <td>$sensorcoupling_descr</td>
        <td>$sensorcoupling_sDescr</td>
        <td>$sensorcoupling_sType</td>
        <td>$sensorcoupling_sVal</td>
        <td>$sensorcoupling_tDescr</td>
        <td>$sensorcoupling_tType</td>
        <td>$sensorcoupling_tVal</td>
        <td>$sensorcoupling_cond</td>
        <td>$sensorcoupling_action</td>
        </tr>";

    }
}

$conn->close();


echo '
                                                    </tbody>
                                                </table>
                                            </div>
                                </div>
                            </div>
                            <div id="tab-sensortypes" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-lg-12">
                                                <table class="table table-hover table-condensed">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Description</th>
                                                        <th>Unit</th>
                                                        <th>Type</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
';

require 'dbconn.php';

$sql = "
SELECT `sensortype`.`ID` as id, `sensortype`.`beschrijving` as descr, `sensortype`.`sieenheid` as unit, `sensorsoort`.`soort` as soort FROM NoWire.sensortype LEFT JOIN `NoWire`.`sensorsoort` ON `sensortype`.`soort` = `sensorsoort`.`ID` ORDER BY descr;
";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    while($row = $result->fetch_assoc()){
        $sensortype_id = $row["id"];
        $sensortype_descr = $row["descr"];
        $sensortype_unit = $row["unit"];
        $sensortype_soort = $row["soort"];



        echo "
        <tr>
        <td>$sensortype_id</td>
        <td>$sensortype_descr</td>
        <td>$sensortype_unit</td>
        ";
        if($sensortype_soort == "sensor"){
            echo "<td><span class=\"label label-sm label-orange\">SENSOR</span></td>";
        }

        else if($sensortype_soort == "licht"){
            echo "<td><span class=\"label label-sm label-pink\">LIGHT</span></td>";
        }

        else if($sensortype_soort == "schakelaar"){
            echo "<td><span class=\"label label-sm label-blue\">SWITCH</span></td>";
        } else {
            echo "<td></td>";
        }
        echo "
        </tr>";

    }
}

$conn->close();

echo '
                                                    </tbody>
                                                </table>
                                            </div>
                                </div>
                            </div>
                            <div id="tab-sensorcouplingtypes" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-lg-12">
                                                <table class="table table-hover table-condensed">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Condition</th>
                                                        <th>Action</th>
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
        <td>$koppelingstype_id</td>
        <td>$koppelingstype_vw</td>
        <td>$koppelingstype_act</td>
        </tr>";

    }
}

$conn->close();
echo '
                                                    </tbody>
                                                </table>
                                            </div>
                                </div>
                            </div>
                            <div id="tab-accesslevel" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-lg-6">
                                                <table class="table table-hover table-condensed">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Description</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
';
require 'dbconn.php';

$sql = "SELECT ID, beschrijving FROM NoWire.toegangsniveau;";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    while($row = $result->fetch_assoc()){
        $toegang_id = $row["ID"];
        $toegang_descr = $row["beschrijving"];



        echo "
        <tr>
        <td>$toegang_id</td>
        ";
        if($toegang_descr == "ADMIN"){
            echo "<td><span class=\"label label-sm label-danger\">$toegang_descr</span></td>";
        } else if($toegang_descr == "USER"){
            echo "<td><span class=\"label label-sm label-info\">$toegang_descr</span></td>";
        } else if($toegang_descr == "GUEST"){
            echo "<td><span class=\"label label-sm label-warning\">$toegang_descr</span></td>";
        } else {
            echo "<td>$toegang_descr</td>";
        }
        echo "
        </tr>";

    }
}

$conn->close();
echo '
                                                    </tbody>
                                                </table>
                                            </div>
                                </div>
                            </div>
                            <div id="tab-wifimodule" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-lg-12">
                                                <table class="table table-hover table-condensed">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Description</th>
                                                        <th>Type Description</th>
                                                        <th>IPv4</th>
                                                        <th>IPv6</th>
                                                        <th>Online?</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
';
require 'dbconn.php';

$sql = "SELECT `wifimodule`.`ID` as ID, `wifimodule`.`description` as descr, `wifitype`.`beschrijving` as type, INET_NTOA(`wifimodule`.`ipv4`) as ipv4, INET_NTOA(`wifimodule`.`ipv6`) as ipv6, `wifimodule`.`online` as online FROM NoWire.wifimodule LEFT JOIN `NoWire`.`wifitype`ON `wifitype`.`ID` = `wifimodule`.`IDtype`;";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    while($row = $result->fetch_assoc()){
        $wifimod_id = $row["ID"];
        $wifimod_descr = $row["descr"];
        $wifimod_type = $row["type"];
        $wifimod_ipv4 = $row["ipv4"];
        $wifimod_ipv6 = $row["ipv6"];
        $wifimod_online = $row["online"];

        echo "
        <tr>
        <td>$wifimod_id</td>
        <td>$wifimod_descr</td>
        <td>$wifimod_type</td>
        <td>$wifimod_ipv4</td>
        <td>$wifimod_ipv6</td>
        ";
        if($wifimod_online == "0"){
            echo "<td><span class=\"label label-sm label-danger\">OFFLINE</span></td>";
        } else {
            echo "<td><span class=\"label label-sm label-success\">ONLINE</span></td>";
        }

        echo "
        </tr>";

    }
}

$conn->close();

echo '
                                                    </tbody>
                                                </table>
                                            </div>
                                </div>
                            </div>
                            <div id="tab-wifimoduleusers" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-lg-12">
                                                <table class="table table-hover table-condensed">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Wifi module Description</th>
                                                        <th>Wifi IPv4</th>
                                                        <th>Wifi IPv6</th>
                                                        <th>Wifi Type</th>
                                                        <th>User name</th>
                                                        <th>Access Level</th>
                                                        <th>Online?</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
';
require 'dbconn.php';

$sql = "
SELECT `wifimodule_gebruikers`.`ID` as ID,
`wifimodule`.`description` as descr,
INET_NTOA(`wifimodule`.`ipv4`) as ipv4,
INET_NTOA(`wifimodule`.`ipv6`) as ipv6,
`wifitype`.`beschrijving` as type,
concat_ws(' ', `gebruikers`.`voornaam`, `gebruikers`.`achternaam`) as naam,
`toegangsniveau`.`beschrijving` as al,
`wifimodule`.`online` as onl
FROM `NoWire`.`wifimodule_gebruikers`
LEFT JOIN `NoWire`.`wifimodule` ON `wifimodule`.`ID` = `wifimodule_gebruikers`.`IDwifimodule`
LEFT JOIN `NoWire`.`gebruikers` ON `gebruikers`.`ID` = `wifimodule_gebruikers`.`IDgebruiker`
LEFT JOIN `NoWire`.`wifitype` ON `wifimodule`.`IDtype` = `wifitype`.`ID`
LEFT JOIN `NoWire`.`toegangsniveau` ON `gebruikers`.`IDtoegangsniveau` = `toegangsniveau`.`ID`;
";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    while($row = $result->fetch_assoc()){
        $wifimodusers_id = $row["ID"];
        $wifimodusers_description = $row["descr"];
        $wifimodusers_ipv4 = $row["ipv4"];
        $wifimodusers_ipv6 = $row["ipv6"];
        $wifimodusers_type = $row["type"];
        $wifimodusers_naam = $row["naam"];
        $wifimodusers_access = $row["al"];
        $wifimodusers_online = $row["onl"];

        echo "
        <tr>
        <td>$wifimodusers_id</td>
        <td>$wifimodusers_description</td>
        <td>$wifimodusers_ipv4</td>
        <td>$wifimodusers_ipv6</td>
        <td>$wifimodusers_type</td>
        <td>$wifimodusers_naam</td>
        ";

        if($wifimodusers_access == "ADMIN"){
            echo "<td><span class=\"label label-sm label-danger\">$wifimodusers_access</span></td>";
        } else if($wifimodusers_access == "USER"){
            echo "<td><span class=\"label label-sm label-info\">$wifimodusers_access</span></td>";
        } else if($wifimodusers_access == "GUEST"){
            echo "<td><span class=\"label label-sm label-warning\">$wifimodusers_access</span></td>";
        } else {
            echo "<td>$wifimodusers_access</td>";
        }

        if($wifimodusers_online == "0"){
            echo "<td><span class=\"label label-sm label-danger\">OFFLINE</span></td>";
        } else {
            echo "<td><span class=\"label label-sm label-success\">ONLINE</span></td>";
        }

        echo "
        </tr>";

    }
}


$conn->close();

echo '
                                                    </tbody>
                                                </table>
                                            </div>
                                </div>
                            </div>
                            <div id="tab-wifimoduleonline" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-lg-12">
                                    <table class="table table-hover table-condensed">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Module Identifier</th>
                                            <th>Wifi IPv4</th>
                                            <th>Timestamp</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        ';
require 'dbconn.php';

$sql = "SELECT `wifimodule_online`.`ID`, `wifimodule_online`.`moduleIdentifier`, inet_NTOA(`wifimodule_online`.`ipv4`) as ipv4, `wifimodule_online`.`timestamp` FROM NoWire.wifimodule_online;";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    while($row = $result->fetch_assoc()){
        $wifionline_id = $row["ID"];
        $wifionline_identifier = $row["moduleIdentifier"];
        $wifionline_ip = $row["ipv4"];
        $wifionline_ts = $row["timestamp"];



        echo "
        <tr>
        <td>$wifionline_id</td>
        <td>$wifionline_identifier</td>
        <td>$wifionline_ip</td>
        <td>$wifionline_ts</td>
        </tr>";

    }
}

$conn->close();

echo '
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                            <div id="tab-wifitype" class="tab-pane fade in">
                                <div class="row">
                                    <div class="col-lg-6">
                                                <table class="table table-hover table-condensed">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Description</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
';
require 'dbconn.php';

$sql = "SELECT ID, beschrijving FROM NoWire.wifitype";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    while($row = $result->fetch_assoc()){
        $wifitype_id = $row["ID"];
        $wifitype_descr = $row["beschrijving"];



        echo "
        <tr>
        <td>$wifitype_id</td>
        <td>$wifitype_descr</td>
        </tr>";

    }
}

$conn->close();

echo '
                                                    </tbody>
                                                </table>
                                            </div>
                                </div>
                            </div>

                        </div>
                        <div class="panel-group responsive visible-xs visible-sm alert alert-danger" id="collapse-generalTab"><strong>Error!!</strong> This is not visible in such a small window.</div>

                    </div>

                    </div>

                        </div>
                    </div>
    ';