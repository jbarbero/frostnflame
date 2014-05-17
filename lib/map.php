<?php
error_reporting(0);

/* #################################################################### */
/* Map Generation */
/* http://www.win.tue.nl/~vanwijk/stm.pdf */

/* main procedure */
function LsaveUserData(&$array, $vars) {
	global $player_lookup_table;
	$id = $array['num'];
	$v = explode(" ", $vars);
	foreach($v as $var)
		$player_lookup_table[$id][$var] = $array[$var];
}

function LsaveClanData(&$array, $vars) {
	global $clan_lookup_table;
	$id = $array['num'];
	$v = explode(" ", $vars);
	foreach($v as $var)
		$clan_lookup_table[$id][$var] = $array[$var];
}

function doMap($w, $h, $f) {
	global $width, $height, $hierarchies, $areas, $total, $layout, $rectWidth, $clanplayers, $clanareas, $player2clan, $layoutrev, $factor, $rectHeight;
	//Basically, "class variables" are kept as globals
	$factor = $f;
	$width = $w;
	$height = $h;
	$hierarchies = array();
	$areas = array();
	$clanplayers = array();
	$clanareas = array();
	$total = 0;
	$layout = array();
	$layoutrev = array();
	$rectWidth = $width;
	$rectHeight = $height;
	$player2clan = array();

	getHierarchies();
	getAreas();

	arsort($areas);
	squarify($areas, array(), rectWidth());

	//iterate through clans again, and squarify those too
	//width, height must be reset to that clan's width
	//and height

	// do clans
	foreach($clanareas as $num => $areas) {
		$cdata = $layout["c$num"];
		$width = $cdata[width];
		$height = $cdata[height];
		if($height < $width) {
			$height = $cdata[width];
			$width = $cdata[height];
			$clanplayers[$num][rev] = 1;
		}
		$rectWidth = $width;

		arsort($areas);
		squarify($areas, array(), rectWidth());
	}


	//save stuff to the DB (coords)
	foreach($layout as $id => $data) {
		$type = substr($id, 0, 1);
		$num = substr($id, 1);
		$save = array();
		$save[map_x] = $data[x];
		$save[map_y] = $data[y];
		$save[map_width] = $data[width];
		$save[map_height] = $data[height];
		$save[num] = $num;
		if($type == 'p') {
			$cnum = $player2clan[$num];
			if($clanplayers[$cnum][rev] == 1) {
				$w = $save[map_width];
				$save[map_width] = $save[map_height];
				$save[map_height] = $w;
				$x = $save[map_x];
				$save[map_x] = $save[map_y];
				$save[map_y] = $x;
			}
			$save[map_x] += $layout["c$cnum"][x];
			$save[map_y] += $layout["c$cnum"][y];
			$save[map_show] = $hierarchies[$id][clan];
			LsaveUserData($save, "map_show map_x map_y map_width map_height");
		} else {
			$save[map_show] = 0;
			LsaveClanData($save, "map_show map_x map_y map_width map_height");
		}
	}
	//done
}


/* returns the area a child should occupy by its networth */
function areaByNet($networth) {
	global $width, $height, $total;
	return $width*$height*$networth/$total;
}

/* returns the length of the shortest side of the remaining
   subrectangle in which the current row is placed. */
function rectWidth() {
	global $rectWidth, $height;
	return $rectWidth;
//	return min($height, $rectWidth);
}

/* adds a new row of children to the rectangle */
function layoutRow($row, $last=false) {
	global $height, $layout, $rectWidth, $width;
	$curY = 0;
	$curX = $width - $rectWidth;

	$tArea = array_sum($row);

	$tWidth = round($tArea/$height);
	if($last)
		$tWidth = $rectWidth;
	if($tWidth == 0)
		return;

	$xConst = 'x';
	$yConst = 'y';
	$hConst = 'height';
	$wConst = 'width';

	$last;
	foreach($row as $id => $area) {
		$last = $id;
		$tHeight = round($area/$tWidth);
		$layout[$id] = array(	$xConst=>$curX,
					$yConst=>$curY,
					$wConst=>$tWidth,
					$hConst=>$tHeight,
					rev=>$rev);
		$curY += $tHeight;
		if($curY > $height)
			break;
	}

	$rem = $height-$curY;
	if($rem != 0) {
		$layout[$last][height] += $rem;
	}

	$rectWidth -= $tWidth;
}

/* returns the highest aspect ratio of a list of rectangles
   given the length of the side along which they are to be
   laid out */
function worst($children, $w) {
	if(count($children) == 0)
		return 0;
	$s = array_sum($children);
	$rplus = max($children);
	$rminus = min($children);
	if($rminus == 0 || $s == 0 || $w == 0)
		return 2000;
	return max( $w*$w*$rplus/($s*$s), $s*$s/($w*$w*$rminus) );
}

/* creates an array of hierarchies and determines the total networth */
function getHierarchies() {
	global $hierarchies, $total, $clandb, $playerdb, $clanplayers, $player2clan, $factor;

	db_safe_query("UPDATE $playerdb SET map_show=-1;");
	db_safe_query("UPDATE $clandb SET map_show=-1;");

	$total = 0;
	$hierarchies = array();
	$clans = db_safe_query("SELECT num FROM $clandb WHERE members>0;");
	$clanplayers = array();
	while($clan = mysqli_fetch_array($clans)) {
		$net = db_safe_firstval("SELECT SUM($factor) FROM $playerdb WHERE clan=$clan[num] AND land>0 AND disabled<2;");
		$hierarchies["c$clan[num]"] = array(	num=>$clan[num],
							net=>$net,
							type=>1);
		$total += $net;
		$players = db_safe_query("SELECT num, clan, $factor FROM $playerdb WHERE clan=$clan[num] AND land>0 AND disabled<2;");
		while($player = mysqli_fetch_array($players)) {
			$clanplayers[$clan[num]][$player[num]] = array(	num=>$player[num],
									clan=>$player[clan],
									net=>$player[$factor],
									type=>0);
			$player2clan[$player[num]] = $clan[num];
		}
	}

	$players = db_safe_query("SELECT num, clan, $factor FROM $playerdb WHERE clan=0 AND land>0 AND disabled<2;");
	while($player = mysqli_fetch_array($players)) {
		$hierarchies["p$player[num]"] = array(	num=>$player[num],
							clan=>$player[clan],
							net=>$player[$factor],
							type=>0);
		$total += $player[$factor];
	}
}

/* gets the areas of all the children */
function getAreas() {
	global $areas, $hierarchies, $clanplayers, $clanareas;

	foreach($hierarchies as $num => $element) {
		$areas[$num] = round(areaByNet($element[net]));
		if($areas[$num] == 0)
			unset($areas[$num]);
	}

	$clanareas = array();
	foreach($clanplayers as $num => $players) {
		$clanareas[$num] = array();
		foreach($players as $pnum => $data) {
			$clanareas[$num]["p$pnum"] = round(areaByNet($data[net]));
		}
	}
}

/* does the squarification */
function squarify($children, $row, $w) {
	if(count($children) == 0) {
		layoutRow($row, true);
		return;
	}
	$head = 0;
	$idf = "";
	foreach($children as $id => $child) {
		$idf = $id;
		$head = $child;
		break;
	}

	$merge = array_merge($row, array($idf=>$head));

	if(worst($row, $w) <= worst($merge, $w)) {
		unset($children[$idf]);
		squarify($children, $merge, $w);
	} else {
		layoutRow($row);
		squarify($children, array(), rectWidth());
	}
}

?>
