<?php
require_once(dirname(dirname(dirname(__DIR__))) . '/config.php');
require_once('Common/Lib/CommonLib.php');
require_once('Common/Fun_Sessions.inc.php');
require_once('Common/Fun_FormatText.inc.php');
require_once('Common/Fun_Modules.php');
CheckTourSession(true);
checkFullACL(AclQualification, '', AclReadOnly);

$RowTour=NULL;

$Select
	= "SELECT ToCategory, ToId,ToNumDist AS TtNumDist, ToCategory&12 as IsTeam3D "
	. "FROM Tournament  "
	. "WHERE ToId=" . StrSafe_DB($_SESSION['TourId']) . " ";

$RsTour=safe_r_sql($Select);
if (safe_num_rows($RsTour)==1){
	$RowTour=safe_fetch($RsTour);
	safe_free_result($RsTour);
}

$IncludeJquery = true;
$JS_SCRIPT=array(
	'<script type="text/javascript">function SelectSession_JSON(obj) {
    $.getJSON("'.$CFG->ROOT_DIR.'Qualification/SelectSession.php?Ses="+$(obj).val(), function(data) {
        if (data.error==0) {
            $("#x_From").val(data.min);
            $("#x_To").val(data.max);
            $("#x_Coalesce_div").html(data.coalesce);
        }
    });
}</script>',
	phpVars2js(array('MsgAreYouSure' => get_text('MsgAreYouSure'), "nDist"=> $RowTour->TtNumDist)),
	'<style>#x_Coalesce_div {display:inline-block;margin-left:2em;vertical-align:middle;text-align: left;}#x_Coalesce_div div {font-size:0.8em}</style>'
	);

$PAGE_TITLE=get_text('PrintScore', 'Tournament');

include('Common/Templates/head.php');

echo '<form id="PrnParameters" action="./PDFScore.php" method="post" target="PrintOut">';
echo '<table class="Tabella">';
echo '<tr><th class="Title" colspan="2">' . get_text('PrintScore','Tournament')  . '</th></tr>';
echo '<tr><th class="SubTitle" colspan="2">' . get_text('ScorePrintMode','Tournament')  . '</th></tr>';
//Parametri
echo '<tr>';
//Tipo di Score
echo '<td width="50%"><br>';
echo '<input name="ScoreDraw" type="radio" value="Complete" checked onClick="manageDistances(true);">&nbsp;' . get_text('ScoreComplete','Tournament') . '<br>';
echo '<input name="ScoreDraw" type="radio" value="Draw" onClick="manageDistances(true);">&nbsp;' . get_text('ScoreDrawing') . '<br>';
echo '</div>';
echo '</td>';
//Header e Immagini
echo '<td class="w-50"><br>';
echo '<input name="ScoreHeader" type="checkbox" value="1" checked>&nbsp;' . get_text('ScoreTournament','Tournament') . '<br>';
echo '<input name="ScoreLogos" type="checkbox" value="1" checked>&nbsp;' . get_text('ScoreLogos','Tournament') . '<br>';
echo '<input name="ScoreFlags" type="checkbox" value="1" checked>&nbsp;' . get_text('ScoreFlags','Tournament') . '<br>';
//echo '<input name="GetArcInfo" type="checkbox" value="1" >&nbsp;' . get_text('GetArcInfo','Tournament') . '<br>';
if(module_exists("Barcodes")) {
	echo '<input name="ScoreBarcode" type="checkbox" checked value="1" >&nbsp;' . get_text('ScoreBarcode','Tournament') . '<br>';
}
if(getModuleParameter('ISK-NG', 'UsePersonalDevices', '')) {
	echo '<input name="ScoreQrPersonal" type="checkbox" checked value="1" >&nbsp;' . get_text('UsePersonalDevices-Print','Api') . '<br>';
}

foreach(AvailableApis() as $Api) {
	if(!($tmp=getModuleParameter($Api, 'Mode')) || strpos($tmp,'live') !== false) {
		continue;
	}
	echo '<input name="QRCode[]" type="checkbox" '.(strpos($tmp,'pro')!== false ? '' : 'checked="checked"').' value="'.$Api.'" >&nbsp;' . get_text($Api.'-QRCode','Api') . '<br>';
}
echo '</td>';
echo '</tr>';


$ComboSes='';
$TxtFrom='';
$TxtTo='';
$ComboDist='';
$ChkG='';
$ChkX='';
if($RowTour != NULL)
{
//Sessioni
	$sessions=GetSessions('Q');
	echo '<tr><th class="SubTitle" colspan="2">' . get_text('Session')  . '</th></tr>';
	echo '<tr>';
	echo '<td colspan="2" align="Center"><br>';
	echo '<input type="hidden" name="chk_BlockAutoSave" id="chk_BlockAutoSave" value="1">';
	echo get_text('Session') . '&nbsp;<select name="x_Session" id="x_Session" onchange="SelectSession_JSON(this)">';
	echo '<option value="-1">---</option>';
	foreach ($sessions as $s) {
		echo '<option value="' . $s->SesOrder . '"' . (isset($_REQUEST['x_Session']) && $_REQUEST['x_Session']==$s->SesOrder ? ' selected' : '') . '>' . $s->Descr . '</option>';
	}
	echo '</select>';

	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	echo  get_text('From','Tournament') . '&nbsp;<input type="text" name="x_From" id="x_From" size="5" maxlength="' . (TargetNoPadding +1) . '" value="' . (isset($_REQUEST['x_From']) ? $_REQUEST['x_From'] : '') . '">';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	echo  get_text('To','Tournament') . '&nbsp;<input type="text" name="x_To" id="x_To" size="5" maxlength="' . (TargetNoPadding +1) . '" value="' . (isset($_REQUEST['x_To']) ? $_REQUEST['x_To'] : '') . '">';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<input id="x_noEmpty" name="noEmpty" type="checkbox" value="1">' . get_text('StartlistSessionNoEmpty', 'Tournament');
	echo '<div id="x_Coalesce_div"></div>';
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="2" align="Center">';
	echo '<input id="ScoreFilled" name="ScoreFilled" type="checkbox" value="1">' . get_text('ScoreFilled');
	echo '</td>';
	echo '</tr>';
}

echo '<tr>';
echo '<td colspan="2" align="Center"><br>';
echo '<input type="submit" value="' . get_text('PrintScore','Tournament') . '"><br/>&nbsp;';
if($_SESSION['TourLocSubRule']=='SetFrBouquet') {
    echo '<br/><input type="submit" name="Marmot" value="Marmot">';
}
echo '</td>';
echo '</tr>';
echo '</table>';
echo '</form>';
print '<br/>';



include('Common/Templates/tail.php');
