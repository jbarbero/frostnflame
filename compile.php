<?
//include('scripting-funcs.php');
//Needs heavy testing
//structures: ifs, loops
error_reporting(E_ALL);
// WE NEED TO REDEFINE TheEnd() TO PREVENT AN ERROR FROM CRASHING TURNS.PHP!!!!


//set $lockdb to 1, run the script, unset $lockdb to get a sample run
//beware in scripting functions like messages

define('QUOTE', 'QUOTE');

function addstatement($statement) {
    global $compiled, $end_count, $until_count;
    $tabs = @str_repeat("\t", $end_count+$until_count);
    $statement = trim($statement);
    $compiled .= "$tabs$statement\n";
}

function removeBadChars($str) {
    $regex = '/[^<>=_;+#\-\*\/\^0-9A-Za-z\s]/';

    $str = preg_replace($regex, "", $str);
    $str = str_replace("'", "'", $str);
    $str = str_replace('"', "", $str);
    return $str;
}

function fixConstants($str) {
    global $const;
    foreach($const as $orig => $replace) {
        $regex = "/([^0-9A-Za-z><.])${orig}([^0-9A-Za-z><.])/";
        $regex2 = "/^${orig}([^0-9A-Za-z><.])/";
        $regex3 = "/([^0-9A-Za-z><.])${orig}\$/";
        $regex4 = "/^${orig}\$/";
        $reg_replace= "\\1${replace}\\2";

        $str = preg_replace($regex, $reg_replace, $str);
        $str = preg_replace($regex2, $reg_replace, $str);
        $str = preg_replace($regex3, $reg_replace, $str);
        $str = preg_replace($regex4, $reg_replace, $str);
    }
    return $str;
}

function fixWhitespace($str) {
    $str = trim($str);
    $str = preg_replace("/\s+/", ' ', $str);
    return $str;
}

function fixConditional($cond) {
    global $vars, $problem, $error;
    $cond = str_replace('==', ' EQSIGN ', $cond);
    $cond = str_replace('!=', ' NEQUALS ', $cond);
    $cond = str_replace('>=', ' GTE ', $cond);
    $cond = str_replace('<=', ' LTE ', $cond);
    $cond = str_replace('=', ' == ', $cond);
    $cond = str_replace(' EQSIGN ', '==', $cond);
    $cond = str_replace(' NEQUALS ', '!=', $cond);
    $cond = str_replace(' GTE ', '>=', $cond);
    $cond = str_replace(' LTE ', '<=', $cond);

    $args = preg_split("/[\s]+/", $cond);
    $ops = array('==', '!=', '<', '>', '<=', '>=', '||', '&&');
    $argstr = '';
    foreach($args as $num => $arg) {
        if(!is_numeric($arg) && empty($vars[$arg]) && !in_array($arg, $ops))
            $arg = "'$arg'";
        $argstr .= "$arg ";
    }

    $conds = preg_split("/((\&\&)|(\|\|))/", $argstr);  
    foreach($conds as $num => $cond) {
        $cond = trim($cond);
        echo $problem;
        $stuff = preg_split("/[\s]+/", $cond);
        if(count($stuff) != 3)
            $problem = true;
        $first = $stuff[0];
        $op = $stuff[1];
        $second = $stuff[2];
        if(in_array($first, $ops) || in_array($second, $ops))
            $problem = true;
        if(!in_array($op, $ops))
            $problem = true;

        if($problem) {
//            $error = "Bad conditional section: \"$cond\" ($problem)!";
            break;
        }
    }

    return $argstr;
}

function strBeginsWith($str, $start) {
    $len = strlen($start);
    $sub = substr($str, 0, $len);
    return ($sub == $start);
}

function argSplit($str) {
    $args = preg_split("/[\s,]+/", $str);
    unset($args[0]);
    foreach($args as $num => $arg) {
        if($arg == '')
            unset($args[$num]);
    }
//    echo "argsplitting: \"$str\"<br>\n";
//    echo "args: ".print_r($args)."<br>\n";
    return $args;
}

