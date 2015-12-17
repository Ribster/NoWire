<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 26/11/15
 * Time: 21:11
 */

//start the session
session_start();

$sensor = intval($_GET['sensID']);
$wifiMod = intval($_GET['wifiMod']);

// we need to get the topic and payload to the python script and execute it
    // topic is pretty easy
        $pythonTopic;

        require "dbconn.php";

            // do query and select values

            $sql = "SELECT concat_ws('/', 'sensors', `wifimodule`.`moduleIdentifier`, '') as topic FROM NoWire.wifimodule WHERE `wifimodule`.`ID`=$wifiMod;";

            $result = $conn->query($sql);

            if ($result->num_rows >0) {
                // output data of each row
                if($row = $result->fetch_assoc()){
                    $pythonTopic = $row["topic"];

                    $sql = "
                        SELECT `sensortype`.`topic` FROM NoWire.sensor
                        LEFT JOIN `NoWire`.`sensortype` ON `sensor`.`IDtype` = `sensortype`.`ID`
                        WHERE `sensor`.`ID` = $sensor;
                        ";

                    $result = $conn->query($sql);

                    if ($result->num_rows >0) {
                        // output data of each row
                        if($row = $result->fetch_assoc()){
                            $pythonTopic = $pythonTopic . $row["topic"];
                        }
                    }
                }
            }

        $conn->close();


    // payload is a little more difficult
        // we need to get the n-th occurence of this type of sensor for this module
        // first get the ID type of the sensor.
        // then get the list of sensors by ID with this type
        // loop over the sensors and get the n-th occurence
        $idType;
        $nthOccurance = 0;

        require "dbconn.php";

        // do query and select values

        $sql = "SELECT `IDtype` FROM `NoWire`.`sensor` WHERE ID=$sensor LIMIT 1;";

        $result = $conn->query($sql);

        if ($result->num_rows >0) {
            // output data of each row
            if ($row = $result->fetch_assoc()) {
                $idType = $row["IDtype"];
                // got the id type. Time to get the id list of sensors relating to this wifi module
                $sql = "SELECT `sensor`.`ID` FROM `NoWire`.`sensor` WHERE `sensor`.`IDwifimodule` = $wifiMod AND `sensor`.`IDtype` = $idType ORDER BY `sensor`.`ID`;";

                $result = $conn->query($sql);

                if ($result->num_rows >0) {
                    // output data of each row
                    $iteratorLoop = 1;

                    while ($row = $result->fetch_assoc()) {
                        // loop over the rows and if the id matches, get the occurance
                        if($sensor == $row["ID"]){
                            $nthOccurance = intval($iteratorLoop);
                        } else {
                            $iteratorLoop = $iteratorLoop + 1;
                        }
                    }
                }
            }
        }
        $conn->close();

        if($nthOccurance > 0){
            // then we need to get the current output state and complement it
            //
            $newValue;

            require "dbconn.php";

            // do query and select values

            $sql = "SELECT (1-IFNULL(`value`, 0)) as newVal FROM `NoWire`.`sensor` WHERE `sensor`.`ID` = $sensor;";

            $result = $conn->query($sql);

            if ($result->num_rows >0) {
                // output data of each row
                if ($row = $result->fetch_assoc()) {
                    $newValue = $row["newVal"];

                }
            }
            $conn->close();

            $pythonPayload = $nthOccurance . '-' . $newValue;


            // send the topic and payload and get the return value from the python script
            $command = escapeshellcmd('/Users/Robbe/GITHUB/phpStorm/NoWire2/python/sendMQTT.py ' . $pythonTopic . ' ' . $pythonPayload);

            $output = shell_exec($command);

            //echo "Topic: $pythonTopic, Payload: $pythonPayload";

                // set the new output state in the database
                require "dbconn.php";
                $updated = False;

                $sql = "UPDATE `NoWire`.`sensor`
                        SET
                        `value` = '$newValue'
                        WHERE `sensor`.`ID`=$sensor;";

                if ($conn->query($sql) === TRUE) {
                    $updated = True;
                }

                $conn->close();

                if($updated == TRUE && $output == 0){
                    echo 'true';
                } else {
                    echo 'false';
                }


        } else {
            echo 'false';
        }

