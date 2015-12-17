<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 12/11/15
 * Time: 21:06
 */

//start the session
session_start();

require 'dbconn.php';

$module_identifier = $conn->real_escape_string(($_POST["module"]));
$module_description = $conn->real_escape_string($_POST["description"]);
$module_type = $conn->real_escape_string($_POST["wifitype"]);
$module_ip = intval($conn->real_escape_string(ip2long($_POST["ip"])));
$module_option = $conn->real_escape_string($_POST["optionPubPriv"]);
$module_online = $conn->real_escape_string($_POST["onoffline"]);

$editmodule_id = 0;

$addmodule_currentuserID = 12;

// condition online parameter for database
if ($module_online != 1){
    $module_online = 0;
}

if(isset($_SESSION['uID'])){
    $addmodule_currentuserID = $_SESSION['uID'];
}



$sql = "
SELECT ID FROM NoWire.wifimodule WHERE moduleIdentifier = '$module_identifier';
";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    if ($row = $result->fetch_assoc()) {
        $editmodule_id = $row["ID"];
    }
}

$conn->close();

// error handling

if( ($module_option == 2 && $addmodule_currentuserID == 12)){
    //escape, not allowed
    //echo 'Current user is not allowed to have a private module';
    header("Location: index.php?p=2&m=$editmodule_id&u=0");
    exit();
}

if ($module_type == 0){
    //escape, not allowed
    //echo "Module type is zero";

    header("Location: index.php?p=2&m=$editmodule_id&u=0");
    exit();
}

if($module_option == 1){
    $addmodule_currentuserID = 12;
} else if ($module_option == 2){
    // keep the current user ID
} else {
    // something wrong, exit!
    //echo 'Module option is illegal';
    header("Location: index.php?p=2&m=$editmodule_id&u=0");
    exit();
}


// now update the module
require 'dbconn.php';

$sql = "
UPDATE `NoWire`.`wifimodule`
SET
`description` = '$module_description',
`IDtype` = $module_type,
`ipv4` = $module_ip,
`online` = $module_online
WHERE ID=$editmodule_id
";

if ($conn->query($sql) === TRUE) {
    $sql = "
    UPDATE `NoWire`.`wifimodule_gebruikers`
    SET
    `IDgebruiker` = $addmodule_currentuserID
    WHERE IDwifimodule=$editmodule_id
    ";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?p=2&m=$editmodule_id&u=0");
        exit();
    } else {
        // error
        //echo "Error: " . $sql . "<br>" . $conn->error;
        header("Location: index.php?p=2&m=$editmodule_id&u=0");
        exit();
    }
} else {
    // error
    //echo "Error: " . $sql . "<br>" . $conn->error;
    header("Location: index.php?p=2&m=$editmodule_id&u=0");
    exit();
}