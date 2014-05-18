<?
$mercdb = $bountydb;
function saveBountData (&$user, $data)
{
    global $mercdb, $lockdb;
    if ($lockdb)
        return;
    $items = explode(" ",$data);
    $update = "";
    $i = 0;
    while ($tmp = $items[$i++])
    {
        $data = $user[$tmp];
        if (is_numeric($data))
            $update .= "$tmp=$data";
        else
        {
            sqlQuotes($data);
            $update .= "$tmp='$data'";
        }
        if ($items[$i]) $update .= ",";
    }
        $my_sqltest = db_safe_query("UPDATE $mercdb SET $update WHERE num=$user[num];") or die("Invalid query: " . mysqli_error($db_link));
    if (!db_safe_query("UPDATE $mercdb SET $update WHERE num=$user[num];"))
        print "FATAL ERROR: Failed to update player data $update for user #$user[num]!<!--prob1--><BR>\n";

}

function loadBount ($num)
{
    global $mercdb;
    fixInputNum($num);
    return mysqli_fetch_array(db_safe_query("SELECT * FROM $mercdb WHERE num=$num;"));
}

function bountyCount($target_id, $user_id) {
    global $mercdb;
    fixInputNum($user_id);
    fixInputNum($target_id);
    return db_safe_firstval("SELECT count(*) FROM $mercdb WHERE t_num=$target_id AND s_num=$user_id AND filled=0;");
}

