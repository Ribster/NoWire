<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 27/11/15
 * Time: 02:57
 */
//start the session
session_start();
require_once "functions.php";
require "dbconn.php";

$selectedNotification = intval($conn->real_escape_string($_GET['b']));

if($selectedNotification != 0){
    echo '
<div class="col-md-12">
';
    if($selectedNotification == 1){
        echo '
            <div class="alert alert-success alert-dismissable">
            <button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button>
            The action was added correctly. You can add another one
            </div>';
    } else if ($selectedNotification == 2){
        echo '
            <div class="alert alert-danger alert-dismissable">
            <button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button>
            The action was not added. Something went wrong.
            </div>';
    }
    echo '
</div>';
}


echo '

<div class="col-md-12">
    <div class="panel panel-grey">';
require_once "body_sensoractions_menu.php";
    echo '<div class="barGrey"></div>';
require_once "body_sensoractions_data.php";

echo '
    </div>
</div>
';