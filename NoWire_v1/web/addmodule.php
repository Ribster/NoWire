<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 10/11/15
 * Time: 16:40
 */

//start the session
session_start();

require 'dbconn.php';

// public addition of module ID=12
$module_identifier = $conn->real_escape_string(($_POST["module"]));
$module_option = $conn->real_escape_string(($_POST["optionPubPriv"]));
$module_description = $conn->real_escape_string(($_POST["description"]));
$module_type = $conn->real_escape_string(($_POST["wifitype"]));
$addmodule_id = 0;
$addmodule_ip = 0;

$addmodule_currentuserID = 12;

if(isset($_SESSION['uID'])){
    $addmodule_currentuserID = $_SESSION['uID'];
}



$sql = "
SELECT ID, moduleIdentifier, ipv4 FROM NoWire.wifimodule_online WHERE moduleIdentifier = '$module_identifier';
";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    if ($row = $result->fetch_assoc()) {
        $addmodule_id = $row["ID"];
        $addmodule_ip = $row["ipv4"];
        $module_identifier = $row["moduleIdentifier"];

    }
}

$conn->close();

// check if the input is allowed

if( ($module_option == 2 && $addmodule_currentuserID == 12)){
    //escape, not allowed
    header("Location: index.php?p=2&u=$addmodule_id&m=0");
    exit();
}
if ($module_type == 0){
    //escape, not allowed
    //echo "Module type is zero";
    header("Location: index.php?p=2&u=$addmodule_id&m=0");
    exit();
}

// now insert the module
require 'dbconn.php';

$sql = "INSERT INTO `NoWire`.`wifimodule`
(`description`,
`moduleIdentifier`,
`IDtype`,
`ipv4`,
`online`)
VALUES ('$module_description', '$module_identifier', '$module_type', '$addmodule_ip', 1)";

if ($conn->query($sql) === TRUE) {
    // get the acual ID and send the user to the module page
    require 'dbconn.php';

    $sql = "
    SELECT ID FROM NoWire.wifimodule WHERE moduleIdentifier='$module_identifier'
    ";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        if ($row = $result->fetch_assoc()) {
            $addmodule_id = $row["ID"];
        }
    }

    $conn->close();

    require 'dbconn.php';

    if($module_option == 1){
        $sql = "
        INSERT INTO `NoWire`.`wifimodule_gebruikers`
        (`IDwifimodule`,
        `IDgebruiker`)
        VALUES
        ($addmodule_id,
        12);
    ";
    } else if ($module_option == 2){
        $sql = "
        INSERT INTO `NoWire`.`wifimodule_gebruikers`
        (`IDwifimodule`,
        `IDgebruiker`)
        VALUES
        ($addmodule_id,
        $addmodule_currentuserID);
    ";
    }





    if ($conn->query($sql) === FALSE) {
        echo "Insert of $sql failed";
    }

    $conn->close();
    header( "Location: index.php?p=2&m=$addmodule_id&u=0" );
    exit();
} else {
    // send the user back to the addition page
    echo "Insert of $sql failed";
    header("Location: index.php?p=2&u=$addmodule_id&m=0");
    exit();
}

$conn->close();

