<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 25/11/15
 * Time: 21:26
 */
//start the session
session_start();

if(isset($_SESSION['uID'])){
    // use the session user id

    $uID = $_SESSION['uID'];

    // do query for unconnected, public, private modules
    require "dbconn.php";

        $sql = "
        SELECT
        ( SELECT COUNT(*) FROM (SELECT `wifimodule`.`ID` FROM `NoWire`.`wifimodule_gebruikers`
        LEFT JOIN `NoWire`.`wifimodule` ON `wifimodule_gebruikers`.`IDwifimodule` = `wifimodule`.`ID`
        WHERE `wifimodule_gebruikers`.`IDgebruiker` = 12) as all_pub_tab ) as all_pub,
        ( SELECT COUNT(*) FROM (SELECT `wifimodule`.`ID` FROM `NoWire`.`wifimodule_gebruikers`
        LEFT JOIN `NoWire`.`wifimodule` ON `wifimodule_gebruikers`.`IDwifimodule` = `wifimodule`.`ID`
        WHERE `wifimodule_gebruikers`.`IDgebruiker` = 12 AND `wifimodule`.`online` = 1) as onl_pub_tab ) as onl_pub ,
        ( SELECT COUNT(*) FROM (SELECT `wifimodule`.`ID`
        FROM `NoWire`.`wifimodule_gebruikers`
        LEFT JOIN `NoWire`.`wifimodule` ON `wifimodule_gebruikers`.`IDwifimodule` = `wifimodule`.`ID`
        WHERE `wifimodule_gebruikers`.`IDgebruiker` = $uID) as all_priv_tab ) as all_priv,
        ( SELECT COUNT(*) FROM (SELECT `wifimodule`.`ID`
        FROM `NoWire`.`wifimodule_gebruikers`
        LEFT JOIN `NoWire`.`wifimodule` ON `wifimodule_gebruikers`.`IDwifimodule` = `wifimodule`.`ID`
        WHERE `wifimodule_gebruikers`.`IDgebruiker` = $uID AND `wifimodule`.`online` = 1) onl_priv_tab ) as onl_priv,
        ( SELECT COUNT(*) FROM (SELECT `wifimodule_online`.`ID`
        FROM NoWire.wifimodule_online
        WHERE `wifimodule_online`.`moduleIdentifier` NOT IN (SELECT `wifimodule`.`moduleIdentifier` FROM `NoWire`.`wifimodule`) ) as all_free_tab) as all_free
        FROM `NoWire`.`wifimodule_gebruikers`
    ";

        $result = $conn->query($sql);

        if ($result->num_rows >0) {
            // output data of each row
            if($row = $result->fetch_assoc()){
                $uinfo_allpub = $row["all_pub"];
                $uinfo_onlpub = $row["onl_pub"];
                $uinfo_allpriv = $row["all_priv"];
                $uinfo_onlpriv = $row["onl_priv"];
                $uinfo_free = $row["all_free"];
                echo json_encode( array( "total_free"=>"$uinfo_free","online_public"=>"$uinfo_onlpub","total_public"=>"$uinfo_allpub","online_private"=>"$uinfo_onlpriv","total_private"=>"$uinfo_allpriv" ) );
            } else {
                echo json_encode( array( "total_free"=>"0","online_public"=>"0","total_public"=>"0","online_private"=>"0","total_private"=>"0" ) );
            }
        } else {
            echo json_encode( array( "total_free"=>"0","online_public"=>"0","total_public"=>"0","online_private"=>"0","total_private"=>"0" ) );
        }

    $conn->close();
} else {
    // only public and unconnected modules
    // do query for unconnected, public, private modules
    require "dbconn.php";

    $sql = "
        SELECT
        ( SELECT COUNT(*) FROM (SELECT `wifimodule`.`ID` FROM `NoWire`.`wifimodule_gebruikers`
        LEFT JOIN `NoWire`.`wifimodule` ON `wifimodule_gebruikers`.`IDwifimodule` = `wifimodule`.`ID`
        WHERE `wifimodule_gebruikers`.`IDgebruiker` = 12) as all_pub_tab ) as all_pub,
        ( SELECT COUNT(*) FROM (SELECT `wifimodule`.`ID` FROM `NoWire`.`wifimodule_gebruikers`
        LEFT JOIN `NoWire`.`wifimodule` ON `wifimodule_gebruikers`.`IDwifimodule` = `wifimodule`.`ID`
        WHERE `wifimodule_gebruikers`.`IDgebruiker` = 12 AND `wifimodule`.`online` = 1) as onl_pub_tab ) as onl_pub ,
        ( SELECT COUNT(*) FROM (SELECT `wifimodule_online`.`ID`
        FROM NoWire.wifimodule_online
        WHERE `wifimodule_online`.`moduleIdentifier` NOT IN (SELECT `wifimodule`.`moduleIdentifier` FROM `NoWire`.`wifimodule`) ) as all_free_tab) as all_free
        FROM `NoWire`.`wifimodule_gebruikers`
    ";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        if($row = $result->fetch_assoc()){
            $uinfo_allpub = $row["all_pub"];
            $uinfo_onlpub = $row["onl_pub"];
            $uinfo_free = $row["all_free"];
            echo json_encode( array( "total_free"=>"$uinfo_free","online_public"=>"$uinfo_onlpub","total_public"=>"$uinfo_allpub","online_private"=>"","total_private"=>"" ) );
        } else {
            echo json_encode( array( "total_free"=>"0","online_public"=>"0","total_public"=>"0","online_private"=>"","total_private"=>"" ) );
        }
    } else {
        echo json_encode( array( "total_free"=>"0","online_public"=>"0","total_public"=>"0","online_private"=>"","total_private"=>"" ) );
    }

    $conn->close();
}