<?
	function getWardropColor($wardrop) {
		global $users, $uclan, $urace, $uera, $config;
			if($users[num] == $wardrop[num])
				return "self";

			if($wardrop[disabled] == 2)
				return "admin";

			$color = "normal";
			$warflag = 0;
			if ($wardrop[clan] != 0 && ($uclan[war1] == $wardrop[clan] || $uclan[war2] == $wardrop[clan] || $uclan[war3] == $wardrop[clan] || $uclan[war4] == $wardrop[clan] || $uclan[war5] == $wardrop[clan]))
				$warflag = 1;
			$netmult = $config['default_cutoff'];
			if($users[warset] == $wardrop[num] || $wardrop[warset] == $users[num]) {
				$netmult = $config['war_cutoff'];
				$warflag = 1;
			}

			if($warflag == 1)
				$color = "good";

			if (($users[networth] > $wardrop[networth] * $config['fear_shame_mult']) && $warflag == 0)
				$color = "disabled";
			if (($wardrop[networth] > $users[networth] * $config['fear_shame_mult']) && $warflag == 0)
				$color = "disabled";
			if ($wardrop[networth] > $users[networth] * $netmult)
				$color = "dead";
			if ($users[networth] > $wardrop[networth] * $netmult)
				$color = "dead";

			if (($wardrop[era] != $users[era]) && ($users[gate] <= $time) && ($wardrop[gate] <= $time))
				$color = "protected";
			if ($wardrop[attacks] > $config['max_attacks'] && $warflag == 0)
				$color = "protected";

			if ($users[clan] == $wardrop[clan] && $users[clan] != 0)
				$color = "ally";
			if ($wardrop[clan] != 0 && (($uclan[ally1] == $wardrop[clan]) || ($uclan[ally2] == $wardrop[clan]) || ($uclan[ally3] == $wardrop[clan]) || ($uclan[ally4] == $wardrop[clan]) || ($uclan[ally5] == $wardrop[clan])))
				$color = "ally";

		return $color;
	}
?>