function getFuncArgs($line) {
    $stuff = argsplit($line);
    $args = array();
    foreach($stuff as $item) {
        $pos = strpos($item, '=');
        if(!$pos)
            continue;
        $var = substr($item,0,$pos);
        $value = substr($item,$pos+1);
        $args[$var] = $value;
    }
//    echo "func-argsplitting: \"$line\"<br>\n";
//    echo "funcargs: ".print_r($args)."<br>\n";
    return $args;
}

function parseLine($lineno) {
    global $code, $compiled, $count, $vars, $functions, $end_count, $until_count, $problem, $counter_counter, $error;
    $line = $code[$lineno];
    $line = fixWhitespace($line);
    if($line == '') {
        addstatement('');
        return;
    }
    if(strpos($line,'#',1)) {
        $problem = true;
        $error = "Bad line, comment marks may occur only at beginning of a line!";
        return;
    }


    if(strBeginsWith($line, '#')) {
        addstatement($line);
        return;
    }
    else if(strBeginsWith($line, 'declare ')) {
        $decs = argsplit($line);
        $statement = '';
        foreach($decs as $variable) {
            $regex = "/[^0-9A-Za-z]/";
            $variable = preg_replace($regex, "", $variable);
            $vars["$variable"] = "\$priv_$variable";
            $statement .= "$variable; ";
        }
        addstatement($statement);
        return;
    }
    else if(strBeginsWith($line, 'if ')) {
        $statement = "if";
        $condition = substr($line, 3);
        $condition = fixConditional($condition);
        $statement .= " ($condition) {";
        addstatement($statement);
        $end_count++;
        return;
    }
    else if(strBeginsWith($line, 'else')) {
        $line = substr($line, 5);
        $code[$count] = $line;
        $count--;
        addstatement("else {");
        $compiled = substr($compiled, 0, -1);
        $end_count++;
        return;
    }
    else if(strBeginsWith($line, 'end')) {
        $end_count--;
        addstatement("}");
        return;
    }
    else if(strBeginsWith($line, 'stop')) {
        // IMPORTANT! This assumes that user code will be run in a function...
        addstatement("return;");
        return;
    }
    else if(strBeginsWith($line, 'while ')) {
        $statement = "while";
        $condition = substr($line, 6);
        $condition = fixConditional($condition);
        $cntvar = "cnt$counter_counter";
        $condition .= " && \$$cntvar <= 1000";
        $statement .= " ($condition) {";
        addstatement($statement);
        $statement = "\$$cntvar++;";
        addstatement($statement);
        $counter_counter++;
        $end_count++;
        return;
    }
    else if(strBeginsWith($line, 'for ')) {
        $args = argsplit($line);
        $var = $args[1];
        $start = $args[2];
        $end = $args[3];
        $increment = round(abs($args[3]));
        if($increment == 0)
            $increment = 1;
        fixInputNum($start);
        fixInputNum($end);
        if($end > $increment*1000)            //can't go above 1000
            $end = $increment*1000;

        $statement = "for($var = $start; $var <= $end; $var += $increment) {";
        $vars[$var] = "\$priv_$var";
        addstatement($statement);
        $end_count++;
        return;
    }
    else if(strBeginsWith($line, 'do')) {
        $line = substr($line, 3);
        $code[$count] = $line;
        $count--;
        addstatement("do {");
        $compiled = substr($compiled, 0, -1);
        $until_count++;
        return;
    }
    else if(strBeginsWith($line, 'until ')) {
        $cntvar = "cnt$counter_counter";
        $statement2 = "\$$cntvar++;";
        addstatement($statement2);
        $counter_counter++;

        $statement = "} until";
        $condition = substr($line, 6);
        $condition = fixConditional($condition);
        $condition .= " && \$$cntvar <= 1000";
        $statement .= " ($condition)";
        $until_count--;
        addstatement($statement);
        return;
    }


    foreach($vars as $var => $value) {
        $temp_line = preg_replace("/[\s]/", "", $line); // Killing spaces...
        $regex = "/^$value(\+|\-)\\1/"; // Checks if it's something like $var++ $var--
        $regex2 = "/^$value(?:\+|\-|)=[0-9+\-\*\/]+(.*)/"; // Checcks if it's something like $var += 432 $var -= 432 , $var = 432
        $fail_char = "/([^0-9+\-\*\/])/";
        if(  (preg_match($regex, $temp_line) || preg_match($regex2, $temp_line, $matches)) && !preg_match($fail_char, $matches[0])) {
            return;
        // Found something for this variable in this line!
        }
    }

    //function calls
    $spos = strpos($line, " ");
    if(!$spos)
        $fname = $line;
    else
        $fname = substr($line, 0, $spos);
    if(!in_array($fname, $functions)) {
        $problem = true;
        $error = "No function called \"$fname\" exists!";
        return;
    }
    $args = getFuncArgs($line);
    $argstr = '';
    foreach($args as $var => $value) {
        $argstr .= "'$var' => '$value', ";
    }
    $argstr = substr($argstr, 0, -2);
    $statement = "fn_$fname ( array($argstr) );";
    addstatement($statement);
    return;

    //if it's none of the above, there's a problem
    $problem = true;
    $error = "Error on line $lineno!";
    return;
}


