<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 13/11/15
 * Time: 10:02
 */
//start the session
session_start();

require 'dbconn.php';

if(isset($_SESSION['uID'])){

    // get post values
        $sens_descr = $conn->real_escape_string(($_POST["sensorDescription"]));
        $sens_mod = $conn->real_escape_string(($_POST["module"]));
        $sens_senstype = $conn->real_escape_string(($_POST["sensorType"]));

    // check post values
        if($sens_descr == "" || $sens_mod == 0 || $sens_senstype == 0){
            header( "Location: index.php?p=2" );
            exit();
        }

    // insert post value in database


    $sql = "    INSERT INTO `NoWire`.`sensor`
        (`description`,
        `IDtype`,
        `IDwifimodule`)
        VALUES
        ('$sens_descr',
        $sens_senstype,
        $sens_mod);";


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