function bountyscan(&$target)
{
       global $mercdb, $newsdb, $config;
       $bounties = db_safe_query("SELECT * FROM $mercdb WHERE t_num=$target[num] AND filled=0;") or die("Invalid query: " . mysqli_error($db_link));
        //echo "<br><br><br>";
        //print("entered scan?");

        while($bounty = mysqli_fetch_array($bounties)) {
            $l = $r = $n = 0;;
            $all_three_net = $bounty[net];
            $all_three_land = $bounty[land];
            $all_three_rank = $bounty[rank];
            $just_one_net = $bounty[net];
        $just_one_land = $bounty[land];
            $just_one_rank = $bounty[rank];
        $mintroop = "0" . str_repeat("|0", sizeof($config[troop])-1); // This tells us what the troops1 value would look like in $newsdb if the defender lost no troops (ie no net change)
        $mintroop2 = str_repeat("|", sizeof($config[troop]));
        

        if($target['land'] == 0) {
            $bounty[filled] = 1;
            saveBountData($bounty, "filled");
            $setter = loadUser($bounty[s_num]);
            addNews(406, array(    id2=>$target[num], clan2=>$target[clan],
                        id1=>$setter[num], clan1=>$setter[clan])); // News to setter.
            continue;
        }

        // There is something wrong with this query... if news was changed then the query must be adjusted.
        //$query = "SELECT s_num from $newsdb WHERE s_num!=$bounty[s_num] AND num_d=$target[num] AND (type=303 OR ((type=302 OR type=303 OR type=304 OR type=305 OR type=306 OR type=307 OR type=308 OR type=309 OR type=310) AND data0!=0) OR ((type=202 OR type=204 OR type=205 OR type=206 OR type=211 OR type=212) AND data0 > -1));";
        // I still need to fix this because this only does for land
        // the problem is I need to check certain attacks based on if the requirements were land or net or rank
        // if they were land I should only consider land attacks
        // if they were net I should consider both land and net
        /*
        
        
                (( OR ) ORDER BY time DESC;"; 
                
                
                (success_reg_attack OR wizard_attack))
                
                (attack OR magic) ORDER BY time DESC;"; 

                
         ORDER BY time DESC;"; 
    
        */
        
        //I've just decided to use variables to shorten these crazy sql queries
        
        $reg_attack_code = "code=302 OR code=303 OR code=304 OR code=305 OR code=306 OR code=307 OR code=308 OR code=309 OR code=310";
        $mag_attack_code = "code=202 OR code=204 OR code=205 OR code=206 OR code=211 OR code=212";
        
        $troop_change = "troops1!='$mintroop' AND troops1!='$mintroop2'";
        $land_change = "land1>0";
        
        $net_change_no_land = "cash1>0 OR food1>0 OR runes1>0 OR ($troop_change) OR wizards1>0";
        
        
        
        if($bounty[land]>=0) {
//            $all_three_land = $target[land] + 1000;
//            $just_one_land = $target[land] - 1000;
            fixInputNum($bounty[s_num]);
            $query = "SELECT id2 from $newsdb WHERE id2!=$bounty[s_num] AND id1=$target[num] AND (((code=302 OR code=303 OR code=304 OR code=305 OR code=306 OR 
                code=307 OR code=308 OR code=309 OR code=310) AND (land1>0)) OR ((code=202 OR code=204 OR code=205 OR code=206 OR code=211 OR code=212)
                 AND land1>0)) ORDER BY time DESC;"; 
        } else {
            $l = 1;
        }
    
        fixInputNum($reg_attack_code);
        fixInputNum($mag_attack_code);
        fixInputNum($land_change);
        fixInputNum($net_change_no_land);

            if($bounty[net]>0) {
            $query = "SELECT id2 from $newsdb WHERE id2!=$bounty[s_num] AND id1=$target[num] AND (($reg_attack_code OR $mag_attack_code) AND
                 ($land_change OR $net_change_no_land)) ORDER BY time DESC;"; 
            //$query = "SELECT id2 from $newsdb WHERE id2!=$bounty[s_num] AND id1=$target[num] AND ($reg_attack_code OR $mag_attack_code) ORDER BY time DESC;"; 
            //$query = "SELECT id2 from $newsdb WHERE id2!=$bounty[s_num] AND id1=$target[num] AND ($net_change_no_land) ORDER BY time DESC;"; 
            
            //$query = "SELECT id2 from $newsdb WHERE id2!=$bounty[s_num] AND id1=$target[num] AND (((code=302 OR code=303 OR code=304 OR code=305 OR code=306 OR code=307 OR code=308 OR code=309 OR code=310) AND (land1>0 OR (troops1!='$mintroop' AND troops1!='$mintroop2'))) OR ((code=202 OR code=204 OR code=205 OR code=206 OR code=211 OR code=212) AND (land1 > 0 OR cash1 > 0 OR food1>0 OR rune1>0 OR (troops1!='$mintroop' AND troops1!='$mintroop2') OR wizards1>0))) ORDER BY time DESC;"; 
        
        } else {
            $n = 1;
        }

        if($bounty[rank]>0) {
            $query = "SELECT id2 from $newsdb WHERE id2!=$bounty[s_num] AND id1=$target[num] AND (($reg_attack_code OR $mag_attack_code) AND 
            ($land_change OR $net_change_no_land)) ORDER BY time DESC;"; 
        
        } else {
            $r = 1;
        }

        // So if it is not set to be done: it will always pass on the ALL THREE TEST and always fail on the JUST ONE test. So a user should not have any not set... otherwise it is not really a bounty right?


        /*
        
        if_requirement
            then if_met
                then succ
            else
                then fail
        else
            then pass
        */


        $knum = db_safe_firstval($query);
        if($knum) $killer = loadUser($knum);
        if(!$killer[num]) {
            //print("($bounty[t_num] No one has attacked this target (besides maybe setter of this bounty)!<br>");
            print("");
        }
        else {
            if(!$l)
                if($target[land] <= $bounty[land])
                    $l = 1;
            if(!$n)
                if($target[networth] <= $bounty[net])
                    $n = 1;
            if(!$r)
                if($target[rank] >= $bounty[rank])
                    $r = 1;
            
                //this if just looks terrible.if(  ($bounty[all_1] && $all_three_land >= $target[land] && $all_three_rank <= $target[rank] && $all_three_net >= $target[networth]) || ( !$bounty[all_1] && ($just_one_land >= $target[land] || $just_one_rank <= $target[rank] || $just_one_net >= $target[networth])))
                if( $n && $l && $r )
                {
                    //print("Entered Bounty on # $target(num) --- $bounty[all_1] = All");
                $bounty[filled] = 1;
                $killer[cash]+=$bounty[cash];
                $killer[food]+=$bounty[food];
                $killer[runes]+=$bounty[rune];
                /*$killer[troop0]+=$bounty[troop0];
                $killer[troop1]+=$bounty[troop1];
                $killer[troop2]+=$bounty[troop2];
                $killer[troop3]+=$bounty[troop3];*/
                
                $bounty[troop] = explode("|", $bounty[troop]);
                foreach($bounty[troop] as $key => $var)
                    $killer[troop][$key] += $var;
                    
                $setter = loadUser($bounty[s_num]);
                $setter[num_bounties]--;
                
                saveBountData($bounty, "filled");
                saveUserData($setter, "num_bounties");
                saveUserData($killer, "networth cash food runes troops");
//                printf("Saving data");
                
                addNews(405, array(    id3=>$target[num], clan3=>$target[clan],
                            id2=>$killer[num], clan2=>$killer[clan],
                            id1=>$setter[num], clan1=>$setter[clan])); // News to setter.
                
                if($bounty[anon]) $setter[num] = "???";
                          $setter[clan] = "";
                addNews(403, array(    id1=>$target[num], clan1=>$target[clan],
                            id2=>$killer[num], clan2=>$killer[clan],
                            id3=>$setter[num], clan3=>$setter[clan])); // News to target.
                addNews(404, array(    id2=>$target[num], clan2=>$target[clan],
                            id1=>$killer[num], clan1=>$killer[clan],
                            id3=>$setter[num], clan3=>$setter[clan])); // News to killer.
                
                $kera = loadEra($killer['era'], $killer['race']);
                        //echo "You have fullfilled the bounty set by $bounty[s_name] for \$" . commas($bounty[cash]) . ", " . commas($bounty[food]) . " food, " . commas($bounty[runes] . " runes, " . commas($bounty[troop0]) . " $kera[troop0], " . commas($bounty[troop1]) . " $kera[troop1], " .  commas($bounty[troop2]) . " $kera[troop2], " . commas($bounty[troop3]) . " $kera[troop3].<br>";
            }
        }
        }

}


