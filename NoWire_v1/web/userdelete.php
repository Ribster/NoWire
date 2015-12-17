<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 3/11/15
 * Time: 00:49
 */
//start the session
session_start();

require 'dbconn.php';

$userID = $conn->real_escape_string($_POST["userdel"]);



$sql = "
DELETE FROM `NoWire`.`gebruikers`
WHERE ID=$userID;
";

if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully";
    //echo "<br>" . "$userFirst $userLast $userEmail";
    header( "Location: index.php?p=6&b=3" );
    exit();
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
    //echo "<br>" . "$userFirst $userLast $userEmail";
    header( "Location: index.php?p=6&b=4" );
    exit();
}

$conn->close();

echo "User ID: $userID";