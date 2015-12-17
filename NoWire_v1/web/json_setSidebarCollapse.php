<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 1/12/15
 * Time: 09:10
 */
//start the session
session_start();

require "dbconn.php";

$sidebar = boolval($conn->real_escape_string($_GET['setVal']));

$_SESSION['sidebarcollapse'] = $sidebar;

$curVal = $_SESSION['sidebarcollapse'];

echo json_encode( array( "state"=>"$curVal") );