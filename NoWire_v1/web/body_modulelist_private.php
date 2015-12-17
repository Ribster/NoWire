<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 9/11/15
 * Time: 16:43
 */
?>




    <?php
    // list all available sensors

    require 'dbconn.php';

    $sql = "
SELECT
`wifimodule`.`moduleIdentifier`,
`wifimodule`.`ID`,
`wifimodule`.`online`,
COUNT(`sensor`.`ID`) as sensorcount
FROM NoWire.wifimodule_gebruikers
LEFT JOIN `NoWire`.`wifimodule` ON `wifimodule_gebruikers`.`IDwifimodule` = `wifimodule`.`ID`
LEFT JOIN `NoWire`.`sensor` ON `wifimodule`.`ID` = `sensor`.`IDwifimodule`
WHERE `wifimodule_gebruikers`.`IDgebruiker` = $modulelist_currentuserID;
";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        echo '<ul class="nav nav-pills-blue nav-justified">';
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $modulelist_identifier = $row["moduleIdentifier"];
            $modulelist_id = $row["ID"];
            $modulelist_online = $row["online"];
            $modulelist_sensorcount = $row["sensorcount"];

            if($modulelist_id != 0){
                if($modulelist_existing == $modulelist_id){
                    echo '<li class="active">';

                } else {
                    echo '<li>';
                }

                echo "<a href=\"index.php?p=2&m=$modulelist_id&u=0\">";
                echo '<div class="modListPriv" id="';
                echo "priv-$modulelist_id";
                echo '"></div>';
                echo "</a></li>";
            }


        }
        echo '</ul>';
    }

    $conn->close();

    ?>


