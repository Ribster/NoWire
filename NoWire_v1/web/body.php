<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 29/10/15
 * Time: 23:08
 */

//start the session
session_start();

if($_SESSION['page'] == 8){
    // login
    require "body_login.php";

} else if($_SESSION['page'] == 6 && $_SESSION['toegangsniveau'] == 1){
    // user management
    require "body_usermmt.php";

} else if($_SESSION['page'] == 9){
    // Register user name
    require "body_register.php";

} else if($_SESSION['page'] == 10){
    // restore password
    require "body_restorepw.php";

}  else if($_SESSION['page'] == 11){
    // My Profile Page
    require "body_myprofile.php";

} else if($_SESSION['page'] == 7 && $_SESSION['toegangsniveau'] == 1){
    // direct database
    require "body_directdatabase.php";
} else if($_SESSION['page'] == 2){
    // sensor list
    require "body_modulelist.php";
} else if($_SESSION['page'] == 1){
    // sensor data
    require "body_sensordata.php";
} else if($_SESSION['page'] == 5 && $_SESSION['toegangsniveau'] == 1){
    // sensor management
    require "body_sensormanagement.php";
} else if($_SESSION['page'] == 3){
    // sensor actions
    require "body_sensoractions.php";
}