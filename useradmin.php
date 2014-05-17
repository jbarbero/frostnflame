<?
include("header.php");

if ($users[disabled] != 2)
    TheEnd("You are not an administrator!");

if ($do_modify == 1)
{
    reset($modify);
    while (list(,$modify_num) = each($modify))
    {
        fixInputNum($modify_num);
        fixInputNum($adminclan);
        if ($modify_setdisabledmulti)
        {
            $modify_setmulti = 1;
            $modify_setdisabled = 1;
        }
        if ($modify_clrdisabledmulti)
        {
            $modify_clrmulti = 1;
            $modify_clrdisabled = 1;
        }
        if ($modify_setmulti)
        {
            print "$modify_num marked as multi!<BR>\n";
            db_safe_query("UPDATE $playerdb SET ismulti=1 WHERE num=$modify_num;");
        }
        if ($modify_clrmulti)
        {
            print "$modify_num no longer marked as multi!<BR>\n";
            db_safe_query("UPDATE $playerdb SET ismulti=0 WHERE num=$modify_num;");
        }
        if ($modify_setdisabled)
        {
            print "$modify_num disabled!<BR>\n";
            db_safe_query("UPDATE $playerdb SET disabled=3 WHERE num=$modify_num;");
        }
        if ($modify_clrdisabled)
        {
            print "$modify_num no longer disabled!<BR>\n";
            db_safe_query("UPDATE $playerdb SET disabled=0,idle=$time WHERE num=$modify_num;");
        }
        if ($modify_admin)
        {
            print "Granting $modify_num administrative privileges!<BR>\n";
            db_safe_query("UPDATE $playerdb SET disabled=2 WHERE num=$modify_num;");
        }
        if ($modify_delete)
        {
            print "Deleting $modify_num!<BR>\n";
            db_safe_query("UPDATE $playerdb SET land=0,disabled=4 WHERE num=$modify_num;");
            $users[kills]++;
        }
        if ($modify_boot)
        {
            print "Booting $modify_num from protection!<BR>\n";
            $used = db_safe_firstval("SELECT turnsused FROM $playerdb WHERE num=$modify_num;");
            if($used <= $config['protection']) {
                $used = $config['protection'] + 1;
                db_safe_query("UPDATE $playerdb SET turnsused=$used WHERE num=$modify_num;");
            }
        }
        if ($putclan)
        {
            print "Putting $modify_num in clan $adminclan!<BR>\n";
            db_safe_query("UPDATE $playerdb SET clan=$adminclan WHERE num=$modify_num;");
            db_safe_query("UPDATE $clandb SET members=members+1 WHERE num=$adminclan;");
        }
    }
    saveUserData($users,"kills");
}

if (!$sortby)
    $sortby = "ip";
sqlQuotes($sortby);
$multis = db_safe_query("SELECT num,empire,clan,ip,name,username,email,idle,signedup,disabled,turnsused,land,ismulti FROM $playerdb WHERE ip!='0.0.0.0' ORDER BY $sortby, num ASC;");
$ctags = loadClanTags();
?>
<form method="post" action="?useradmin<?=$authstr?>">
<table border=1>
<tr><th class="aright"><a href="?useradmin&amp;sortby=num<?=$authstr?>">Num</a></th>
      <th class="aleft"><a href="?useradmin&amp;sortby=empire<?=$authstr?>"><?=$uera[empireC]?></a></th>
    <th class="acenter"><a href="?useradmin&amp;sortby=clan<?=$authstr?>">Clan</a></th>
    <th class="aright"><a href="?useradmin&amp;sortby=ip<?=$authstr?>">IP</a></th>
    <th class="acenter"><a href="?useradmin&amp;sortby=name<?=$authstr?>">Name</a></th>
    <th class="acenter"><a href="?useradmin&amp;sortby=username<?=$authstr?>">Username</a></th>
    <th class="acenter"><a href="?useradmin&amp;sortby=email<?=$authstr?>">E-mail</a></th>
    <th class="aright"><a href="?useradmin&amp;sortby=idle<?=$authstr?>">Idle</a></th>
    <th class="aright"><a href="?useradmin&amp;sortby=idle<?=$authstr?>">Joined</a></th>
    <th class="aright">Status</th>
    <th class="aright">Modify</th></tr>
