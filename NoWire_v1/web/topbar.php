<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 29/10/15
 * Time: 22:42
 */
//start the session
session_start();

$pubCount = 0;
$pubOnline = 0;
$privCount = 0;
$privOnline = 0;


if(isset($_SESSION['uID'])){
    $userID = $_SESSION['uID'];
} else {
    $userID = 12;
}
// get sensor information

    require 'dbconn.php';



    $sql = "
SELECT
(SELECT COUNT(`wifimodule_gebruikers`.`IDgebruiker`)
FROM `NoWire`.`wifimodule_gebruikers`
WHERE `wifimodule_gebruikers`.`IDgebruiker` = 12
) as pubCount,
(
SELECT COUNT(`wifimodule_gebruikers`.`IDgebruiker`) as onlinePrivate
FROM `NoWire`.`wifimodule_gebruikers`
LEFT JOIN  `NoWire`.`wifimodule` ON `wifimodule_gebruikers`.`IDwifimodule` = `wifimodule`.`ID`
WHERE `wifimodule_gebruikers`.`IDgebruiker` = 12 AND `wifimodule`.`online` = 1
) as pubOnline,
(SELECT COUNT(`wifimodule_gebruikers`.`IDgebruiker`)
FROM `NoWire`.`wifimodule_gebruikers`
WHERE `wifimodule_gebruikers`.`IDgebruiker` = $userID
) as privCount,
(
SELECT COUNT(`wifimodule_gebruikers`.`IDgebruiker`) as onlinePrivate
FROM `NoWire`.`wifimodule_gebruikers`
LEFT JOIN  `NoWire`.`wifimodule` ON `wifimodule_gebruikers`.`IDwifimodule` = `wifimodule`.`ID`
WHERE `wifimodule_gebruikers`.`IDgebruiker` = $userID AND `wifimodule`.`online` = 1
) as privOnline
;
  ";

    $result = $conn->query($sql);



    if($row = $result->fetch_assoc()) {
        $pubCount = $row["pubCount"];
        $pubOnline = $row["pubOnline"];
        $privCount = $row["privCount"];
        $privOnline = $row["privOnline"];
    }

    $conn->close();






echo '
<!--BEGIN TOPBAR-->
        <div id="header-topbar-option" class="page-header-topbar">
            <nav id="topbar" role="navigation" style="margin-bottom: 0;" data-step="3" class="navbar navbar-default navbar-static-top">
            <div class="navbar-header">
                <button type="button" data-toggle="collapse" data-target=".sidebar-collapse" class="navbar-toggle"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                <a id="logo" href="index.php" class="navbar-brand"><span class="fa fa-rocket"></span><span class="logo-text">NoWire</span><span style="display: none" class="logo-text-icon">µ</span></a></div>
            <div class="topbar-main"><a id="menu-toggle" href="#" class="hidden-xs"><i class="fa fa-bars"></i></a>


                <ul class="nav navbar navbar-top-links navbar-right mbn">
                <li class="dropdown"><a data-hover="dropdown" href="index.php?p=2" class="dropdown-toggle" title="Free Sensors"><i class="fa fa-hdd-o fa-fw"></i><span class="badge badge-green">
                    <div id="modFree"></div>
                    </span></a>
                    </li>
                    <li class="dropdown"><a data-hover="dropdown" href="index.php?p=2" class="dropdown-toggle" title="Public Sensors"><i class="fa fa-hdd-o fa-fw"></i><span class="badge badge-orange">
                    <div id="modPublic"></div>
                    </span></a>
                    </li>
';

if($_SESSION['toegangsniveau'] == 1 || $_SESSION['toegangsniveau'] == 2){
echo '
                    <li class="dropdown"><a data-hover="dropdown" href="index.php?p=2" class="dropdown-toggle" title="Private Sensors"><i class="fa fa-hdd-o fa-fw"></i><span class="badge badge-blue">
                    <div id="modPrivate"></div>
                    </span></a>
                    </li>
';
}



echo '
                    <li class="dropdown topbar-user"><a data-hover="dropdown" href="#" class="dropdown-toggle"><img src="images/avatar/profile-pic.png" alt="" class="img-responsive img-circle"/>&nbsp;
                            <span class="hidden-xs">
';
// select the correct user and functionality
if(isset($_SESSION['username'])){
echo $_SESSION['username'];
} else {
echo 'Guest';
}
echo '<ul class="dropdown-menu dropdown-user pull-right">';
echo '</span>&nbsp;<span class="caret"></span></a>';

//check to make sure the session variable is registered
if(isset($_SESSION['username']))
{
echo '<li><a href="index.php?p=11"><i class="fa fa-user"></i>My Profile</a></li>';
echo '<li class="divider"></li>';
echo '<li><a href="logout.php"><i class="fa fa-key"></i>Log Out</a></li>';
} else {
echo '<li><a href="index.php?p=8"><i class="fa fa-key"></i>Log In</a></li>';
echo '<li><a href="index.php?p=9"><i class="fa fa-magic"></i>Register</a></li>';
}
echo '
</ul>
</li>
</ul>
</div>
</nav>

</div>
<!--END TOPBAR-->
';