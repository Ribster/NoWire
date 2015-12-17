<?php
/**
 * Created by PhpStorm.
 * User: Robbe Van Assche
 * Date: 29/10/15
 * Time: 19:04
 */
//start the session
session_start();

require 'dbconn.php';

$userEmail = $conn->real_escape_string(($_POST["inputEmail"]));
$userPassword = $conn->real_escape_string(($_POST["inputPassword"]));

$sql = "
  SELECT ID, passwoord, concat_ws(' ',voornaam, achternaam) AS name, IDtoegangsniveau, email
  FROM gebruikers
  WHERE email = '$userEmail'";

$result = $conn->query($sql);


if($row = $result->fetch_assoc()) {
    $uID = $row["ID"];
    if ($result->num_rows == 1) {
        // output data of each row

        if ( hash_equals($row["passwoord"], crypt($userPassword, $row["passwoord"])) ) {
            $_SESSION['username'] = $row["name"];
            $_SESSION['toegangsniveau'] = $row["IDtoegangsniveau"];
            $_SESSION['mail'] = $row["email"];
            $_SESSION['uID'] = $row["ID"];
            // write DB

            $sql = "
                INSERT INTO `NoWire`.`inlogpoging`
                (`IDgebruiker`,
                `ingelogd`)
                VALUES
                ($uID,
                1)";
            $result = $conn->query($sql);

            $sql = "
                UPDATE `NoWire`.`gebruikers`
                SET
                `lastlogin` = NULL
                WHERE `ID` = $uID;";
            $result = $conn->query($sql);

            header( "Location: index.php?p=0" );
            exit();
        } else {
            // write DB
            $sql = "
                INSERT INTO `NoWire`.`inlogpoging`
                (`IDgebruiker`,
                `ingelogd`)
                VALUES
                ($uID,
                0)";
            $result = $conn->query($sql);

            header( "Location: index.php?p=8&b=2" );
            exit();
            //echo "No password match";
            //echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        //echo "Bad query execution - $userEmail - ";
        //echo "Error: " . $sql . "<br>" . $conn->error;
        // write DB
        $sql = "
                INSERT INTO `NoWire`.`inlogpoging`
                (`IDgebruiker`,
                `ingelogd`)
                VALUES
                ($uID,
                0)";
        $result = $conn->query($sql);

        header( "Location: index.php?p=8&b=2" );
        exit();
    }
}


$conn->close();


