/**
 * Created by Robbe on 25/11/15.
 */


$( document ).ready(function() {


    updateSensorList();
    updateOnlineList();
    updateSensorDataPublic();
    updateSensorDataPrivate();

    $(window).load(function () {
        //alert(sideBarHideState());
        sideBarHideState();
    });

});


function getSidebarHideState(newVal, cb){

}

function sideBarHideState(){

    $.getJSON( "json_getSidebarCollapse.php", function( data ) {
        if(data.state == "true"){
            $("#menu-toggle").click();
        }
    });

}

function setSidebarHideState(newstate){
    // call php to set to the session
    $.getJSON( "json_setSidebarCollapse.php" + "?setVal=" + newstate, function( data ) {
        //alert("wrote the sidebar state " + setVal + " got back: " + data.state);
    });


}
/*
$(function () {
    $('#menu-toggle').click({
        // get current state
        // invert it
        // save it again
    });
});*/

