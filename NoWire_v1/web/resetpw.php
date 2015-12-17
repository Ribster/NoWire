<?php
/**
 * Created by PhpStorm.
 * User: Robbe Van Assche
 * Date: 29/10/15
 * Time: 19:57
 */
//start the session
session_start();

echo '
    <div class="row">
    <div class="col-lg-8">
    <div class="panel panel-orange">
        <div class="panel-heading">
        </div>
        <div class="panel-body pan">
            <form method="POST" action="resetpw.php"  class="form-horizontal">
            <div class="form-body pal">
                <div class="form-group">
                <label for="inputEmail" class="col-md-3 control-label">E-Mail</label>
                <div class="col-md-9"><div class="input-icon right"><i class="fa fa-envelope"></i>
                <input id="inputEmail" type="text" placeholder="" class="form-control" /></div></div>
                </div>
            </div>
            <div class="form-actions pal">
                <div class="form-group mbn"><div class="col-md-offset-3 col-md-6">
                <a href="index.php?p=8" class="btn btn-grey">Login</a>&nbsp;&nbsp;
                <button type="submit" class="btn btn-success">Reset Password</button></div>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
    </div>';