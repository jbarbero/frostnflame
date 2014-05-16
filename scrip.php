<pre>
<?
if(!defined("PROMISANCE"))
	die(" ");
function fixInputNum (&$num)
{
        $num = round(str_replace(",","",$num));
        $num = round(abs($num));   
}


include("compile.php");

$s = "
declare this that who
declare a=b, this0, 05, 0.4

while this ne 4
attack target=4, times=5 randomly=yes
endwhile

for i 1 200
if that gte turns or who ne 5 and i gt thebear
forage times=4
endif
else
scout times=-2 who=me
attack times=turns
attack you=i
end
endfor

do
forage times=1
#that++
until turns lt 5

scout + - < > <= >) `hi` mysql_close();


stop";

echo "$s\n\n<hr>\n\n";

echo compile($s);

?>


</pre>