function recalcBounties() {
    global $playerdb;

        $all_users = db_safe_query("SELECT * FROM $playerdb ORDER BY networth DESC;");
        while ($one_user = mysqli_fetch_array($all_users))
        {
                $r = bountyscan($one_user);
        }
}



/*

function printBountyLine (&$bounty, $x)
{

    global $users;
    if ($bounty[s_num] == $users[num]) $color = "good";
        if ($bounty[t_num] == $users[num]) $color = "bad";
       $ccolor = "";


return "<tr class=\"c<?=$color?>\"><td class=\"aright\"><?=$x?></td><td class=\"acenter\"><?=$bounty[t_name]?> <a class=proflink href=?profiles&num=<?=$bounty[t_num]?>$authstr>(#<?=$bounty[t_num]?>)</a></td><td class=\"acenter\"><?=$bounty[s_name]?> <a class=proflink href=?profiles&num=<?=$bounty[s_num]?><?=$authstr?>>(#<?=$bounty[s_num]?>)</a></td><td class=\"aright\">$<?=commas($bounty[cash])?></td><td class=\"aright\"><?=commas($bounty[low])?> land</td>";
}



function printBountyHeader($color)
{


return "<tr class=\"era<?=$color?>\"><th style=\"width:10%\" >Number</th><th style=\"width:30%\">Bounty Target</th><th style=\"width:30%\">Bounty Setter</th><th style=\"width:15%\" >Bounty Price</th><th style=\"width:15%\" >Drop Point</th></tr>";
}

*/
?>
