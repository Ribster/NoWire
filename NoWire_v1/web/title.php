<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 29/10/15
 * Time: 22:48
 */

//start the session
session_start();

echo '
<!--BEGIN TITLE & BREADCRUMB PAGE-->
';

if($_SESSION['page'] == 0){
    echo '
    <div id="title-breadcrumb-option" class="page-title-breadcrumb">
    <div class="page-header pull-left">
    <div class="page-title">
    Home</div></div><ol class="breadcrumb page-breadcrumb pull-right">
    <li><i class="fa fa-home active"></i>&nbsp;<a href="index.php?p=0">Home</a></li>';
} else if($_SESSION['page'] == 1){
    echo '
    <div id="title-breadcrumb-option" class="page-title-breadcrumb">
    <div class="page-header pull-left">
    <div class="page-title">
    Sensor Data</div></div><ol class="breadcrumb page-breadcrumb pull-right">
    <li><i class="fa fa-bar-chart-o active"></i>&nbsp;<a href="index.php?p=1">Sensor Data</a></li>';
} else if($_SESSION['page'] == 2){
    echo '
    <div id="title-breadcrumb-option" class="page-title-breadcrumb">
    <div class="page-header pull-left">
    <div class="page-title">
    Module List</div></div><ol class="breadcrumb page-breadcrumb pull-right">
    <li><i class="fa fa-hdd-o active"></i>&nbsp;<a href="index.php?p=2">Module List</a></li>';
} else if($_SESSION['page'] == 3){
    echo '
    <div id="title-breadcrumb-option" class="page-title-breadcrumb">
    <div class="page-header pull-left">
    <div class="page-title">
    Sensor Actions</div></div><ol class="breadcrumb page-breadcrumb pull-right">
    <li><i class="fa fa-chain active"></i>&nbsp;<a href="index.php?p=3">Sensor Actions</a></li>';
} else if($_SESSION['page'] == 4){
    echo '
    <div id="title-breadcrumb-option" class="page-title-breadcrumb">
    <div class="page-header pull-left">
    <div class="page-title">
    Documentation</div></div><ol class="breadcrumb page-breadcrumb pull-right">
    <li><i class="fa fa-archive active"></i>&nbsp;<a href="index.php?p=4">Documentation</a></li>';
} else if($_SESSION['page'] == 5){
    echo '
    <div class="page-title-breadcrumb option">
    <div class="page-header pull-left">
        <div class="page-title pull-left mrm">Management</div>
        <span class="label label-primary pull-left mtm">ADMIN</span></div>
    <ol class="breadcrumb page-breadcrumb pull-right">
        <li><i class="fa fa-cogs active"></i>&nbsp;<a href="index.php?p=5">Management</a></li>';
} else if($_SESSION['page'] == 6){
    echo '
    <div class="page-title-breadcrumb option">
    <div class="page-header pull-left">
        <div class="page-title pull-left mrm">User Management</div>
        <span class="label label-primary pull-left mtm">ADMIN</span></div>
    <ol class="breadcrumb page-breadcrumb pull-right">
        <li><i class="fa fa-group active"></i>&nbsp;<a href="index.php?p=6">User Management</a></li>';
} else if($_SESSION['page'] == 7){
    echo '
    <div class="page-title-breadcrumb option">
    <div class="page-header pull-left">
        <div class="page-title pull-left mrm">Direct Database</div>
        <span class="label label-primary pull-left mtm">ADMIN</span></div>
    <ol class="breadcrumb page-breadcrumb pull-right">
        <li><i class="fa fa-table active"></i>&nbsp;<a href="index.php?p=7">Direct Database</a></li>';
} else if($_SESSION['page'] == 8){
    echo '
    <div id="title-breadcrumb-option" class="page-title-breadcrumb">
    <div class="page-header pull-left">
    <div class="page-title">
    Login</div></div><ol class="breadcrumb page-breadcrumb pull-right">
    <li><i class="fa fa-home"></i>&nbsp;<a href="index.php?p=0">Home</a>&nbsp;&nbsp;<i
    class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
    <li class="hidden"><a href="#">Login</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
    <li class="active">Login</li>';
} else if($_SESSION['page'] == 9){
    echo '
    <div id="title-breadcrumb-option" class="page-title-breadcrumb">
    <div class="page-header pull-left">
    <div class="page-title">
    Register</div></div><ol class="breadcrumb page-breadcrumb pull-right">
    <li><i class="fa fa-home"></i>&nbsp;<a href="index.php?p=0">Home</a>&nbsp;&nbsp;<i
    class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
    <li class="hidden"><a href="#">Register</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
    <li class="active">Register</li>';
} else if($_SESSION['page'] == 10){
    echo '
    <div id="title-breadcrumb-option" class="page-title-breadcrumb">
    <div class="page-header pull-left">
    <div class="page-title">
    Restore Password</div></div><ol class="breadcrumb page-breadcrumb pull-right">
    <li><i class="fa fa-home"></i>&nbsp;<a href="index.php?p=0">Home</a>&nbsp;&nbsp;<i
    class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
    <li class="hidden"><a href="#">Restore Password</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
    <li class="active">Restore Password</li>';
} else if($_SESSION['page'] == 11){
    echo '
    <div id="title-breadcrumb-option" class="page-title-breadcrumb">
    <div class="page-header pull-left">
    <div class="page-title">
    My Profile</div></div><ol class="breadcrumb page-breadcrumb pull-right">
    <li><i class="fa fa-home"></i>&nbsp;<a href="index.php?p=0">Home</a>&nbsp;&nbsp;<i
    class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
    <li class="hidden"><a href="#">My Profile</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
    <li class="active">My Profile</li>';
}

echo '
</ol>


<div class="clearfix">
</div>
</div>
<!--END TITLE & BREADCRUMB PAGE-->
';