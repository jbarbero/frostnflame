<?
include("header.php");
if($php_block == "No") $do_mission = 0;

include("magicfun.php");

?><big><b><?=$uera[wizards]?>: Missions</big></b><br><br>
<a href="?guide&amp;section=magic&amp;era=<?=$users[era]?><?=$authstr?>"><?=$gamename?> Guide: Missions</a><br>
<form name="magic_form" method="post" action="?magic<?=$authstr?>" onSubmit="
if(document.magic_form.mission_num.options[document.magic_form.mission_num.selectedIndex].value==<?=$spnumbyname['missionkill']?>) {
	temp = window.confirm('Performing Seppuku will destroy ALL of your troops and peasants as well as a portion of your <?=$uera[wizards]?>. Do you still want to perform this mission?');
	if(temp==1) {
		document.forms.magic_form.jsenabled.value = 'jsenabled';
		document.forms.magic_form.submit();
	}
	else {
		return false;
	}
		
}
else
	document.forms.magic_form.submit();
">
<table class="inputtable">
<tr><td><select name="mission_num" size="1" class="dkbg">
        <option value="0">Select a Mission</option>
<?
for ($i = 1; $i <= $missions; $i++)
	if ($sptype[$i] == 'd')
		printMRow($i);
?>
        </select></td></tr>
<tr><td class="acenter">How many times: <input type="text" name="num_times" value="1" size="5" length="3"></td></tr>
<input type="hidden" name="jsenabled" value="">
<tr><td class="acenter"><input type="submit" name="do_mission" value="Perform Mission"></td></tr>
<tr><td class="acenter">Condense Turns?<input type="checkbox" name="hide_turns"<?=$cnd?>></td></tr>

</table>
</form>
<?
if ($users[shield] > $time)
	print "<i>We currently have a patrol around our ".$uera[empire].", which will last for ".round(($users[shield]-$time)/3600,1)." more hours.</i><br>\n";
if ($users[gate] > $time)
	print "<i>We currently have troops prepared for attack which will last for ".round(($users[gate]-$time)/3600,1)." more hours.</i><br>\n";
TheEnd("");
?>
