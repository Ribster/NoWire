<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 12/11/15
 * Time: 18:05
 */

//start the session
session_start();

function getSidebarCollapsed($sessionVar){
    if($sessionVar == "true"){
        return true;
    } else {
        return false;
    }
}

function printWifiTypeOptions(){
    require 'dbconn.php';

    $sql = "
            SELECT ID, beschrijving FROM NoWire.wifitype
            ";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $modulelist_typeid = $row["ID"];
            $modulelist_typedescr = $row["beschrijving"];
            echo "<option value=\"$modulelist_typeid\">$modulelist_typedescr</option>";
        }
    }

    $conn->close();
}

function printWifiTypeOptionsSelectFromModule($printOptionWifi_ModuleID){
    require 'dbconn.php';

    $sql = "
            SELECT IDtype FROM NoWire.wifimodule WHERE ID=$printOptionWifi_ModuleID
            ";

    $selectedID = 0;

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $selectedID = $row["IDtype"];
        }
    }

    $sql = "
            SELECT ID, beschrijving FROM NoWire.wifitype
            ";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $modulelist_typeid = $row["ID"];
            $modulelist_typedescr = $row["beschrijving"];
            echo "<option value=\"$modulelist_typeid\"";
            if($selectedID == $modulelist_typeid){
                echo ' selected="selected"';
            }
            echo ">$modulelist_typedescr</option>";
        }
    }

    $conn->close();
}

function printOptionPubPriv(){
    echo '
                                                <div class="inline-block">
                                                <input type="radio" name="optionPubPriv" value="1" checked="checked">
                                                <span class="label label-orange">Public Module</span>
                                                </div>


                                        ';

    // only if user is logged in
    if(isset($_SESSION['toegangsniveau'])){
        if( ($_SESSION['toegangsniveau']==1 || $_SESSION['toegangsniveau']==2) ){
            echo '                              <div class="inline-block margin-horiz-30">
                                                    <input type="radio" name="optionPubPriv" value="2">
                                                    <span class="label label-blue">Private Module</span>
                                                    </div>';
        }
    }
}

function printOptionPubPrivSelectFromModule($printOptionPubPriv_ModuleID){
    require 'dbconn.php';

    $sql = "
            SELECT IDgebruiker FROM NoWire.wifimodule_gebruikers WHERE IDwifimodule=$printOptionPubPriv_ModuleID
            ";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        if ($row = $result->fetch_assoc()) {
            $module_user = $row["IDgebruiker"];

            echo '
                                                <div class="inline-block">
                                                <input type="radio" name="optionPubPriv" value="1" ';
            if($module_user == 12){
                echo 'checked="checked"';
            }
            echo '>
                                                <span class="label label-orange">Public Module</span>
                                                </div>


                                        ';

            // only if user is logged in
            if(isset($_SESSION['toegangsniveau'])){
                if( ($_SESSION['toegangsniveau']==1 || $_SESSION['toegangsniveau']==2) ){
                    echo '                              <div class="inline-block margin-horiz-30">
                                                    <input type="radio" name="optionPubPriv" value="2" ';
                    if($module_user != 12){
                        echo 'checked="checked"';
                    }
                    echo '>                          <span class="label label-blue">Private Module</span>
                                                    </div>';
                }
            }
        }
    }

    $conn->close();


}

function printOnlineOffline(){
    echo '
    <div class="inline-block">
    <input type="radio" name="onoffline" value="1" checked="checked">
    <span class="label label-green">Online</span>
    </div>
    <div class="inline-block margin-horiz-30">
    <input type="radio" name="onoffline" value="2">
    <span class="label label-red">Offline</span>
    </div>
    ';
}

function printOnlineOfflineSelectFromModule($printOnlineOffline_ModuleID){
    $module_online = 0;

    require 'dbconn.php';

    $sql = "
            SELECT online FROM NoWire.wifimodule WHERE ID=$printOnlineOffline_ModuleID
            ";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        if ($row = $result->fetch_assoc()) {
            $module_online = $row["online"];
        }
    }

    echo '
    <div class="inline-block">
    <input type="radio" name="onoffline" value="1" ';
    if($module_online == 1){
        echo 'checked="checked"';
    }
    echo '>
    <span class="label label-green">Online</span>
    </div>
    <div class="inline-block margin-horiz-30">
    <input type="radio" name="onoffline" value="2" ';
    if($module_online == 0){
        echo 'checked="checked"';
    }
    echo '>
    <span class="label label-red">Offline</span>
    </div>
    ';

    $conn->close();
}

function printDescriptionSelectFromModule($printDescription_ModuleID){
    require 'dbconn.php';

    $sql = "
            SELECT description FROM NoWire.wifimodule WHERE ID=$printDescription_ModuleID
            ";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        if ($row = $result->fetch_assoc()) {
            $module_description = $row["description"];
            echo '<input name="description" type="text" placeholder="Module description" class="form-control margin-bottom-10" value="';
            echo "$module_description";
            echo '">';
        }
    }

    $conn->close();
}

function printIpv4SelectFromModule($printIP_ModuleID){
    require 'dbconn.php';

    $sql = "
            SELECT inet_NTOA(ipv4) as ipv4 FROM NoWire.wifimodule WHERE ID=$printIP_ModuleID
            ";

    $result = $conn->query($sql);

    if ($result->num_rows >0) {
        // output data of each row
        if ($row = $result->fetch_assoc()) {
            $module_ip = $row["ipv4"];
            echo '<input name="ip" type="text" placeholder="IPv4" class="form-control margin-bottom-10 ip_address" data-mask="099.099.099.099" value="';
            echo "$module_ip";
            echo '">';
        }
    }

    $conn->close();
}

function printHiddenInputField($name, $value){
    echo '<input id="';
    echo $name;
    echo '" name="';
    echo "$name";
    echo '" type="hidden" class="form-control" value="';
    echo "$value";
    echo '">';
}

function printInputField($name, $placeholder, $value){
    echo '<input id="';
    echo $name;
    echo '" name="';
    echo $name;
    echo '" type="text" placeholder="';
    echo "$placeholder";
    echo '" class="form-control margin-bottom-10"';
    echo "$value";
    echo '>';
}

function printInputFieldFormatted($id, $name, $type, $placeholder, $value, $class){
    echo '<input id="';
    echo $id . '" name="';
    echo $name . '" type="';
    echo $type . '" placeholder="';
    echo $placeholder . '" class="';
    echo $class . '" value="';
    echo $value . '">';
}

function getFormattedValue($soort, $value, $siUnit){
    if($soort == "licht" || $soort == "schakelaar"){
        if($value == "0"){
            return "OFF";
        } else {
            return "ON";
        }
    } else {
        return "$value $siUnit";
    }
}

function printMenuItem($faLogo, $active, $name, $link){
    $parse_active = boolval($active);
    $parse_falogo = strval($faLogo);
    $parse_name = strval($name);
    $parse_link = strval($link);

    if($parse_active == TRUE){
        echo '<li class="active">';
    } else {
        echo '<li>';
    }

    echo '<a href="';
    echo "$parse_link";
    echo '">
                            <div class="" id="">
                                <span class="fa ';
    echo "$parse_falogo";
    echo '"></span>&nbsp;&nbsp;<strong>';
    echo "$parse_name";
    echo '</strong>
                            </div>
                        </a>
                    </li>';
}

function printColumnHeading($name){
    $parse_name = strval($name);
    echo '<div class="panel-heading margin-bottom-5 leftcolumnheading">';
    echo "$parse_name";
    echo '</div>';
}