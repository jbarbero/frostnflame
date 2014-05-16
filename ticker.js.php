<?php
if(!defined("PROMISANCE"))
	die(" ");
header("Pragma: no-cache");
// NOTE: make sure that all HTML content contains single quotes ONLY between HTML tags, or the stuff in the HTML tags will be converted to &#039; as well
$total_width = 'document.body.clientWidth*0.7';
if (!empty($_GET['width']))
	$total_width = $_GET['width'];

$ticker_message = 'The text-to-be-scrolled, ie. news. <b>HTML</b> can be used. Also, it all resides in one JavaScript file, so other sites can include it. Very good propaganda, that.';
$ticker_speed = 2;
$stock_interval = 3;

$stockmsg = array();
$stockmsg[0] = 'ROPE +1.0';
$stockmsg[1] = 'TXT -1.3';

$stocklen = count($stockmsg);

?>

//config
var total_width = <?=$total_width?>;

var marqueespeed = <?=$ticker_speed?>;
var marqueecontent = '<nobr><?=str_replace("'", "&#039;", $ticker_message)?></nobr>';
var pauseit = 1;

var stockmessages = new Array();
<?php
for($i = 0; $i < $stocklen; $i += 1) {
	echo "stockmessages[$i] = '" . str_replace("'", "&#039;", $stockmsg[$i]) . "';\n";
} 

?>
stockinterval = <?=$stock_interval?>;
var stock_width = 100;

var spacer_width = 20;

//stock script
stockcounter = 1;
stocklength = <?=$stocklen?>;
paused = false;

function stockflash() {
	if(!paused) {
		if(stockcounter >= stocklength)
			stockcounter = 0;
		stockflasher.innerHTML = stockmessages[stockcounter];
		stockcounter++;
	}
}

setInterval("stockflash()", stockinterval*1000);


//marquee script
var marqueewidth = total_width - stock_width - spacer_width;
var marqueeheight = 20;
marqueespeed = (document.all) ? marqueespeed : Math.max(1, marqueespeed-1)
	//slow speed down by 1 for NS
var copyspeed = marqueespeed;
var pausespeed = (pauseit == 0) ? copyspeed: 0;
var iedom = document.all || document.getElementById;
if (iedom)
	document.write('<span id="temp" style="visibility:hidden;position:absolute;top:-100px;left:-9000px">' + marqueecontent + '</span>');
var actualwidth = '';
var cross_marquee, ns_marquee;

function populate() {
	if (iedom){
		cross_marquee = document.getElementById? document.getElementById("iemarquee") : document.all.iemarquee;
		cross_marquee.style.left = marqueewidth + 8 + "px";
		cross_marquee.innerHTML = marqueecontent;
		actualwidth = document.all? temp.offsetWidth : document.getElementById("temp").offsetWidth;
	}
	else if (document.layers) {
		ns_marquee = document.ns_marquee.document.ns_marquee2;
		ns_marquee.left = marqueewidth + 8;
		ns_marquee.document.write(marqueecontent);
		ns_marquee.document.close();
		actualwidth = ns_marquee.document.width;
	}
	lefttime = setInterval("scrollmarquee()", 20);
}

window.onload = populate;

function scrollmarquee() {
	if (iedom) {
		if (parseInt(cross_marquee.style.left)>(actualwidth*(-1)+8))
			cross_marquee.style.left = parseInt(cross_marquee.style.left) - copyspeed + "px";
		else
			cross_marquee.style.left = marqueewidth + 8 + "px";
	}
	else if (document.layers) {
		if (ns_marquee.left>(actualwidth*(-1)+8))
			ns_marquee.left -= copyspeed;
		else
			ns_marquee.left = marqueewidth + 8;
	}
}

function pause() {
	paused = true;
	copyspeed=pausespeed;
}

function restart() {
	paused = false;
	copyspeed=marqueespeed;
}

document.write('<table border="0" cellspacing="0" cellpadding="0" style="cursor:hand" onMouseover="pause()" onMouseout="restart()"><tr><td>');

if (iedom || document.layers) {
	with (document) {
		document.write('<table border="0" cellspacing="0" cellpadding="0"><td>');
		if (iedom) {
			write('<div style="position:relative;width:' + marqueewidth + ';height:' + marqueeheight + ';overflow:hidden">');
			write('<div style="position:absolute;width:' + marqueewidth + ';height:' + marqueeheight + '">');
			write('<div id="iemarquee" style="position:absolute;left:0px;top:0px"></div>');
			write('</div></div>');
		}
		else if (document.layers) {
			write('<ilayer width=' + marqueewidth + ' height=' + marqueeheight + ' name = "ns_marquee" bgColor = ' + marqueebgcolor + '>');
			write('<layer name="ns_marquee2" left=0 top=0></layer>');
			write('</ilayer>');
		}
		document.write('</td></table>');
	}
}

document.write('</td><td width="' + spacer_width + '">&nbsp;</td><td width="' + stock_width + '" align="left"><div id="stockflasher">' + stockmessages[0] + '</div></td></tr></table>');

