<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 29/10/15
 * Time: 21:46
 */

//start the session
session_start();

$servername = "localhost:3306";
$username = "nowire";
$password = "secret";
$dbname = "NoWire";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}