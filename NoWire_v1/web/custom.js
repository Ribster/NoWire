/**
 * Created by Robbe on 3/11/15.
 */
function getval(sel) {
    // get info via php
    // set information with php
    $.get( "json_getUserinfo.php" + "?id=" + sel, function( data ) {
        $('input[name="inputFirst"]').val(data.first);
        $('input[name="inputLast"]').val(data.last);
        $('input[name="inputEmail"]').val(data.email);
        $('input[name="inputPassword"]').val(data.pw);
    }, "json" );

};

function dbTimestampToJSTimestamp(dbTimestamp){
    // Split timestamp into [ Y, M, D, h, m, s ]
    var t = dbTimestamp.toString().split(/[- :]/);

// Apply each element to the Date function
    var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

    return d;
}

function getSensorInfo(sel, moduleExisting) {
    // get info via php
    // set information with php
    //"typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>""

    $.get( "json_getSensorType.php" + "?id=" + sel + "&mod=" + moduleExisting, function( data ) {


        $("input[name='sensorType']").val(data.typeID);

        $("#selsens_description").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">' + data.descr + '</div>');

        if(data.unit == ""){
            $("#selsens_unit").html('<div class="btn btn-block btn-red disabled padding-horiz-10">' + "No Unit" + '</div>');
        } else {
            $("#selsens_unit").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">Unit of measurement: ' + data.unit + '</div>');
        }


        $("#selsens_topic").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">Topic: ' + data.topic + '</div>');
        $("#selsens_label").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">' + data.label + '</div>');

        if(data.type == "sensor"){
            $("#selsens_type").html('<div class="btn btn-block btn-orange active padding-horiz-10"><span class="fa fa-hdd-o"></span>&nbsp;SENSOR</div>');
        } else if(data.type == "licht"){
            $("#selsens_type").html('<div class="btn btn-block btn-pink active padding-horiz-10"><span class="fa fa-bolt"></span>&nbsp;LIGHT</div>');
        } else if(data.type == "schakelaar"){
            $("#selsens_type").html('<div class="btn btn-block btn-blue active padding-horiz-10"><span class="fa fa-power-off"></span>&nbsp;SWITCH</div>');
        }


        $("#addSensorTypeInfo").switchClass("invis", "vis");


    }, "json" );

};

function getSensorInfoActionSource(sel, moduleExisting, sensID) {
    // get info via php
    // set information with php
    //"typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>""

    $.get( "json_getSensorType.php" + "?id=" + sel + "&mod=" + moduleExisting + "&sens=" + sensID, function( data ) {


        $("input[name='selsource']").val(data.typeID);

        $("#sourcesens_description").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">' + data.descr + '</div>');

        if(data.unit == ""){
            $("#sourcesens_unit").html('<div class="btn btn-block btn-red disabled padding-horiz-10">' + "No Unit" + '</div>');
        } else {
            $("#sourcesens_unit").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">Unit of measurement: ' + data.unit + '</div>');
        }


        $("#sourcesens_topic").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">Topic: ' + data.topic + '</div>');
        $("#sourcesens_label").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">' + data.label + '</div>');

        if(data.type == "sensor"){
            $("#sourcesens_type").html('<div class="btn btn-block btn-orange active padding-horiz-10"><span class="fa fa-hdd-o"></span>&nbsp;SENSOR</div>');
            $("#sourcesens_sensdescr").html('<div class="btn btn-block btn-orange disabled padding-horiz-10">' + data.sensDescription +'</div>');
            $('.triggerVal').mask('0000');
        } else if(data.type == "licht"){
            $("#sourcesens_type").html('<div class="btn btn-block btn-pink active padding-horiz-10"><span class="fa fa-bolt"></span>&nbsp;LIGHT</div>');
            $("#sourcesens_sensdescr").html('<div class="btn btn-block btn-pink disabled padding-horiz-10">' + data.sensDescription +'</div>');
            $('.triggerVal').mask('Z', {translation:  {'Z': {pattern: /[0-1]/, optional: false}}});
        } else if(data.type == "schakelaar"){
            $("#sourcesens_type").html('<div class="btn btn-block btn-blue active padding-horiz-10"><span class="fa fa-power-off"></span>&nbsp;SWITCH</div>');
            $("#sourcesens_sensdescr").html('<div class="btn btn-block btn-blue disabled padding-horiz-10">' + data.sensDescription +'</div>');
            $('.triggerVal').mask('Z', {translation:  {'Z': {pattern: /[0-1]/, optional: false}}});
        }

        $("#sourcesens_modident").html('<div class="btn btn-block btn-green active padding-horiz-10">' + data.moduleIdentifier + '</div>');
        $("#sourcesens_moddescr").html('<div class="btn btn-block btn-green disabled padding-horiz-10">' + data.moduleDescription + '</div>');

        $("#sourcesens").switchClass("invis", "vis");


    }, "json" );

};

