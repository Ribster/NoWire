<?php
/**
 * Created by PhpStorm.
 * User: Robbe Van Assche
 * Date: 29/10/15
 * Time: 19:07
 */
	//start the session
	session_start();

	//check to make sure the session variable is registered
	if(isset($_SESSION['username']))
    {
        //session variable is registered, user ready to logout
        session_unset();
        session_destroy();
        header( "Location: index.php?p=0" );
        exit();
    }
    else
    {
        //session variable isn't registered, user shouldn't be on this page
        header( "Location: index.php?p=0" );
        exit();
    }