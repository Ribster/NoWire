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
    (SELECT count(`sensor`.`ID`) FROM `NoWire`.`sensor` WHERE `wifimodule`.`ID` = `sensor`.`IDwifimodule`) AS sensorcount
    FROM NoWire.wifimodule_gebruikers
    JOIN `NoWire`.`wifimodule` ON `wifimodule_gebruikers`.`IDwifimodule` = `wifimodule`.`ID`
    WHERE `wifimodule_gebruikers`.`IDgebruiker` = 12
";

$result = $conn->query($sql);

if ($result->num_rows >0) {
    // output data of each row
    echo '<ul class="nav nav-pills-orange nav-justified">';

    while ($row = $result->fetch_assoc()) {
        $modulelist_identifier = $row["moduleIdentifier"];
        $modulelist_id = $row["ID"];
        $modulelist_online = $row["online"];
        $modulelist_sensorcount = $row["sensorcount"];

        if($modulelist_existing == $modulelist_id){
            echo '<li class="active">';

        } else {
            echo '<li>';
        }
        echo "<a href=\"index.php?p=2&m=$modulelist_id&u=0\">";
        echo '<div class="modListPublic" id="';
        echo "pub-$modulelist_id";
        echo '"></div>';
        echo "</a></li>";
    }

    echo '</ul>';
}

$conn->close();

?>
