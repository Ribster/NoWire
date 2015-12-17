<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 11/11/15
 * Time: 19:04
 */

//start the session
session_start();

require 'dbconn.php';

$module_identifier = $conn->real_escape_string($_POST["module"]);
$deletemodule_deletable = False;

if(isset($_SESSION['uID'])){
    $deletemodule_currentuserID = $_SESSION['uID'];
} else {
    $deletemodule_currentuserID = 12;
}


$sql = "
SELECT COUNT(`wifimodule_gebruikers`.`IDwifimodule`) as ispresent
FROM NoWire.wifimodule_gebruikers
WHERE (`wifimodule_gebruikers`.`IDgebruiker` = 12 OR `wifimodule_gebruikers`.`IDgebruiker` = $deletemodule_currentuserID) AND `wifimodule_gebruikers`.`IDwifimodule` = $module_identifier
            ";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    if ($row = $result->fetch_assoc()) {
        $deletemodule_present = $row["ispresent"];
        if($deletemodule_present > 0){
            $deletemodule_deletable = True;
        }
    }
}

$conn->close();
// check if the module is deletable

if($deletemodule_deletable == True){
    require 'dbconn.php';

    $sql = "
    DELETE FROM `NoWire`.`wifimodule_gebruikers`
    WHERE IDwifimodule=$module_identifier
    ";

    $sql2 = "
    DELETE FROM `NoWire`.`wifimodule`
    WHERE `wifimodule`.`ID`=$module_identifier
    ";

    $sql3 = "
    DELETE FROM `NoWire`.`sensor`
    WHERE `sensor`.`IDwifimodule`=$module_identifier
    ";

    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";
        //echo "<br>" . "$userFirst $userLast $userEmail";
        if($conn->query($sql2) === TRUE){
            if($conn->query($sql3) === TRUE){
                header( "Location: index.php?p=2" );
                exit();
            } else {
                header( "Location: index.php?p=2" );
                exit();
            }
        } else {
            header( "Location: index.php?p=2" );
            exit();
        }
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
        //echo "<br>" . "$userFirst $userLast $userEmail";
        header( "Location: index.php?p=2" );
        exit();
    }

    $conn->close();
}