function getSensorInfoActionTarget(sel, moduleExisting, sensID) {
    // get info via php
    // set information with php
    //"typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>""

    $.get( "json_getSensorType.php" + "?id=" + sel + "&mod=" + moduleExisting + "&sens=" + sensID, function( data ) {


        $("input[name='seltarget']").val(data.typeID);

        $("#targetsens_description").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">' + data.descr + '</div>');

        if(data.unit == ""){
            $("#targetsens_unit").html('<div class="btn btn-block btn-red disabled padding-horiz-10">' + "No Unit" + '</div>');
        } else {
            $("#targetsens_unit").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">Unit of measurement: ' + data.unit + '</div>');
        }


        $("#targetsens_topic").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">Topic: ' + data.topic + '</div>');
        $("#targetsens_label").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">' + data.label + '</div>');

        if(data.type == "sensor"){
            $("#targetsens_type").html('<div class="btn btn-block btn-orange active padding-horiz-10"><span class="fa fa-hdd-o"></span>&nbsp;SENSOR</div>');
            $("#targetsens_sensdescr").html('<div class="btn btn-block btn-orange disabled padding-horiz-10">' + data.sensDescription +'</div>');;
            $('.assignVal').mask('0000');
        } else if(data.type == "licht"){
            $("#targetsens_type").html('<div class="btn btn-block btn-pink active padding-horiz-10"><span class="fa fa-bolt"></span>&nbsp;LIGHT</div>');
            $("#targetsens_sensdescr").html('<div class="btn btn-block btn-pink disabled padding-horiz-10">' + data.sensDescription +'</div>');
            $('.assignVal').mask('Z', {translation:  {'Z': {pattern: /[0-1]/, optional: false}}});
        } else if(data.type == "schakelaar"){
            $("#targetsens_type").html('<div class="btn btn-block btn-blue active padding-horiz-10"><span class="fa fa-power-off"></span>&nbsp;SWITCH</div>');
            $("#targetsens_sensdescr").html('<div class="btn btn-block btn-blue disabled padding-horiz-10">' + data.sensDescription +'</div>');
            $('.assignVal').mask('Z', {translation:  {'Z': {pattern: /[0-1]/, optional: false}}});
        }

        $("#targetsens_modident").html('<div class="btn btn-block btn-green active padding-horiz-10">' + data.moduleIdentifier + '</div>');
        $("#targetsens_moddescr").html('<div class="btn btn-block btn-green disabled padding-horiz-10">' + data.moduleDescription + '</div>');

        $("#targetsens").switchClass("invis", "vis");


    }, "json" );

};

function getSensorDeleteInfo(sel, moduleExisting) {
    // get info via php
    // set information with php
    //"typeID"=>"$getID","descr"=>"","unit"=>"","topic"=>"","type"=>"","label"=>""

    $.get( "json_getSensorDelType.php" + "?id=" + sel + "&mod=" + moduleExisting, function( data ) {
        $("input[name='sensorSelected']").val(data.typeID);

        $("#selsensDel_description").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">' + data.descr + '</div>');

        $("#selsensDel_sensdescr").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">' + data.beschr + '</div>');


        if(data.unit == ""){
            $("#selsensDel_unit").html('<div class="btn btn-block btn-red disabled padding-horiz-10">' + "No Unit" + '</div>');
        } else {
            $("#selsensDel_unit").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">Unit of measurement: ' + data.unit + '</div>');
        }


        $("#selsensDel_topic").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">Topic: ' + data.topic + '</div>');
        $("#selsensDel_label").html('<div class="btn btn-block btn-grey disabled padding-horiz-10">' + data.label + '</div>');
        $("#selsensDel_label").addClass("margin-bottom-10");

        if(data.type == "sensor"){
            $("#selsensDel_type").html('<div class="btn btn-block btn-orange active padding-horiz-10"><span class="fa fa-hdd-o"></span>&nbsp;SENSOR</div>');
        } else if(data.type == "licht"){
            $("#selsensDel_type").html('<div class="btn btn-block btn-pink active padding-horiz-10"><span class="fa fa-bolt"></span>&nbsp;LIGHT</div>');
        } else if(data.type == "schakelaar"){
            $("#selsensDel_type").html('<div class="btn btn-block btn-blue active padding-horiz-10"><span class="fa fa-power-off"></span>&nbsp;SWITCH</div>');
        }

        $("#addSensorTypeInfoDel").switchClass("invis", "vis");

    }, "json" );

};

