<?php

function printNews (&$user) {
	global $newsdb, $playerdb, $clandb, $users, $uera, $time, $authstr, $config, $atknames;

	if($user[newssort] == 0)
		$order = 'DESC';
	elseif($user[newssort] == 1)
		$order = 'ASC';
	else
		$order = 'DESC';

	$news = db_safe_query("SELECT * FROM $newsdb WHERE id1=$user[num] AND time>$user[newstime] ORDER BY time $order;");
	if (!@mysqli_num_rows($news))
		return 0;

	echo '<table class="inputtable" border=1>';
	echo '<tr><th>Time</th>';
	echo '<th colspan="2">Event</th>';
	echo '<th style="width:2">Times</th></tr>';

	while ($new = mysqli_fetch_array($news)) {
		$time = $new[time];
		$id1 = $new[id1];
		$id2 = $new[id2];
		$id3 = $new[id3];

		if($id1 != "0") {
			$pl1 = mysqli_fetch_array(db_safe_query("SELECT empire,race,era FROM $playerdb WHERE num=$id1;"));
			$name1 = $pl1['empire'].' <a class=proflink href=?profiles&num='.$id1.$authstr.'>(#'.$id1.')</a>';
			$era1 = loadEra($pl1['era'], $pl1['race']);
		} else {
			$name1 = "An unknown $uera[empire]"; // anon bounty checking
			$id1 = "???";
		}
		
		if($id2 != "0") {
			$pl2 = mysqli_fetch_array(db_safe_query("SELECT empire,race,era FROM $playerdb WHERE num=$id2;"));
			$name2 = $pl2['empire'].' <a class=proflink href=?profiles&num='.$id2.$authstr.'>(#'.$id2.')</a>';
			$era2 = loadEra($pl2['era'], $pl2['race']);
		} else {
			$name2 = "An unknown $uera[empire]"; // anon bounty checking
			$id2 = "???";
		}
		
		if($id3 != "0") {
			$pl3 = mysqli_fetch_array(db_safe_query("SELECT empire,race,era FROM $playerdb WHERE num=$id3;"));
			$name3 = $pl3['empire'].' <a class=proflink href=?profiles&num='.$id3.$authstr.'>(#'.$id3.')</a>';
			$era3 = loadEra($pl3['era'], $pl3['race']);
		} else {
			$name3 = "An unknown $uera[empire]"; // anon bounty checking
			$id3 = "???";
		}
		
		$clan1 = $new[clan1];
		$clan2 = $new[clan2];
		$clan3 = $new[clan3];
		if($clan1 != 0)		$clname1 = db_safe_firstval("SELECT name FROM $clandb WHERE num=$clan1;").' <a class=proflink href=?clancrier&sclan='.
						$clan1.$authstr.'>('.db_safe_firstval("SELECT tag FROM $clandb WHERE num=$clan1;").')</a>';
		else			$clname1 = "None";
		if($clan2 != 0)		$clname2 = db_safe_firstval("SELECT name FROM $clandb WHERE num=$clan2;").' <a class=proflink href=?clancrier&sclan='.
						$clan1.$authstr.'>('.db_safe_firstval("SELECT tag FROM $clandb WHERE num=$clan2;").')</a>';
		else			$clname2 = "None";
		if($clan3 != 0)		$clname3 = db_safe_firstval("SELECT name FROM $clandb WHERE num=$clan3;").' <a class=proflink href=?clancrier&sclan='.
						$clan1.$authstr.'>('.db_safe_firstval("SELECT tag FROM $clandb WHERE num=$clan3;").')</a>';
		else			$clname3 = "None";
		$shielded = $new[shielded]%10;
		$failed = 0;

		$troops1 = explode('|', $new[troops1]);
		$troops2 = explode('|', $new[troops2]);
		foreach($troops1 as $id => $key)
			$troops1[$id] = gamefactor($key);
		foreach($troops2 as $id => $key)
			$troops2[$id] = gamefactor($key);

		$factors = array('cash1', 'cash2', 'food1', 'food2', 'runes1', 'runes2', 'wizards1', 'wizards2');
		foreach($factors as $key)
			$new[$key] = gamefactor($new[$key]);

		$troops1sum = array_sum($troops1);
		$troops2sum = array_sum($troops2);

		if($shielded == -1)
			$failed = 1;

		echo '<tr style="vertical-align:top"><th>';

		$hours = (time() - $new[time]) / 3600;
		if ($hours > 24) {
			$days = floor($hours / 24);
			print $days . " days, ";
			$hours -= $days * 24;
		} 
		echo round($hours, 1).' hours ago';
		echo '</th>';

//		echo '<td>'.$new[code].'</td>';		//debug
		switch ($new[code]) {
			case 100:
				echo '<td colspan="2"><span class="cgood">You sold:<br>';
				foreach($config[troop] as $num => $mktcost) {
					if($troops1[$num])
						echo '<li>'.commas($troops1[$num]).' '.$uera["troop$num"].'</li>';
				}

				if(!empty($new[food1]))
					echo '<li>'.commas($new[food1]).' '.$uera["food"].'</li>';
				if(!empty($new[runes1]))
					echo '<li>'.commas($new[runes1]).' '.$uera["runes"].'</li>';

				echo 'on the market for $'.commas($new[cash1]).'.<br>';
				echo 'The cash is available in your <a href="?bank'.$authstr.'">Savings</a> account.</span></td>';
				break;
			case 101:
				//raffle
				echo '<td colspan="2"><span class="cgood">You check your raffle ticket and find it is the winning number!<br>';
				$get = '';
				if(!empty($new[food1]))		$get = commas($new[food1]).' food';
				elseif(!empty($new[cash1]))	$get = '$'.commas($new[cash1]);
				echo 'You have won '.$get.'!</span></td>';
				break;
			case 102:
				echo '<td><span class="cgood">'.$name2.' has sent you '.commas($troops1[$config[aidtroop]]).' ';
				echo $era2["troop".$config[aidtroop]].' carrying...</span></td><td><span class="cgood">';
				foreach($troops1 as $num => $amt) {
					if($amt && $num != $config[aidtroop]) {
						echo commas($amt).' '.$era2["troop$num"]."<br>\n";
					}
				}
				if($new[cash1])		echo '$'.commas($new[cash1])."<br>\n";
				if($new[food1])		echo commas($new[food1]).' food'."<br>\n";
				if($new[runes1])	echo commas($new[runes1]).' '.$uera["runes"]."<br>\n";
				break;

			case 110:
				echo '<td colspan="2"><span class="cgood">You founded '.$clname1.'.</span></td>';
				break;
			case 111:
				echo '<td colspan="2"><span class="cbad">You disbanded '.$clname1.'.</span></td>';
				break;
			case 112:
				echo '<td colspan="2"><span class="cgood">'.$name2;
				echo ' joined '.$clname1.'.</span></td>';
				break;
			case 113:
				echo '<td colspan="2"><span class="cgood">'.$name2;
				echo ' left '.$clname1.'.</span></td>';
				break;
			case 114:
				echo '<td colspan="2"><span class="cwarn">'.$name2;
				echo ' removed you from '.$clname1.'.</span></td>';
				break;
			case 115:
				echo '<td colspan="2"><span class="cgood">'.$name2;
				echo ' made you leader of '.$clname1.'.</span></td>';
				break;
			case 116:
				echo '<td colspan="2"><span class="cgood">You inherited leadership of '.$clname1.'.</span></td>';
				break;
			case 117:
				echo '<td colspan="2"><span class="cwarn">'.$name2;
				echo ' disbanded '.$clname1.'.</span></td>';
				break;
			case 118:
				echo '<td colspan="2"><span class="cgood">'.$name2;
				echo ' made you an officer of '.$clname1.'.</span></td>';
				break;
			case 119:
				echo '<td colspan="2"><span class="cwarn">'.$name2;
				echo ' removed you from your position in '.$clname1.'.</span></td>';
				break;

			case 201:
				echo '<td colspan="2"><span class="'.($failed?'cwarn':'cbad').'">You find ';
				if($failed)	echo $name2.' trying to view your stats!';
				else		echo 'another '.$uera[empire].' viewing your stats!';
				echo '</span></td>';
				break;
			case 202:
				if($failed)		echo '<td colspan="2"><span class="cwarn">'.$name2.' tried to eliminate your forces';
				else			echo '<td colspan="2"><span class="cbad">'.$name2.' eliminated '.($shielded?1:3).'% of your forces';
				if($shielded == 1)	echo ',</span> <span class="cgood">though your '.$uera[wizards].' prevented too much damage';
				echo '!</span></td>';
				break;
			case 204:
				if($failed)		echo '<td colspan="2"><span class="cwarn">'.$name2.' tried to poison your food';
				else			echo '<td colspan="2"><span class="cbad">'.$name2.' poisoned '.commas($new[food1]).' of your food';
				if($shielded == 1)	echo ',</span> <span class="cgood">though your '.$uera[wizards].' prevented too much damage';
				echo '!</span></td>';
				break;
			case 205:
				if($failed)		echo '<td colspan="2"><span class="cwarn">'.$name2.' tried to wreck your runes';
				else			echo '<td colspan="2"><span class="cbad">'.$name2.' wrecked '.commas($new[runes1]).' of your runes';
				if($shielded == 1)	echo ',</span> <span class="cgood">though your '.$uera[wizards].' prevented too much damage';
				echo '!</span></td>';
				break;
			case 206:
				if($failed)		echo '<td colspan="2"><span class="cwarn">'.$name2.' tried to burn your buildings';
				else			echo '<td colspan="2"><span class="cbad">'.$name2.' burned '.commas($new[land1]).' of your buildings';
				if($shielded == 1)	echo ',</span> <span class="cgood">though your '.$uera[wizards].' prevented too much damage';
				echo '!</span></td>';
				break;
			case 211:
				if($failed)
					echo '<td><span class="cwarn">'.$name2.' tried to attack you with '.$era2[wizards].'!</span></td><td>';
				else if($shielded == 0)	{
					echo '<td><span class="cbad">'.$name2.' defeated you with '.$era2[wizards].'!</span></td>';
					echo '<td><span class="cbad">Your enemy captured '.commas($new[land1]).' acres and destroyed:<br>';
				}
				if($shielded == 1) {
					echo '<td><span class="cwarn">'.$name2.' lost a battle with your '.$uera[wizards].'!</span></td>';
					echo '<td><span class="cwarn">You lost<br>';
				}
				if(!$failed)
					echo commas($new[wizards1]).' '.$uera[wizards].'</span><br>';
				echo '<span class="cgood">You managed to destroy <br>'.commas($new[wizards2]).' '.$era2[wizards];
				echo '</span></td>';
				break;
			case 212:
				if($failed)		echo '<td colspan="2"><span class="cwarn">'.$name2.' tried to steal your money';
				else			echo '<td colspan="2"><span class="cbad">'.$name2.' stole $'.commas($new[cash1]).' of your cash';
				if($shielded == 1)	echo ',</span> <span class="cgood">though your '.$uera[wizards].' prevented too much damage';
				echo '!</span></td>';
				break;
			case 213:
				if($failed)		echo '<td colspan="2"><span class="cwarn">'.$name2.' tried to steal your food';
				else			echo '<td colspan="2"><span class="cbad">'.$name2.' stole '.commas($new[food1]).' of your food';
				if($shielded == 1)	echo ',</span> <span class="cgood">though your '.$uera[wizards].' prevented too much damage';
				echo '!</span></td>';
				break;

			case 300:
				echo '<td colspan="2"><span class="cgood">Your forces came to the aid of '.$name1.' in defense of '.$name2.'!';
				echo '</span></td>';
				break;
			case 302:
			case 303:
			case 304:
			case 305:
			case 303:
			case 306:
			case 307:
			case 308:
			case 309:
			case 310:
			case 311:
			case 312:
			case 313:
			case 314:
			case 315:
			case 316:
			case 317:
			case 318:
			case 319:
			case 320:
				$num = $new[code] - 300;
				$name = $atknames[$num];
				$failed = true;
				if($new[land1] > 0)	$failed = false;
				if($new[food1] > 0)	$failed = false;
				if($new[cash1] > 0)	$failed = false;

				if($failed) {
					echo '<td><span class="cwarn">'.$name2.' attacked you in a '.$name.'!</span></td>';
					echo '<td><span class="cgood">You held your defense and your enemy was repelled, ';
					if($troops1sum != 0)	echo '<span class="cwarn">but you lost:<br>';
					else			echo 'and you lost nothing!<br>';
				} else {
					echo '<td><span class="cbad">'.$name2.' attacked you in a '.$name.'!</span></td>';
					echo '<td><span class="cbad">Your enemy captured ';
					switch($shielded) {
						case 1:	echo commas($new[land1]).' acres';	break;
						case 2:	echo '$'.commas($new[cash1]);		break;
						case 3:	echo commas($new[food1]).' food';	break;
					}
					if($troops1sum == 0)	echo '.<br>';
					else			echo ' and destroyed:<br>';
				}

				if($troops1sum != 0) {
					foreach($config[troop] as $num => $mktcost) {
						if($troops1[$num])
							echo '<li>'.commas($troops1[$num]).' '.$uera["troop$num"].'</li>';
					}
				}
				if($troops2sum != 0) {
					echo '</span><span class="cgood"><br>You managed to destroy:<br>';
					foreach($config[troop] as $num => $mktcost) {
						if($troops2[$num])
							echo '<li>'.commas($troops2[$num]).' '.$era2["troop$num"].'</li>';
					}
				}
				echo '</span></td>';
				break;
			case 399:
				echo '<td colspan="2"><span class="cbad">As '.$name2.' delivers their final blow, your '.$uera[empire].' collapses...</span></td>';
				break;

			case 401:
				echo '<td colspan="2"><span class="cgood">You have set a bounty on '.$name2.'!</span></td>';
				break;
			case 402:
				echo '<td colspan="2"><span class="cwarn">'.$name2.' has set a bounty on you!</span></td>';
				break;
			case 403:
				echo '<td colspan="2"><span class="cwarn">'.$name2.' has fulfilled a bounty on you set by '.$name3.'!</span></td>';
				break;
			case 404:
				echo '<td colspan="2"><span class="cgood">You fulfilled a bounty set on '.$name2.' by '.$name3.'!</span></td>';
				break;
			case 405:
				echo '<td colspan="2"><span class="cgood">'.$name2.' has fulfilled a bounty set on '.$name3.' by you!</span></td>';
				break;
			case 406:
				echo '<td colspan="2"><span class="cgood">Your bounty on '.$name2.' has been dropped as the '.$uera[empire].' is dead!</span></td>';
				break;
			case 450:
				echo '<td colspan="2"><span class="cgood">You have been granted access to the clan treasury.</span></td>';
				break;
			case 451:
				echo '<td colspan="2"><span class="cwarn">Your access to the clan treasury has been revoked.</span></td>';
				break;
			case 501:
				echo '<td colspan="2"><span class="cgood">'.$name2.':';
				echo '<ul>';
				if($new[cash1] != 0)
					echo '<li>'.($new[cash1]>0?'gave':'took').' $'.commas(abs($new[cash1])).' '.($new[cash1]>0?'to':'from').' the clan treasury.</li>';
				if($new[food1] != 0)
					echo '<li>'.($new[food1]>0?'gave':'took').' '.commas(abs($new[food1])).' food '.($new[food1]>0?'to':'from').' the clan granary.</li>';
				if($new[runes1] != 0)
					echo '<li>'.($new[runes1]>0?'gave':'took').' '.commas(abs($new[runes1])).' runes '.($new[runes1]>0?'to':'from').' the clan loft.</li>';
				if($new[cash1] == 0 && $new[food1] == 0 && $new[runes1] == 0)
					echo '<li>messed around with the treasury, but made no net change at all.</li>';
				echo '<ul>';
				echo '</span></td>';
				break;
			case 502:
				echo '<td colspan="2"><span class="cgood">You:<br>';
				echo '<ul>';
				if($new[cash1] != 0)
					echo '<li>'.($new[cash1]>0?'gave':'took').' $'.commas(abs($new[cash1])).' '.($new[cash1]>0?'to':'from').' the clan treasury.</li>';
				if($new[food1] != 0)
					echo '<li>'.($new[food1]>0?'gave':'took').' '.commas(abs($new[food1])).' food '.($new[food1]>0?'to':'from').' the clan granary.</li>';
				if($new[runes1] != 0)
					echo '<li>'.($new[runes1]>0?'gave':'took').' '.commas(abs($new[runes1])).' runes '.($new[runes1]>0?'to':'from').' the clan loft.</li>';
				if($new[cash1] == 0 && $new[food1] == 0 && $new[runes1] == 0)
					echo '<li>messed around with the treasury, but made no net change at all.</li>';
				echo '</ul>';
				echo '</span></td>';
				break;

			case 601:
				$wtime = db_safe_firstval("SELECT warset_time FROM $playerdb WHERE num=$id2;");
				$peace = round(($wtime - time()) / 3600);
				echo '<td colspan="2"><span class="cwarn">'.$name2.' has declared war on you! Neutrality can be declared in '.$peace.' hours.</span></td>';
				break;
			case 602:
				echo '<td colspan="2"><span class="cgood">'.$name2.' has declared neutrality with you.</span></td>';
				break;
			case 603:
				$wtime = db_safe_firstval("SELECT warset_time FROM $playerdb WHERE num=$id1;");
				$peace = round(($wtime - time()) / 3600);
				echo '<td colspan="2"><span class="cgood">You have declared war on '.$name2.'! Neutrality can be declared in '.$peace.' hours.</span></td>';
				break;
			case 604:
				echo '<td colspan="2"><span class="cgood">You have declared neutrality with '.$name2.'.</span></td>';
				break;
			case 605:
				$ptime = db_safe_firstval("SELECT peaceset_time FROM $playerdb WHERE num=$id2;");
				$neut = round(($ptime - time()) / 3600);
				echo '<td colspan="2"><span class="cgood">'.$name2.' has declared peace with you! Neutrality can be declared in '.$neut.' hours.</span></td>';
				break;
			case 606:
				$ptime = db_safe_firstval("SELECT peaceset_time FROM $playerdb WHERE num=$id1;");
				$neut = round(($ptime - time()) / 3600);
				echo '<td colspan="2"><span class="cgood">You have declared peace with '.$name2.'! Neutrality can be declared in '.$neut.' hours.</span></td>';				
				break;		
		
		} 

		echo '<td>'.$new[num].'</td></tr>';
	}

	echo '</table>';
	return 1;
} 

?>
