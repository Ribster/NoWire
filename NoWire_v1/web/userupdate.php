<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 3/11/15
 * Time: 14:31
 */

//start the session
session_start();

require "dbconn.php";


$getID = $conn->real_escape_string($_POST['usereditID']);
$getFirst = $conn->real_escape_string($_POST['inputFirst']);
$getLast = $conn->real_escape_string($_POST['inputLast']);
$getEmail = $conn->real_escape_string($_POST['inputEmail']);

// A higher "cost" is more secure but consumes more processing power
$cost = 10;
// Create a random salt
$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
// Prefix information about the hash so PHP knows how to verify it later.
// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
$salt = sprintf("$2a$%02d$", $cost) . $salt;
// Value:
// Hash the password with the salt
$userPassword = crypt($_POST["inputPassword"], $salt);

//echo "The userID is: $getID, First: $getFirst, Last: $getLast, Email: $getEmail, PW: $userPassword";

$sql = "
UPDATE `NoWire`.`gebruikers`
SET
`voornaam` = '$getFirst',
`achternaam` = '$getLast',
`passwoord` = '$userPassword',
`email` = '$getEmail',
`lastlogin` = `lastlogin`
WHERE ID='$getID';
";



if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully";
    //echo "<br>" . "$userFirst $userLast $userEmail";
    header( "Location: index.php?p=6&b=5" );
    exit();
    //echo $sql;
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
    //echo "<br>" . "$userFirst $userLast $userEmail";
    header( "Location: index.php?p=6&b=6" );
    exit();
    //echo $sql;
}

$conn->close();