<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 29/10/15
 * Time: 22:34
 */
//start the session
session_start();

echo '
<!--BEGIN SIDEBAR MENU-->
<nav id="sidebar" role="navigation" data-step="2" data-intro="Template has &lt;b&gt;many navigation styles&lt;/b&gt;"
data-position="left" class="navbar-default navbar-static-side">
<div class="sidebar-collapse menu-scroll">
<ul id="side-menu" class="nav">

<div class="clearfix"></div>';

if($_SESSION['page'] == 0 || $_SESSION['page'] == 8 || $_SESSION['page'] == 9 || $_SESSION['page'] == 10){
    echo '<li class="active">';
} else {
    echo '<li>';
}


echo '
<a href="index.php?p=0"><i class="fa fa-home fa-fw">
        <div class="icon-bg bg-orange"></div>
    </i><span class="menu-title">Home</span></a></li>
';


if($_SESSION['page'] == 1){
    echo '<li class="active">';
} else {
    echo '<li>';
}

echo '
<a href="index.php?p=1"><i class="fa fa-bar-chart-o fa-fw">
        <div class="icon-bg bg-pink"></div>
    </i><span class="menu-title">Sensor Data</span></a></li>
';

if($_SESSION['page'] == 2){
    echo '<li class="active">';
} else {
    echo '<li>';
}

echo '
<a href="index.php?p=2"><i class="fa fa-hdd-o fa-fw">
        <div class="icon-bg bg-pink"></div>
    </i><span class="menu-title">Module List</span></a></li>
';

if($_SESSION['page'] == 3){
    echo '<li class="active">';
} else {
    echo '<li>';
}

echo '
<a href="index.php?p=3"><i class="fa fa-chain fa-fw">
        <div class="icon-bg bg-pink"></div>
    </i><span class="menu-title">Sensor Actions</span></a></li>
';


if($_SESSION['page'] == 4){
    echo '<li class="active">';
} else {
    echo '<li>';
}

echo '
<a href="index.php?p=4"><i class="fa fa-archive fa-fw">
        <div class="icon-bg bg-pink"></div>
    </i><span class="menu-title">Documentation</span></a></li>
';




if($_SESSION['toegangsniveau'] == 1){
    if($_SESSION['page'] == 5){
        echo '<li class="active">';
    } else {
        echo '<li>';
    }
    echo '
                        <a href="index.php?p=5"><i class="fa fa-cogs fa-fw">
                            <div class="icon-bg bg-pink"></div>
                        </i><span class="menu-title">Management</span></a></li>
                        ';
}



if($_SESSION['toegangsniveau'] == 1){
    if($_SESSION['page'] == 6){
        echo '<li class="active">';
    } else {
        echo '<li>';
    }
    echo '
                        <a href="index.php?p=6"><i class="fa fa-group fa-fw">
                        <div class="icon-bg bg-pink"></div>
                        </i><span class="menu-title">User Management</span></a></li>
                        ';
}




if($_SESSION['toegangsniveau'] == 1){
    if($_SESSION['page'] == 7){
        echo '<li class="active">';
    } else {
        echo '<li>';
    }
    echo '
                        <a href="index.php?p=7"><i class="fa fa-table fa-fw">
                            <div class="icon-bg bg-pink"></div>
                        </i><span class="menu-title">Direct Database</span></a></li>
                        ';
}

echo '
<div class="clearfix"></div>
</ul>
</div>
</nav>
<!--END SIDEBAR MENU-->
';