<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 3/11/15
 * Time: 12:30
 */
//start the session
session_start();

$failed = $_GET['b'];
if ($failed == 1){
    // register redirect
    echo '<div class="alert alert-success alert-dismissable"><strong>Successful!!</strong> You successfully created a new user.</div>';
} else if ($failed == 2){
    // register redirect
    echo '<div class="alert alert-danger alert-dismissable"><strong>Error!!</strong> Creating the user failed.</div>';
} else if ($failed == 3){
    // register redirect
    echo '<div class="alert alert-success alert-dismissable"><strong>Successful!!</strong> You successfully deleted the user.</div>';
} else if ($failed == 4){
    // register redirect
    echo '<div class="alert alert-danger alert-dismissable"><strong>Error!!</strong> Deleting the user failed.</div>';
} else if ($failed == 5){
    // register redirect
    echo '<div class="alert alert-success alert-dismissable"><strong>Successful!!</strong> You successfully updated the user information.</div>';
} else if ($failed == 6){
    // register redirect
    echo '<div class="alert alert-danger alert-dismissable"><strong>Error!!</strong> Updating the user failed.</div>';
}

if (isset($_SESSION['username'])){
    echo '
        <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-grey">

            <ul id="generalTab" class="nav nav-tabs border-bottom-0">
                <li class=""><a href="#tab-adduser" data-toggle="tab">Add User</a></li>
                <li class=""><a href="#tab-deleteuser" data-toggle="tab">Delete User</a></li>
                <li class=""><a href="#tab-edituser" data-toggle="tab">Edit User</a></li>
                <li class="active"><a href="#tab-userlist" data-toggle="tab">List Users</a></li>
            </ul>

                        <div id="generalTabContent" class="tab-content margin-bottom-0 padding-top-0 padding-bottom-0">

                            <div id="tab-adduser" class="tab-pane fade in min-heightforce-250">
                                <div class="col-lg-6 padding-horiz-0">


                                    <form method="POST" action="register_mmt.php">
                                    <div class="form-body ">
                                        <div class="form-group">
                                            <div class="input-icon right">
                                                <i class="fa fa-user"></i>
                                                <input name="inputFirst" id="inputFirst" type="text" placeholder="First" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-icon right">
                                                <i class="fa fa-user"></i>
                                                <input name="inputLast" id="inputLast" type="text" placeholder="Last" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-icon right">
                                                <i class="fa fa-envelope"></i>
                                                <input name="inputEmail" id="inputEmail" type="text" placeholder="Email address" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-icon right">
                                                <i class="fa fa-lock"></i>
                                                <input name="inputPassword" id="inputPassword" type="password" placeholder="Password" class="form-control"></div>
                                        </div>
                                        <button type="submit" class="btn btn-green">Submit</button>
                                    </div>

                                    </form>
                                </div>

                            </div>
                            <div id="tab-deleteuser" class="tab-pane fade in min-heightforce-100">
                            <div class="col-lg-6 padding-horiz-0">
                                <form method="POST" action="userdelete.php">
                                <div class="form-group">
                                    <select name="userdel" class="form-control">
                                        <option value=\"0\">User</option>
                                        ';

    require 'dbconn.php';

    $sql = "
    SELECT ID, concat_ws(' - ', voornaam, achternaam, email) as uName FROM NoWire.gebruikers;
    ";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        while($row = $result->fetch_assoc()){
            $userdelete_id = $row["ID"];
            $userdelete_name = $row["uName"];

            echo "
            <option value=\"$userdelete_id\">$userdelete_name</option>
        ";

        }
    }

    $conn->close();


    echo '
                                    </select>
                                </div>
                                <div class="form-group">
                                <button type="submit" class="btn btn-red">Delete User!!</button>
                                </div>
                                </form>
                            </div>
                            </div>
                            <div id="tab-edituser" class="tab-pane fade in min-heightforce-300">
                            <div class="col-lg-6 padding-horiz-0">
                            <form method="POST" action="userupdate.php">
                                    <div class="form-body ">

                                    <div class="form-group">
                                        <select name="usereditID" class="form-control" onchange="getval(this.value);">
                                            <option value="0">User</option>
                                        ';

    require 'dbconn.php';

    $sql = "
    SELECT ID, concat_ws(' - ', voornaam, achternaam, email) as uName FROM NoWire.gebruikers;
    ";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        while($row = $result->fetch_assoc()){
            $userdelete_id = $row["ID"];
            $userdelete_name = $row["uName"];

            echo "
            <option value=\"$userdelete_id\">$userdelete_name</option>
        ";

        }
    }

    $conn->close();


    echo '
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-icon right">
                                                <i class="fa fa-user"></i>
                                                <input name="inputFirst" id="inputFirst" type="text" placeholder="First" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-icon right">
                                                <i class="fa fa-user"></i>
                                                <input name="inputLast" id="inputLast" type="text" placeholder="Last" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-icon right">
                                                <i class="fa fa-envelope"></i>
                                                <input name="inputEmail" id="inputEmail" type="text" placeholder="Email address" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-icon right">
                                                <i class="fa fa-lock"></i>
                                                <input name="inputPassword" id="inputPassword" type="password" placeholder="Password" class="form-control"></div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-green">Update information</button>
                                        </div>
                                    </div>
                            </form>
                            </div>
                            </div>
                            <div id="tab-userlist" class="tab-pane fade active in">

                        <table class="table table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Password</th>
                                <th>E-Mail</th>
                                <th>Access Level</th>
                                <th>Last Login</th>
                            </tr>
                            </thead>
                            <tbody>
';
    require 'dbconn.php';

    $sql = "SELECT `gebruikers`.`ID`,
    `gebruikers`.`voornaam`,
    `gebruikers`.`achternaam`,
    `gebruikers`.`passwoord`,
    `gebruikers`.`email`,
    `gebruikers`.`lastlogin`,
    `toegangsniveau`.`beschrijving`
    FROM `NoWire`.`gebruikers`
    LEFT JOIN `NoWire`.`toegangsniveau` ON `gebruikers`.`IDtoegangsniveau` = `toegangsniveau`.`ID`";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        while($row = $result->fetch_assoc()){
            $user_id = $row["ID"];
            $user_first = $row["voornaam"];
            $user_last = $row["achternaam"];
            $user_pw = $row["passwoord"];
            $user_email = $row["email"];
            $user_descr = $row["beschrijving"];
            $user_ll = $row["lastlogin"];



            echo "
        <tr>
        <td>$user_id</td>
        <td>$user_first</td>
        <td>$user_last</td>
        <td>$user_pw</td>
        <td>$user_email</td>
        ";

            if($user_descr == "ADMIN"){
                echo "<td><span class=\"label label-sm label-danger\">$user_descr</span></td>";
            } else if($user_descr == "USER"){
                echo "<td><span class=\"label label-sm label-info\">$user_descr</span></td>";
            } else if($user_descr == "GUEST"){
                echo "<td><span class=\"label label-sm label-warning\">$user_descr</span></td>";
            } else {
                echo "<td>$user_descr</td>";
            }


            echo "
        <td>$user_ll</td>
        </tr>";



        }
    }
    $conn->close();

    echo '
                                                    </tbody>
                                                </table>
                                            </div>
                                            </div>
        </div>
        </div>';
} else {
    header( "Location: index.php?p=0" );
    exit();
}
