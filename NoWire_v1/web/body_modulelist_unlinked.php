<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 9/11/15
 * Time: 16:43
 */
?>

    <div class="panel-heading margin-bottom-5" style="font-size: 14px; font-weight: bold;">Unlinked</div>
    <?php
    // list all available sensors

    require 'dbconn.php';

    $sql = "
SELECT
`wifimodule_online`.`ID` as onlineID,
`wifimodule_online`.`moduleIdentifier`,
`wifimodule`.`ID` as moduleID
FROM NoWire.wifimodule_online
LEFT JOIN `NoWire`.`wifimodule` ON `wifimodule_online`.`moduleIdentifier` = `wifimodule`.`moduleIdentifier`
WHERE `wifimodule`.`ID` IS NULL
";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        echo '<ul class="nav nav-pills-green nav-justified">';
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $modulelist_identifier = $row["moduleIdentifier"];
            $modulelist_id = $row["onlineID"];

            if($modulelist_id != 0){
                if($modulelist_unexisting == $modulelist_id){
                    echo '<li class="active">';

                } else {
                    echo '<li>';
                }
                echo "<a href=\"index.php?p=2&u=$modulelist_id&m=0\">";
                echo '<div class="modListFree" id="';
                echo "free-$modulelist_id";
                echo '"></div>';
                echo "</a></li>";
            }


        }
        echo '</ul>';
    }

    $conn->close();

    ?>
