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


echo '
<div class="col-md-12">
    <div class="panel panel-group">';

require_once "body_sensormanagement_menu.php";

echo '
    </div>
</div>
<div class="col-md-12">
    <div class="panel panel-grey">
        <div class="panel-body">
            <div class="row">
            Test 456
            </div>
        </div>
    </div>
</div>
';