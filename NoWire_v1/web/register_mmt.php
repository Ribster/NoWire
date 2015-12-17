<?php
/**
 * Created by PhpStorm.
 * User: Robbe Van Assche
 * Date: 29/10/15
 * Time: 19:03
 */
//start the session
session_start();

require 'dbconn.php';

$userFirst = $conn->real_escape_string(($_POST["inputFirst"]));
$userLast = $conn->real_escape_string(($_POST["inputLast"]));
$userEmail = $conn->real_escape_string(($_POST["inputEmail"]));

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



$sql = "INSERT INTO gebruikers
(voornaam,
achternaam,
email,
passwoord)
VALUES ('$userFirst', '$userLast', '$userEmail', '$userPassword')";

if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully";
    //echo "<br>" . "$userFirst $userLast $userEmail";
    header( "Location: index.php?p=6&b=1" );
    exit();
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
    //echo "<br>" . "$userFirst $userLast $userEmail";
    header( "Location: index.php?p=6&b=2" );
    exit();
}

$conn->close();