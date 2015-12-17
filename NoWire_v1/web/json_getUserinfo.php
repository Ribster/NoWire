<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 3/11/15
 * Time: 13:44
 */
//start the session
session_start();

require "dbconn.php";

$getID = intval($conn->real_escape_string($_GET['id']));

if(isset($_SESSION['uID'])){


    if($getID == 0){
        echo json_encode( array( "first"=>"","last"=>"","email"=>"","pw"=>"" ) );
    } else {
        // do query and select values

        $sql = "
    SELECT voornaam, achternaam, email FROM NoWire.gebruikers WHERE ID=$getID;
    ";

        $result = $conn->query($sql);

        if ($result->num_rows >0) {
            // output data of each row
            if($row = $result->fetch_assoc()){
                $uinfo_first = $row["voornaam"];
                $uinfo_last = $row["achternaam"];
                $uinfo_email = $row["email"];
                echo json_encode( array( "first"=>"$uinfo_first","last"=>"$uinfo_last","email"=>"$uinfo_email","pw"=>"" ) );
            } else {
                echo json_encode( array( "first"=>"","last"=>"","email"=>"","pw"=>"" ) );
            }
        } else {
            echo json_encode( array( "first"=>"","last"=>"","email"=>"","pw"=>"" ) );
        }


    }


    $conn->close();
}

