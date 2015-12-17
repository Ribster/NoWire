<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 1/12/15
 * Time: 06:07
 */
session_start();
require_once "functions.php";

require "dbconn.php";

$selectedMenuItem = intval($conn->real_escape_string($_GET['men']));

if($selectedMenuItem == 0){
    $selectedMenuItem = 1;
}

if($selectedMenuItem == 1){
    echo '
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#couplingtype">Coupling Type</a></li>
  <li><a data-toggle="tab" href="#couplingsource">Coupling Source</a></li>
  <li><a data-toggle="tab" href="#couplingtarget">Coupling Target</a></li>
  <li><a data-toggle="tab" href="#couplingfinish">Finish up</a></li>
</ul>

<div class="tab-content margin-bottom-0">
  <div id="couplingtype" class="tab-pane fade in active">';

  require_once "body_sensoractions_data_add_couplingtype.php";

  echo '
  </div>
  <div id="couplingsource" class="tab-pane fade">';

  require_once "body_sensoractions_data_add_couplingsource.php";

  echo '
  </div>
  <div id="couplingtarget" class="tab-pane fade">';

  require_once "body_sensoractions_data_add_couplingtarget.php";

  echo '
  </div>
  <div id="couplingfinish" class="tab-pane fade">';

  require_once "body_sensoractions_data_add_couplingfinish.php";

  echo '
  </div>
</div>
';
} else if ($selectedMenuItem == 2){
    echo '
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#pubDelete">Select Public Action</a></li>';

  if($_SESSION['uID'] != 0){
    echo '<li><a data-toggle="tab" href="#privDelete">Select Private Action</a></li>';
  }

  echo '
  <li><a data-toggle="tab" href="#deletefinish">Finish up</a></li>
</ul>

<div class="tab-content margin-bottom-0">
  <div id="pubDelete" class="tab-pane fade in active">';

  require_once "body_sensoractions_data_remove_public.php";

  echo '
  </div>';

  if($_SESSION['uID'] != 0){
    echo '
    <div id="privDelete" class="tab-pane fade">';

    require_once "body_sensoractions_data_remove_private.php";

    echo '
    </div>
    ';
  }

  echo '
  <div id="deletefinish" class="tab-pane fade">';

  require_once "body_sensoractions_data_remove_finish.php";

  echo '
  </div>
</div>
';
} else if ($selectedMenuItem == 3){
  require_once "body_sensoractions_data_actionlist.php";
}

