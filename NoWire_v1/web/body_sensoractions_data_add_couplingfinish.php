<?php
/**
 * Created by PhpStorm.
 * User: Robbe
 * Date: 2/12/15
 * Time: 06:57
 */
session_start();
require_once "functions.php";


echo '
<div class="row">';

echo '<form method="POST" action="formhandler_addaction.php" class="">';

echo '<div class="inline-block margin-horiz-10 leftf">';
    echo '<h4 align="center">Source Sensor</h4>';
    printInputField("sourcedelayvalue","Source Delay Value", "");
    printInputFieldFormatted('sourcesensoractionvalue', "sourcesensoractionvalue", "input", "Source Trigger Value", "", "form-control triggerVal margin-bottom-10");

    echo '<div id="sourcesens" class="invis">';
    echo '
        <div id="sourcesens_modident">x</div>
        <div id="sourcesens_moddescr">t</div>
        <div id="sourcesens_type">source type</div>
        <div id="sourcesens_sensdescr">y</div>
        <div id="sourcesens_description">b</div>
        <div id="sourcesens_unit">c</div>
        <div id="sourcesens_topic">d</div>
        <div id="sourcesens_label">e</div>
    ';
    echo '</div>';

echo '</div>';

echo '<div class="inline-block margin-horiz-10 leftf">';
    echo '<h4 align="center">Target Sensor</h4>';
    printInputField("targetdelayvalue","Target Delay Value", "");
    printInputFieldFormatted('targetsensoractionvalue', "targetsensoractionvalue", "input", "Target Assignment Value", "", "form-control assignVal margin-bottom-10");

    echo '<div id="targetsens" class="invis">';
    echo '
            <div id="targetsens_modident">x</div>
            <div id="targetsens_moddescr">t</div>
            <div id="targetsens_type">target type</div>
            <div id="targetsens_sensdescr">y</div>
            <div id="targetsens_description">b</div>
            <div id="targetsens_unit">c</div>
            <div id="targetsens_topic">d</div>
            <div id="targetsens_label">e</div>
        ';
    echo '</div>';

echo '</div>';

echo '<div class="inline-block margin-horiz-10 leftf">';
    echo '<h4 align="center" style="vertical-align: top">Accept and Add</h4>';
    printHiddenInputField("actionID", 0);
    printHiddenInputField("sourcesensorID", 0);
    printHiddenInputField("targetsensorID", 0);
    echo '<div id="selcoupling" class="middle"><button class="btn disabled btn-danger btn-block padding-horiz-10">NO COUPLING SELECTED</button></div>';
    echo '<div id="selsource" class="middle"><button class="btn disabled btn-danger btn-block padding-horiz-10">NO SOURCE SELECTED</button></div>';
    echo '<div id="seltarget" class="middle"><button class="btn disabled btn-danger btn-block padding-horiz-10">NO TARGET SELECTED</button></div>';
    echo '<div id="selsourcetrigger" class="middle"><button class="btn disabled btn-danger btn-block padding-horiz-10">NO SOURCE TRIGGER VALUE</button></div>';
    echo '<div id="seltargetassignment" class="middle"><button class="btn disabled btn-danger btn-block padding-horiz-10">NO TARGET ASSIGNMENT VALUE</button></div>';
    echo '<div id="seltargetdescription" class="middle"><button class="btn disabled btn-danger btn-block padding-horiz-10">NO DESCRIPTION VALUE</button></div>';
    printInputFieldFormatted('actionName', "actionName", "input", "Action Description Name", "", "form-control margin-bottom-10 actionDescription");
    echo '<button id="couplingSubm" type="button" class="btn btn-green btn-block margin-top-5" style="vertical-align: bottom;">Add Coupling</button>';
echo '</div>';

echo '</form>';
echo '
</div>';