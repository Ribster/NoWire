<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 3/11/15
 * Time: 12:31
 */
//start the session
session_start();

if (!isset($_SESSION['username'])){
    $failed = $_GET['b'];
    if ($failed == 1){
        // register redirect
        echo '<div class="alert alert-danger"><strong>Error!!</strong> Your user could not be created.</div>';
    }
    echo '
        <div class="row">
        <div class="col-lg-8">
        <div class="panel panel-pink">
            <div class="panel-body pan">
                <form method="POST" action="register.php"  class="form-horizontal">
                <div class="form-body pal">
                    <div class="form-group">
                        <div><div class="input-icon right"><i class="fa fa-user"></i>
                        <input name="inputFirst" id="inputFirst" type="text" placeholder="First Name" class="form-control" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div><div class="input-icon right"><i class="fa fa-user"></i>
                        <input name="inputLast" id="inputLast" type="text" placeholder="Last Name" class="form-control" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div><div class="input-icon right"><i class="fa fa-envelope"></i>
                        <input name="inputEmail" id="inputEmail" type="text" placeholder="E-Mail" class="form-control" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div><div class="input-icon right"><i class="fa fa-lock"></i>
                        <input name="inputPassword" id="inputPassword" type="password" placeholder="Password" class="form-control" /></div>
                        </div>
                    </div>
                </div>
                <div class="form-actions pal">
                    <div class="form-group mbn">
                        <div class="col-md-offset-3">
                            <a href="index.php?p=8" class="btn btn-grey">Login</a>&nbsp;&nbsp;
                            <button type="submit" class="btn btn-success">Register</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
        </div>
        </div>';
} else {
    header( "Location: index.php?p=0" );
}