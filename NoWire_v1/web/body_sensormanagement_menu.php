<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 1/12/15
 * Time: 05:21
 */
session_start();
require_once "functions.php";

$selectedMenuItem = intval($conn->real_escape_string($_GET['men']));

if($selectedMenuItem == 0){
    $selectedMenuItem = 11;
}

printColumnHeading("Module");
echo '<ul class="nav nav-pills-red nav-justified">';
    $p_moduletype = false;


    if($selectedMenuItem == 11){
        $p_moduletype = true;
    }
    printMenuItem("fa-bullseye", $p_moduletype, "Module Type", "index.php?p=5&men=11");

echo '</ul>';


printColumnHeading("Sensor");
echo '<ul class="nav nav-pills-red nav-justified">';
    $p_sensortype = false;
    $p_couplingtype = false;
    $p_sensorkind = false;

    if($selectedMenuItem == 21){
        $p_sensortype = true;
    }
    printMenuItem("fa-bullseye", $p_sensortype, "Sensor Type", "index.php?p=5&men=21");

    if($selectedMenuItem == 22){
        $p_sensorkind = true;
    }
    printMenuItem("fa-bullseye", $p_sensorkind, "Sensor Kind", "index.php?p=5&men=22");


    if($selectedMenuItem == 23){
        $p_couplingtype = true;
    }
    printMenuItem("fa-bullseye", $p_couplingtype, "Coupling Type", "index.php?p=5&men=23");

echo '</ul>';


printColumnHeading("User");
echo '<ul class="nav nav-pills-red nav-justified">';
    $p_accesslevel = false;

    if($selectedMenuItem == 31){
        $p_accesslevel = true;
    }
    printMenuItem("fa-bullseye", $p_accesslevel, "Sensor Type", "index.php?p=5&men=31");

echo '</ul>';