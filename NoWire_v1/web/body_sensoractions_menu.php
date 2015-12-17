<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 1/12/15
 * Time: 05:00
 */
//start the session
session_start();
require_once "functions.php";

require "dbconn.php";

$selectedMenuItem = intval($conn->real_escape_string($_GET['men']));

if($selectedMenuItem == 0){
    $selectedMenuItem = 1;
}

//printColumnHeading("Actions");
echo '<ul class="nav nav-pills-red nav-justified">';
                $p_addaction = false;
                $p_removeaction = false;
                $p_listaction = false;


                if($selectedMenuItem == 1){
                    $p_addaction = true;
                }
                printMenuItem("fa-plus", $p_addaction, "Add Action", "index.php?p=3&men=1");

                if($selectedMenuItem == 2){
                    $p_removeaction = true;
                }
                printMenuItem("fa-minus", $p_removeaction, "Remove Action", "index.php?p=3&men=2");

                if($selectedMenuItem == 3){
                    $p_listaction = true;
                }
                printMenuItem("fa-table", $p_listaction, "Action List", "index.php?p=3&men=3");

echo '</ul>';