function updateSensorList(){
    // get the module identifier
    // loop over sensChange id's
    $(".sensChange").each(function(){
        var sensID = parseInt(this.id.split("-")[1], 10);
        // get new value string from module identif string and sensorclass
        var obj = this;

        $.get( "json_getSensorValue.php" + "?id=" + sensID, function( data ) {

            $(obj).html(data.totVal);

        }, "json" );


    });

    // set timeout
    setTimeout(updateSensorList, 300);

};

function updateOnlineList(){
    // get the module identifier
    // loop over sensChange id's
        $.get( "json_getOnlineModules.php", function( data ) {
            if($("#modFree").length != 0){
                $("#modFree").html(data.total_free);
            }

            if($("#modPublic").length != 0){
                $("#modPublic").html(data.online_public + "/" + data.total_public);
            }

            if($("#modPrivate").length != 0){
                $("#modPrivate").html(data.online_private + "/" + data.total_private);
            }

        }, "json" );

        if($("#moduleIdentif").length != 0){
            // current page of the module is selected
            // online module id exists
            var modTxt = $("#moduleIdentif").text();



            // get if module is on- or offline
            $.get( "json_getOnlineModule.php" + "?s=" + modTxt, function( data ) {



                if(data.online == "1"){
                    $("#moduleOnline").html('<p><strong>Online:</strong><span class="ident"><span class="label label-green"><span class="fa fa-dot-circle-o"></span>&nbsp;ONLINE</span></span></p>');
                } else {
                    $("#moduleOnline").html('<p><strong>Online:</strong><span class="ident"><span class="label label-red"><span class="fa fa-circle-o"></span>&nbsp;OFFLINE</span></span></p>');
                }


            }, "json" );
        }

        $(".modListPriv").each(function(){
           // loop over every private item
            var modID = parseInt(this.id.split("-")[1], 10);
            var obj = this;

            $.get( "json_getOnlineModule.php" + "?e=" + modID, function( data ) {
                var isActive = false;

                if($("#moduleIdentif").length != 0){
                    // current page of the module is selected
                    if($("#moduleIdentif").text() == data.name){
                        isActive = true;
                    }
                }

                if(data.online == "1"){
                    $(obj).html('<span class="fa fa-dot-circle-o"></span>&nbsp;<strong>' + data.name + '</strong>');
                } else {
                    $(obj).html('<span class="fa fa-circle-o"></span>&nbsp;<strong>' + data.name + '</strong>');
                }

                if(isActive == true){
                    $(obj).html($(obj).html() + '<span class="badge badge-dark pull-right">' + data.sensors + '</span>');
                } else {
                    $(obj).html($(obj).html() + '<span class="badge badge-blue pull-right">' + data.sensors + '</span>');
                }

            }, "json" );
        });

        $(".modListFree").each(function(){
            // loop over every private item

            var modID = parseInt(this.id.split("-")[1], 10);
            var obj = this;

            $.get( "json_getOnlineModule.php" + "?f=" + modID, function( data ) {

                $(obj).html('<span class="fa fa-dot-circle-o"></span>&nbsp;<strong>' + data.name + '</strong>');

            }, "json" );


        });

        $(".modListPublic").each(function(){
            var modID = parseInt(this.id.split("-")[1], 10);
            var obj = this;

            $.get( "json_getOnlineModule.php" + "?e=" + modID, function( data ) {
                var isActive = false;

                if($("#moduleIdentif").length != 0){
                    // current page of the module is selected
                    if($("#moduleIdentif").text() == data.name){
                        isActive = true;
                    }
                }

                if(data.online == "1"){
                    $(obj).html('<span class="fa fa-dot-circle-o"></span>&nbsp;<strong>' + data.name + '</strong>');
                } else {
                    $(obj).html('<span class="fa fa-circle-o"></span>&nbsp;<strong>' + data.name + '</strong>');
                }

                if(isActive == true){
                    $(obj).html($(obj).html() + '<span class="badge badge-dark pull-right">' + data.sensors + '</span>');
                } else {
                    $(obj).html($(obj).html() + '<span class="badge badge-orange pull-right">' + data.sensors + '</span>');
                }

            }, "json" );
        });

    // set timeout
    setTimeout(updateOnlineList, 10000);

};

