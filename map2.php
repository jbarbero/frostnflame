<?
include("header.php");
include("lib/military.php");
include("lib/map2.php");

$type;
if(isset($_GET['by'])) {
	$by = $_GET['by'];
	fixinputnum($by);
	if($by == 1)
		$type = 'Networth';
	if($by == 2)
		$type = 'Land';
	else
		$type = 'Networth';
} else
	$type = 'Networth';

$gWidth = 800;
$gHeight = 600;

$tpl->assign('width', $gWidth+1);
$tpl->assign('height', $gHeight+1);
$tpl->assign('type', $type);
$type = strtolower($type);
 
doMap($gWidth, $gHeight, $type);

$uclan = loadClan($users[clan]);
$mapbuffer = "";

function divStart($other, $type) {
	global $authstr, $mapbuffer, $gWidth, $gHeight;
	$width = $other['map_width'];
	$height = $other['map_height'];
	$x = $other['map_x'];
	$y = $other['map_y'];
	$x++;
	$y++;
	$width--;
	$height--;
	if($height <= 0 || $width <= 0)
		return;

	if($other[clan]) {
		$oclan = loadClan($other[clan]);
		if($x != $oclan[map_x] + 1) {
			$x--;
			$width++;
		}
		if($y != $oclan[map_y] + 1) {
			$y--;
			$height++;
		}
	}

	if($x+$width == $gWidth-1)
		$width++;
	if($y+$height == $gHeight-1)
		$height++;

	$style = "width: $width; height: $height; top: $y; left: $x";

	$class = "mapP";

	$name = '';
	if($type == 'p') {
		$color = getWardropColor($other);
		if($color == "dead")
			$color = "normal";
		if($color == "disabled")
			$color = "normal";
		if($color == "good")
			$color = "dead";
		$name = $other[empire].on_disp(ONHTML, $other[online]).' <a class="proflinkdk" href="?profiles&num='.$other[num].$authstr.'">(#'.$other[num].')</a>';
		$mapbuffer .= '<div class="'.$class.'" style="'.$style.'">'."\n".'<img width='.$width.' height='.$height.' border=0 src=img/m'.$color.'.jpg />'."\n</div>\n";
		$mapbuffer .= '<div class="'.$class.'" style="position:absolute;'.$style.'"><table width=100% height=100% border=0><tr><td style="valign: middle; text-align: center">';
		$mapbuffer .= "\n".'<span class="dark">'.$name;
		if($other[clan]) {
			$oclan = loadClan($other[clan]);
			$mapbuffer .= "<br>\n$oclan[name] <a class=\"proflinkdk\" href=\"?clancrier&sclan=$oclan[num]$authstr\">($oclan[tag])</a>\n";
		}
		$mapbuffer .= '</span></td></tr></table>'."\n</div>\n";
	}
	else {
		$x--;
		$y--;
		$width++;
		$height++;
		$style = "width: $width; height: $height; top: $y; left: $x";
		$class = "mapC";
		$mapbuffer .= '<div class="'.$class.'" style="'.$style.'">'."\n".'<img width='.$width.' height='.$height.' border=1 src=img/spacer.gif />'."\n</div>\n";
	}
}


$players = db_safe_query("SELECT * FROM $playerdb WHERE map_show>=0;");
while($player = mysql_fetch_array($players)) {
	divStart($player, 'p');
}

$tpl->assign('mapbuffer', $mapbuffer);
$tpl->display('map.html');

TheEnd("");
?>
