<?php
include("/web/webpages/wmtools/site/scripts/config.php");
$type=$_GET['type'];
function breakword ($txt,$len,$delim='\s;,.!?:#') {
    $txt = preg_replace_callback ("#(</?[a-z]+(?:>|\s[^>]*>)|[^<]+)#mi"
                                  ,create_function('$a'
                                                  ,'static $len = '.$len.';'
                                                  .'$len1 = $len-1;'
                                                  .'$delim = \''.str_replace("#","\\#",$delim).'\';'
                                                  .'if ("<" == $a[0]{0}) return $a[0];'
                                                  .'if ($len<=0) return "";'
                                                  .'$res = preg_split("#(.{0,$len1}+(?=[$delim]))|(.{0,$len}[^$delim]*)#ms",$a[0],2,PREG_SPLIT_DELIM_CAPTURE);'
                                                  .'if ($res[1]) { $len -= strlen($res[1])+1; $res = $res[1];}'
                                                  .'else         { $len -= strlen($res[2]); $res = $res[2];}'
                                                  .'$res = rtrim($res);/*preg_replace("#[$delim]+$#m","",$res);*/'
                                                  .'return $res;')
                                  ,$txt);
     while (preg_match("#<([a-z]+)[^>]*>\s*</\\1>#mi",$txt)) {
         $txt = preg_replace("#<([a-z]+)[^>]*>\s*</\\1>#mi","",$txt);
     }
     return $txt;
}

$per_page = 3;
$sqlc = "show w_post from w_posts where w_tip='sli' and active='y'";
$rsdc = mysql_query($sqlc);
$cols = mysql_num_rows($rsdc);
$page = $_REQUEST['page'];

$start = ($page-1)*2;
$sql = "select * from w_posts where w_tip='$type' and active='y' order by id limit $start,3";
$rsd = mysql_query($sql);
?>

<?php
while ($rows = mysql_fetch_assoc($rsd))
{?>
	<div class="shopp" style='overflow: hidden;'>
			<div class="label" style='color: #000' >
			<?php 
			$x=htmlspecialchars_decode($rows['w_subj']);
			$date=$rows['w_date'];
            $id=$rows['id'];
			echo "<b style='font-size: 13px;'>$x</b> | <font style='font-size: 7px; color: gray;'>$date</font><a href='?post=$id' class='link-style' style='position: absolute; left: 480px; top: 0px;'>Read More</a><br>";
			$z=htmlspecialchars_decode($rows['w_post']);
			$z=breakword($z,300);
			echo $z;
			
			?></div>
	
	</div>
<div style='width: 540px; border-bottom: 1px dotted #ccc;height: 1px;'></div>
<?php
}?>

<script type="text/javascript">
$(document).ready(function(){
	
	var Timer  = '';
	var selecter = 0;
	var Main =0;
	
	bring(selecter);
	
});

function bring ( selecter )
{	
	$('div.shopp:eq(' + selecter + ')').stop().animate({
		opacity  : '1.0',
		height: '80px'
		
	},300,function(){
		
		if(selecter < 6)
		{
			clearTimeout(Timer); 
		}
	});
	
	selecter++;
	var Func = function(){ bring(selecter); };
	Timer = setTimeout(Func, 20);
}

</script>