function updateSensorDataPublic(){
    $.ajax({
        url: 'sensorData_public.php',
        type: "GET",
        dataType: "html",
        success: function (data){
            $("#pubData").html(data);
        }
    });

    // set timeout
    setTimeout(updateSensorDataPublic, 2000);
};

function updateSensorDataPrivate(){
    $.ajax({
        url: 'sensorData_private.php',
        type: "GET",
        dataType: "html",
        success: function (data){
            $("#privData").html(data);
        }
    });

    // set timeout
    setTimeout(updateSensorDataPrivate, 2000);
};

function toggleOutput(sensorID, wifiMod){
    // execute python script that toggles the sensor

    $.ajax({
        url: 'interface_toggleOutput.php?sensID='+sensorID+'&wifiMod='+wifiMod,
        type: "GET",
        dataType: "html",
        success: function(data)
        {
            if(data == "true"){
                // data executed succesfully
                //alert("All good, yup yup");
            } else {
                // nope, not a good execution
                //alert("Nope, something is wrong! Payload is: " + data.toString());
            }
        }
    });
};

function setAddCouplingFinish(item){
    var gen = true;
    var first = $("#selcoupling button").hasClass('btn-success');
    var second = $("#selsource button").hasClass('btn-success');
    var turd = $("#seltarget button").hasClass('btn-success');
    var fourth = $("#selsourcetrigger button").hasClass('btn-success');
    var fifth = $("#seltargetassignment button").hasClass('btn-success');
    var sixth = $("#seltargetdescription button").hasClass('btn-success');

    if(item == 1){
        first = true;
    } else if (item == 2){
        second = true;
    } else if (item == 3){
        turd = true;
    } else if (item == 4){
        fourth = true;
    } else if (item == 5){
        fifth = true;
    } else if (item == 6){
        sixth = true;
    } else if (item == 0){
        gen = false;
    }

    if( (turd && first && second && fourth && fifth && sixth && gen) == true){
        $('#couplingSubm').attr("type","submit");
    } else {
        $('#couplingSubm').attr("type","button");
    }
}

