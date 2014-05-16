<?php
/* #################################################################### */
/* Map Generation */
/* http://www.win.tue.nl/~vanwijk/stm.pdf */

/* main procedure */
function doMap($w, $h, $f) {
	global $width, $height, $hierarchies, $areas, $total, $layout, $clanplayers, $clanareas, $player2clan;
	global $factor, $rectHeight, $rectWidth, $rectX, $rectY;
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
	$rectWidth = $width;
	$rectHeight = $height;
	$player2clan = array();
	$rectX = 0;
	$rectY = 0;

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
		$rectHeight = $height;
		$rectWidth = $width;
		$rectX = 0;
		$rectY = 0;

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
			$save[map_x] += $layout["c$cnum"][x];
			$save[map_y] += $layout["c$cnum"][y];
			$save[map_show] = $hierarchies[$id][clan];
			saveUserData($save, "map_show map_x map_y map_width map_height");
		} else {
			$save[map_show] = 0;
			saveClanData($save, "map_show map_x map_y map_width map_height");
		}
	}
	//done
}


/* returns the area a child should occupy by its networth */
function areaByNet($networth) {
	global $width, $height, $total;
	$totA = $width*$height;
	$fact = $totA/$total;
	return $fact*$networth;
}

/* returns the length of the shortest side of the remaining
   subrectangle in which the current row is placed. */
function rectWidth() {
	global $rectWidth, $rectHeight;
	return min($rectHeight, $rectWidth);
}

/* adds a new row of children to the rectangle */
function layoutRow($row, $last=false) {
	global $layout, $rectWidth, $rectHeight, $rectX, $rectY;
	if($rectHeight == 0 || $rectWidth == 0)
		return;
	$curY = $rectY;
	$curX = $rectX;
	$tArea = array_sum($row);
	$which = true;
	if($rectHeight <= $rectWidth)
		$which = false;

	$tWidth = 0;
	$tHeight = 0;
	if($which) {
		//lay them out along left side
		$tWidth = round($tArea/$rectHeight);
		if($last)
			$tWidth = $rectWidth;
		if($tWidth == 0)
			return;
		if($tWidth > $rectWidth)
			$tWidth = $rectWidth;
	} else {
		//lay them out along top
		$tHeight = round($tArea/$rectWidth);
		if($last)
			$tHeight = $rectHeight;
		if($tHeight == 0)
			return;
		if($tHeight > $rectHeight)
			$tHeight = $rectHeight;
	}

	$xConst = 'x';
	$yConst = 'y';
	$hConst = 'height';
	$wConst = 'width';

	$last;
	foreach($row as $id => $area) {
		$last = $id;
		if($which)
			$tHeight = round($area/$tWidth);
		else
			$tWidth = round($area/$tHeight);
		$layout[$id] = array(	$xConst=>$curX,
					$yConst=>$curY,
					$wConst=>$tWidth,
					$hConst=>$tHeight);
		if($which) {
			$curY += $tHeight;
			if($curY > $rectHeight)
				break;
		} else {
			$curX += $tWidth;
			if($curX > $rectWidth)
				break;
		}
	}

	if($which) {
		$rem = $rectHeight - $curY;
		if($rem != 0) {
			$layout[$last][height] += $rem;
		}

		$rectWidth -= $tWidth;
		$rectX += $tWidth;
	} else {
		$rem = $rectWidth - $curX;
		if($rem != 0) {
			$layout[$last][width] += $rem;
		}

		$rectHeight -= $tHeight;
		$rectY += $tHeight;
	}
}

/* returns the highest aspect ratio of a list of rectangles
   given the length of the side along which they are to be
   laid out */
function worst($children, $w) {
/*	if(count($children) == 0)
		return 0;
	$s = array_sum($children);
	$h = $s/$w;
	$total = 0;
	foreach($children as $ar) {
		$tw = $ar/$h;
		$ar = $h/$tw;
		if($ar < 1)
			$ar = 1/$ar;
		$total += $ar;
	}
	//avg:
	return -$total/count($children);
*/
	if(count($children) == 0)
		return 0;
	$s = array_sum($children);
	return -($w*$w)/$s;

	if(count($children) == 0)
		return 0;
	$s = array_sum($children);
	$rplus = max($children);
	$rminus = min($children);
	return -max( $w*$w*$rplus/($s*$s), $s*$s/($w*$w*$rminus) );
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
	while($clan = mysql_fetch_array($clans)) {
		$net = db_safe_firstval("SELECT SUM($factor) FROM $playerdb WHERE clan=$clan[num] AND land>0 AND disabled<2;");
		$hierarchies["c$clan[num]"] = array(	num=>$clan[num],
							net=>$net,
							type=>1);
		$total += $net;
		$players = db_safe_query("SELECT num, clan, $factor FROM $playerdb WHERE clan=$clan[num] AND land>0 AND disabled<2;");
		while($player = mysql_fetch_array($players)) {
			$clanplayers[$clan[num]][$player[num]] = array(	num=>$player[num],
									clan=>$player[clan],
									net=>$player[$factor],
									type=>0);
			$player2clan[$player[num]] = $clan[num];
		}
	}

	$players = db_safe_query("SELECT num, clan, $factor FROM $playerdb WHERE clan=0 AND land>0 AND disabled<2;");
	while($player = mysql_fetch_array($players)) {
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

	if(worst($row, $w) > worst($merge, $w)) {
		unset($children[$idf]);
		squarify($children, $merge, $w);
	} else {
		layoutRow($row);
		squarify($children, array(), rectWidth());
	}
}

?>
