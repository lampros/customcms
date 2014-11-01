<?php
// include("/web/webpages/wmtools/site/scripts/config.php");
$month=array(1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"Jun",7=>"Jul",8=>"Aug",9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec");
$sql = "select * from w_posts where w_tip='ns' and active='y' order by id desc limit 5";
$rsd = mysql_query($sql);

while ($rows = mysql_fetch_assoc($rsd))
{
echo "	
	<div style='overflow: hidden; width: 290px; height: 50px;'>
			<div class='label' >";
		    $x=htmlspecialchars_decode($rows['w_subj']);
		    if ($x=="") {$x="Empty subject";}
			$date=$rows['w_date'];
            $id=$rows['id'];
            $z=explode(" ",$date);
            $d=explode("-",$z[0]);
            $m=trim($d[1]);
            $n=$m+1;$m=$n-1;
            $dayname=$month[$m];
            echo "
<table border=0 width=290>
<tr>
<td width=20>
<div style='background-color: #006699;padding: 2px;font-size: 8px; width: 33px; height: 33px; line-height: 10px; text-align: center; color: #ccc; opacity: 0.5;'>
<font style='color: white; font-size: 13px; top: 3px; position: relative;'><b>$dayname</b></font><br><br>
$d[2], $d[0]
</div>
</td>
<td width=260px;>
<a href='?post=$id' style='text-decoration: none;'><b style='font-size: 13px; color: #224466;'>$x</b></a>
</td>
</tr>
</table>            
";

echo "      </div>
	
	</div>
<div style='width: 260px; border-bottom: 1px dotted #ccc;height: 1px;'></div>
";
}
?>