<?
while ($multi = mysqli_fetch_array($multis))
{
    $idle = $time - $multi[idle];
    if ($multi[$sortby] == $lastsort)
        if ($multi[ismulti])
            if ($multi[disabled] == 3)
                print '<tr class="cbad">'."\n";
            else    print '<tr class="cgood">'."\n";
        else    print '<tr class="cwarn">'."\n";
    else    print "<tr>\n";
?>
    <th class="aright"><?=$multi[num]?></th>
    <td class="aleft"><?=$multi[empire]?></td>
    <td class="acenter"><?=$ctags["$multi[clan]"]?></td>
    <td class="aright"><?=$multi[ip]?></td>
    <td class="acenter"><?=$multi[name]?></td>
    <td class="acenter"><?=$multi[username]?></td>
    <td class="acenter"><?=$multi[email]?></td>
    <td class="aright"><?=gmdate("d",$idle)-1?>:<?=gmdate("H:i:s",$idle)?></td>
    <td class="aright"><?=str_replace(' ', '&nbsp;', date($dateformat, $multi[signedup]))?></td>
    <td class="aright"><?
    switch ($multi[disabled])
    {
    case 0:    if ($multi[land] == 0)
            print "Dead&nbsp;(uninformed)";
        elseif ($multi[ismulti])
            print "Multi&nbsp;(legal)";
        elseif ($multi[turnsused] > $config[protection])
            print "New&nbsp;account";
        else    print "Normal";
        break;
    case 1: if ($multi[land] == 0)
            print "Dead&nbsp;(informed)";
        break;
    case 2:    print "Admin";
        break;
    case 3:    if ($multi[ismulti])
            print "Multi&nbsp;(disabled)";
        else    print "Cheater";
        break;
    case 4:    print "Deleted";
        break;
    }
?></td>
    <td class="aright"><input type="checkbox" name="modify[]" value="<?=$multi[num]?>"<?if ($multi[num] == $users[num]) print " disabled";?>></td></tr>
<?
    
    $lastsort = $multi[$sortby];
}
?>
<tr><th colspan="10" class="aright">
        <input type="hidden" name="do_modify" value="1">
        <input type="hidden" name="sortby" value="<?=$sortby?>">
    <select name="adminclan" size="1">
        <option value="0" selected>None: Unallied <?=$uera[empireC]?>s</option>
    <?
            $clanlist = db_safe_query("SELECT num,name,tag FROM $clandb WHERE members>0 ORDER BY num DESC;");
            while ($clan = mysqli_fetch_array($clanlist))
            {
    ?>
    <option value="<?=$clan[num]?>"><?=$clan[tag]?>: <?=$clan[name]?></option>
    <?
            }
    ?>
    </select>
    <input type="submit" name="putclan" value="Put in Clan"><br>

        Multi: <input type="submit" name="modify_setmulti" value="Set"> / <input type="submit" name="modify_clrmulti" value="Clr"><br>
        Disabled: <input type="submit" name="modify_setdisabled" value="Set"> / <input type="submit" name="modify_clrdisabled" value="Clr"><br>
        Disable Multi: <input type="submit" name="modify_setdisabledmulti" value="Set"> / <input type="submit" name="modify_clrdisabledmulti" value="Clr"><br>
        Delete Account: <input type="submit" name="modify_delete" value="NUKE"><br>
        Boot from Protection: <input type="submit" name="modify_boot" value="KICK"><br>
        Make Admin (Clr Disabled to undo): <input type="submit" name="modify_admin" value="ADMIN"></th></tr>
</table>
</form>
<?
TheEnd('');
?>
