<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 3/11/15
 * Time: 12:28
 */
//start the session
session_start();

$failed = $conn->real_escape_string($_GET['b']);
if ($failed == 1){
    // register redirect
    echo '<div class="alert alert-success"><strong>Successful!!</strong> You successfully registered your user.</div>';
} else {
    if ($failed == 2){
        // register redirect
        echo '<div class="alert alert-danger"><strong>Error!!</strong> Login attempt failed.</div>';
    }
}
if (!isset($_SESSION['username'])){
    echo '
        <div class="row">
        <div class="col-lg-8">
        <div class="panel panel-grey">
            <div class="panel-body pan">
                <form method="POST" action="login.php" class="form-horizontal">
                <div class="form-body pal">
                    <div class="form-group">
                        <div><div class="input-icon right"><i class="fa fa-envelope"></i>
                        <input name="inputEmail" id="inputEmail" type="text" placeholder="E-Mail" class="form-control" /></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div><div class="input-icon right"><i class="fa fa-lock"></i>
                        <input name="inputPassword" id="inputPassword" type="password" placeholder="Password" class="form-control" /></div>
                        <span class="help-block mbn"><a href="index.php?p=10"><small>Forgot password?</small> </a></span>
                        </div>
                    </div>
                </div>
                <div class="form-actions pal">
                    <div class="form-group mbn">
                        <div class="col-md-offset-3">
                        <a href="index.php?p=9" class="btn btn-grey">Register</a>&nbsp;&nbsp;
                        <button type="submit" class="btn btn-success">Login</button>
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