<?php
//start the session
session_start();

require_once "functions.php";

if(!isset($_SESSION['sidebarcollapse'])){
    $_SESSION['sidebarcollapse'] = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>NoWire | Home Automation System</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/icons/favicon.ico">
    <link rel="apple-touch-icon" href="images/icons/favicon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/icons/favicon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/icons/favicon-114x114.png">
    

    <!--Loading bootstrap css-->
    <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,700">
    <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,700,300">

    <link type="text/css" rel="stylesheet" href="styles/jquery-ui-1.10.4.custom.min.css">

    <link type="text/css" rel="stylesheet" href="styles/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="styles/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="styles/animate.css">
    <link type="text/css" rel="stylesheet" href="styles/all.css">
    <link type="text/css" rel="stylesheet" href="styles/main.css">
    <link type="text/css" rel="stylesheet" href="styles/style-responsive.css">
    <link type="text/css" rel="stylesheet" href="styles/zabuto_calendar.min.css">
    <link type="text/css" rel="stylesheet" href="styles/pace.css">
    <link type="text/css" rel="stylesheet" href="custom.css">



    <script src="script/jquery.min.js"></script>
    <script src="customhead.js"></script>


    <?php
        if(isset($_GET['p'])){
            $_SESSION['page'] = addslashes($_GET['p']);
        }

        if(!isset($_SESSION['page']) || empty($_GET['p'])){
            $_SESSION['page'] = 0;
        }


    // 0    Home
    //      8    Login
    //      9    Register
    //      10   Restore Password
    //      11   My Profile
    // 1    Sensor Data
    // 2    Sensor List
    // 3    Sensor Actions
    // 4    Documentation
    // 5    [ADMIN] Sensor Management
    // 6    [ADMIN] User Management
    // 7    [ADMIN] Direct Database

    ?>



</head>
<?php
//start the session
session_start();

        if(getSidebarCollapsed($_SESSION['sidebarcollapse']) == true){
            echo '<body class="left-side-collapsed">';
        } else {
            echo '<body class="">';
        }
?>

    <div>

        <?php

        require 'topbar.php';


        ?>


        <div id="wrapper">
            <?php
            require 'sidebar.php';
            ?>

            <!--BEGIN PAGE WRAPPER-->
            <div id="page-wrapper">

                <?php
                    require 'title.php';
                ?>

                <!--BEGIN CONTENT-->
                <div class="page-content">
                    <div id="tab-general">

                        <?php
                            require 'body.php';
                        ?>

                    </div>
                </div>
                <!--END CONTENT-->
                <!--BEGIN FOOTER-->
                <div id="footer">
                    <div class="copyright">
                        <a href="http://www.robbevanassche.be">2015 © Robbe Van Assche</a></div>
                </div>
                <!--END FOOTER-->
            </div>
            <!--END PAGE WRAPPER-->
        </div>
    </div>



    <script src="script/jquery-1.10.2.min.js"></script>
    <script src="script/jquery-migrate-1.2.1.min.js"></script>
    <script src="script/jquery-ui.js"></script>
    <script src="script/bootstrap.min.js"></script>
    <script src="script/bootstrap-hover-dropdown.js"></script>
    <script src="script/html5shiv.js"></script>
    <script src="script/respond.min.js"></script>
    <script src="script/jquery.metisMenu.js"></script>
    <script src="script/jquery.slimscroll.js"></script>
    <script src="script/jquery.cookie.js"></script>
    <script src="script/icheck.min.js"></script>
    <script src="script/custom.min.js"></script>
    <script src="script/jquery.menu.js"></script>


    <script src="script/holder.js"></script>
    <script src="script/responsive-tabs.js"></script>
    <script src="script/zabuto_calendar.min.js"></script>




    <!--PACE-->
    <script src="script/pace.min.js"></script>

    <!--CORE JAVASCRIPT-->
    <script src="script/main.js"></script>
    <script src="jQueryMask/jquery.mask.js"></script>

    <!-- Custom JS -->
    <script src="custom.js"></script>
    <script src="custom_graphs.js"></script>

    <!--HIGHCHARTS-->
    <script src="highStock/highstock.js"></script>
    <script src="highStock/modules/exporting.js"></script>
    <script src="highStock/themes/dark-unica.js"></script>


</body>
</html>
