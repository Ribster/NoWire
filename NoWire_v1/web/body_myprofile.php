<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 3/11/15
 * Time: 12:33
 */
//start the session
session_start();

if (isset($_SESSION['username'])){
    $user = $_SESSION['username'];
    $mail = $_SESSION['mail'];

    echo '


        <div id="tab-general">
            <div class="row mbl">


            <div class="row mtl">
                <div class="col-md-8">
                    <table class="table table-striped table-hover">
                        <tbody>
                        <tr>
                            <td>User Name</td>';
    echo "<td>$user</td>";
    echo '

                                    </tr>
                                    <tr>
                                        <td>Email</td>';
    echo "<td>$mail</td>";
    echo '
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><span class="label label-success">Online</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            </div>
        </div>






        ';
} else {
    header( "Location: index.php?p=0" );
}