$( document ).ready(function() {
    $('#sensAction_addCouplingtype_table td').click(function(){

        // get couplings type

        // hide / show input boxes
        $('#sourcedelayvalue').attr("type","hidden");
        $('#targetdelayvalue').attr("type","hidden");

        $("#selcoupling button").html("COUPLING SELECTED");
        $("#selcoupling button").switchClass('btn-danger', 'btn-success');

        $('#sensAction_addCouplingtype_alert').removeClass('alert-info');
        $('#sensAction_addCouplingtype_alert').addClass('alert-success');
        $('#sensAction_addCouplingtype_alert').html('Very good. You selected a coupling type from the table.');

        $('#sensAction_addCouplingtype_table td').each(function(){
            $(this).removeClass('tableCellBack-gray');
        });

        var rowd = $(this).parent().parent().children().index($(this).parent()) + 1;

        var fcallString;

        $('#sensAction_addCouplingtype_table tr').each(function (i, row) {

            if(rowd == row.rowIndex){
                fcallString = $(this).children().last().text();
                $(this).children().each(function(){
                    $(this).addClass('tableCellBack-gray');
                });
            }
        });

        $("#actionID").attr("value",fcallString);

        setAddCouplingFinish(1);
    });

    $('#sensAction_addCouplingsource_table td').click(function(){

        $("#selsource button").html("SOURCE SELECTED");
        $("#selsource button").switchClass('btn-danger', 'btn-success');

        $('#sensAction_addCouplingsource_alert').removeClass('alert-info');
        $('#sensAction_addCouplingsource_alert').addClass('alert-success');
        $('#sensAction_addCouplingsource_alert').html('Very good. You selected a coupling source from the table.');

        $('#sensAction_addCouplingsource_table td').each(function(){
            $(this).removeClass('tableCellBack-gray');
        });

        var rowd = $(this).parent().parent().children().index($(this).parent()) + 1;

        var fcallString;

        $('#sensAction_addCouplingsource_table tr').each(function (i, row) {

            if(rowd == row.rowIndex){
                fcallString = $(this).children().last().text();
                $(this).children().each(function(){
                    $(this).addClass('tableCellBack-gray');
                });
            }
        });

        // get the payload of the div
        var splitString = fcallString.split(',');
        var sensorID = parseInt(splitString[0]);
        var moduleID = parseInt(splitString[1]);
        var typeID = parseInt(splitString[2]);


        getSensorInfoActionSource(typeID, moduleID, sensorID);


        $("#sourcesensorID").attr("value",sensorID);

        $("#sourcesens").switchClass("invis", "vis");


        setAddCouplingFinish(2);
    });

    $('#sensAction_addCouplingtarget_table td').click(function(){

        $("#seltarget button").html("TARGET SELECTED");
        $("#seltarget button").switchClass('btn-danger', 'btn-success');

        $('#sensAction_addCouplingtarget_alert').removeClass('alert-info');
        $('#sensAction_addCouplingtarget_alert').addClass('alert-success');
        $('#sensAction_addCouplingtarget_alert').html('Very good. You selected a coupling target from the table.');

        $('#sensAction_addCouplingtarget_table td').each(function(){
            $(this).removeClass('tableCellBack-gray');
        });

        var rowd = $(this).parent().parent().children().index($(this).parent()) + 1;

        var fcallString;

        $('#sensAction_addCouplingtarget_table tr').each(function (i, row) {

            if(rowd == row.rowIndex){
                fcallString = $(this).children().last().text();
                $(this).children().each(function(){
                    $(this).addClass('tableCellBack-gray');
                });
            }
        });

        // get the payload of the div
        var splitString = fcallString.split(',');
        var sensorID = parseInt(splitString[0]);
        var moduleID = parseInt(splitString[1]);
        var typeID = parseInt(splitString[2]);


        getSensorInfoActionTarget(typeID, moduleID, sensorID);

        $("#targetsensorID").attr("value",sensorID);

        $("#targetsens").switchClass("invis", "vis");

        setAddCouplingFinish(3);
    });

    $('.triggerVal').change(function(){
        var valSource = !($('#sourcesensoractionvalue').val().length == 0);

        if(valSource){
            $("#selsourcetrigger button").html("SOURCE TRIGGER VALUE");
            $("#selsourcetrigger button").switchClass('btn-danger', 'btn-success');
            setAddCouplingFinish(4);
        } else {
            $("#selsourcetrigger button").html("NO SOURCE TRIGGER VALUE");
            $("#selsourcetrigger button").switchClass('btn-success', 'btn-danger');
            setAddCouplingFinish(0);
        }
    });
    $('.assignVal').change(function(){
        var valTarget = !($('#targetsensoractionvalue').val().length == 0);
        if(valTarget){
            $("#seltargetassignment button").html("TARGET ASSIGNMENT VALUE");
            $("#seltargetassignment button").switchClass('btn-danger', 'btn-success');
            setAddCouplingFinish(5);
        } else {
            $("#seltargetassignment button").html("NO TARGET ASSIGNMENT VALUE");
            $("#seltargetassignment button").switchClass('btn-success', 'btn-danger');
            setAddCouplingFinish(0);
        }
    });
    $('.actionDescription').change(function(){
        var valTarget = !($('.actionDescription').val().length == 0);
        if(valTarget){
            $("#seltargetdescription button").html("DESCRIPTION VALUE");
            $("#seltargetdescription button").switchClass('btn-danger', 'btn-success');
            setAddCouplingFinish(6);
        } else {
            $("#seltargetdescription button").html("NO DESCRIPTION VALUE");
            $("#seltargetdescription button").switchClass('btn-success', 'btn-danger');
            setAddCouplingFinish(0);
        }
    });

});

$( document ).ready(function() {
    $(window).load(function () {
        $('.triggerVal').mask('0000');
        $('.assignVal').mask('0000');
    });
});