function compile($input) {
    global $code, $compiled, $count, $vars, $const, $functions, $end_count, $until_count, $problem, $counter_counter, $error;
    $problem = false;

    $compiled = '';
    $counter_counter = 1;

    $input = strtolower($input);
    $input = str_replace(";", "\n", $input);
    $input = removeBadChars($input);
    $input = fixConstants($input);
    $code = explode("\n", $input);

    $count = 0;
    $end_count = 0;
    $until_count = 0;
    for($count=0; $count<count($code); $count++) {
        parseLine($count);
        if($problem)
            die("<br><b>Error:</b> $error<br>\n");
    }

    //replace variables
    foreach($vars as $var => $value) {
        if($var == '')
            continue;
        $regex = "/([^0-9A-Za-z><.])${var}([^0-9A-Za-z><.])/";
        $regex2 = "/^${var}([^0-9A-Za-z><.])/";
        $regex3 = "/([^0-9A-Za-z><.])${var}\$/";
        $regex4 = "/^${var}\$/";
        $reg_replace= "\\1${value}\\2";

        $compiled = preg_replace($regex, $reg_replace, $compiled);
        $compiled = preg_replace($regex2, $reg_replace, $compiled);
        $compiled = preg_replace($regex3, $reg_replace, $compiled);
        $compiled = preg_replace($regex4, $reg_replace, $compiled);
    }

    if($end_count > 0) {
        $problem = true;
        $error = "Loop or test closing END not found!";
    }
    if($end_count < 0) {
        $problem = true;
        $error = "Too many END statements!";
    }
    if($until_count > 0) {
        $problem = true;
        $error = "DO loop closing UNTIL not found!";
    }
    if($until_count < 0) {
        $problem = true;
        $error = "Too many UNTIL statements!";
    }

    if($problem == true)
        echo "<br><b>Error:</b> $error<br>\n";

    return $compiled;
}

$const = array();
$const['ne'] = '!=';
$const['eq'] = '==';
$const['equals'] = '==';
$const['lt'] = '<';
$const['gt'] = '>';
$const['lte'] = '<=';
$const['gte'] = '>=';
$const['and'] = '&&';
$const['or'] = '||';
$const['then'] = '';
//$const[''] = '';


$vars = array();
$vars['health'] = '$health';
$vars['turns'] = '$users[turns]';
$vars['land'] = '$users[land]';
//$vars[''] = '';

$functions = array('scout', 'attack', 'forage');
//turn uses
/** wizard stuff
$functions[''] = '';
**/

//clan functions
$functions['clanboot'] = '';
// I suppose, treasury open/close, etc.


$functions[''] = '';
$functions[''] = '';
$functions['vacation'] = 'vacation';
$functions['delete'] = 'delete';
//$functions[] = '';


?>
