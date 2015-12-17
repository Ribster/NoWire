<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 24/11/15
 * Time: 20:42
 */
//start the session
session_start();

require 'dbconn.php';

if(isset($_SESSION['uID'])){
    // get input
    $sens_selected = $conn->real_escape_string(($_POST["sensorSelected"]));
    $sens_mod = $conn->real_escape_string(($_POST["module"]));

    // check input
    if($sens_mod == 0 || $sens_selected == 0){
        header( "Location: index.php?p=2" );
        exit();
    }

    // delete sensor


    $sql = "DELETE FROM `NoWire`.`sensor`
      WHERE `sensor`.`ID`=$sens_selected";


    // refer the user to the new page

    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";
        //echo "<br>" . "$userFirst $userLast $userEmail";
        header( "Location: index.php?p=2&m=$sens_mod&u=0" );
        exit();
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
        //echo "<br>" . "$userFirst $userLast $userEmail";
        header( "Location: index.php?p=2&m=$sens_mod&u=0" );
        exit();
    }

    $conn->close();
}