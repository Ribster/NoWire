<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 1/12/15
 * Time: 08:59
 */
//start the session
session_start();

require_once "functions.php";

$sidebar = $_SESSION['sidebarcollapse'];

echo json_encode( array( "state"=>"$sidebar") );