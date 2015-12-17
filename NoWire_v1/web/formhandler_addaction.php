<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 3/12/15
 * Time: 17:04
 */
session_start();

require_once "functions.php";
require "dbconn.php";

$action_sourcedelay = $conn->real_escape_string($_POST['sourcedelayvalue']);
$action_sourcevalue = $conn->real_escape_string($_POST['sourcesensoractionvalue']);
$action_sourceid = $conn->real_escape_string($_POST['sourcesensorID']);
$action_targetdelay = $conn->real_escape_string($_POST['targetdelayvalue']);
$action_targetvalue = $conn->real_escape_string($_POST['targetsensoractionvalue']);
$action_targetid = $conn->real_escape_string($_POST['targetsensorID']);
$action_descr = $conn->real_escape_string($_POST['actionName']);
$action_actionID = $conn->real_escape_string($_POST['actionID']);

$sql = "INSERT INTO `NoWire`.`sensor_koppeling`
(`beschrijving`,
`IDsensorBron`,
`IDsensorDoel`,
`IDkoppelingstype`,
`source_trigger_value`,
`target_assign_value`)
VALUES
('$action_descr',
'$action_sourceid',
'$action_targetid',
'$action_actionID',
'$action_sourcevalue',
'$action_targetvalue');";

if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully";
    //echo "<br>" . "$userFirst $userLast $userEmail";
    header( "Location: index.php?p=3&men=1&b=1" );
    exit();
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
    //echo "<br>" . "$userFirst $userLast $userEmail";
    header( "Location: index.php?p=3&men=1&b=2" );
    exit();
}

$conn->close();