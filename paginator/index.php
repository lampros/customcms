<?php
// Поиск по постам
// if (isset($_GET['type'])) {$type=$_GET['type'];}
include("/web/webpages/wmtools/site/scripts/config.php");
function explode_str_on_words($text)
{
    $search = array ("'ё'",
                     "'<script[^>]*?>.*?</script>'si",  // Вырезается javascript
                     "'<[\/\!]*?[^<>]*?>'si",           // Вырезаются html-тэги
                     "'([\r\n])[\s]+'",                 // Вырезается пустое пространство
                     "'&(quot|#34);'i",                 // Замещаются html-элементы
                     "'&(amp|#38);'i",
                     "'&(lt|#60);'i",
                     "'&(gt|#62);'i",
                     "'&(nbsp|#160);'i",
                     "'&(iexcl|#161);'i",
                     "'&(cent|#162);'i",
                     "'&(pound|#163);'i",
                     "'&(copy|#169);'i",
                     "'&#(\d+);'e");
    $replace = array ("е",
                      " ",
                      " ",
                      "\\1 ",
                      "\" ",
                      " ",
                      " ",
                      " ",
                      " ",
                      chr(161),
                      chr(162),
                      chr(163),
                      chr(169),
                      "chr(\\1)");
    $text = preg_replace ($search, $replace, $text);
    $del_symbols = array(",", ".", ";", ":", "\"", "#", "\$", "%", "^",
                         "!", "@", "`", "~", "*", "-", "=", "+", "\\",
                         "|", "/", ">", "<", "(", ")", "&", "?", "¹", "\t",
                         "\r", "\n", "{","}","[","]", "'", "“", "”", "•",
                         " как", " для", " что", " или", " это", " этих",
                         " всех", " вас", "они", " оно", " еще", "когда",
                         " где", " эта", "лишь", " уже", "вам", " нет",
                         "если", " надо", " все", " так", " его", " чем"," на", " не",
                         " при", "даже", " мне", " есть", " раз", "два",
                         "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"
                         );
    $text = str_replace($del_symbols, array(" "), $text);
    $text = ereg_replace("( +)", " ", $text);
  //   $this->origin_arr = explode(" ", trim($text));
    return $text;
}


if (isset($_GET['remove']))
{
if ($_COOKIE['login']=="admin")
{
$id=$_GET['id'];
include("/web/webpages/wmtools/site/scripts/config.php");
echo "Removed";
mysql_query("UPDATE w_posts set active='n' where id='$id' limit 1");
}
exit();
}

if (isset($_GET['search']))
{
echo "<h2>Search results:</h2><br>";
include("/web/webpages/wmtools/site/scripts/config.php");
$req=$_POST['req'];
$res = mysql_query("SELECT id, w_subj, w_post from w_posts where active='y' limit 100");
$number = mysql_num_rows($res); 
while ($row=mysql_fetch_array($res)) 
{
$post=$row['w_post'];
$post=htmlspecialchars_decode($post);
$post=explode_str_on_words($post);
$post=str_replace("$req","<b><font color=red>$req</font></b>",$post);

$subject=$row['w_subj'];
$id=$row['id'];
if (substr_count($post,"$req")>0)
{
echo "
<a href='?post=$id'><h3>$subject</h3></a><br>
<div style='font-size: 9px; line-height: 10px;'>$post</div><div style='border-bottom: 1px dotted #ccc; margin-bottom: 20px;'></div>";
}

}


exit();
}


if (isset($_GET['fp'])) {
$res = mysql_query("SELECT id, w_subj, w_post from w_posts where w_tip='fp' limit 1");
$number = mysql_num_rows($res); 
while ($row=mysql_fetch_array($res)) 
{
$post=htmlspecialchars_decode($row['w_post']);
$subject=$row['w_subj'];
}
echo "$post";
exit();
}


if ($post!="") {
$res = mysql_query("SELECT id, w_subj, w_post from w_posts where id='$post' limit 1");
$number = mysql_num_rows($res); 
while ($row=mysql_fetch_array($res)) 
{
$post=htmlspecialchars_decode($row['w_post']);
$subject=$row['w_subj'];
}
echo "<h2>$subject</h2><br><br>$post";
}
else
if ($_GET['type']) {
echo "<a href='?type=ns' style='text-decoration: none; color: black; font-size: 16px;' ><b>News</b></a> | <a href='?type=sli' style='text-decoration: none; color: black; font-size: 16px;'><b>Slider</b></a><br><br>";
$type=$_GET['type'];	
$res = mysql_query("SELECT id, w_subj, w_post, w_date from w_posts where w_tip='$type' and active='y'");
$number = mysql_num_rows($res); 
while ($row=mysql_fetch_array($res)) 
{
// $post=htmlspecialchars_decode($row['w_post']);
$subject=$row['w_subj'];
$id=$row['id'];
$date=$row['w_date'];
echo "
<div style='width: 600px; height: 40px; border-bottom: 1px solid #ccc; padding: 5px;'>";
if ($_COOKIE['login']=="admin") {echo "<font color=#FCC id='rm$id' style='cursor: pointer;' onclick=\"$.post('paginator/index.php?remove&id=$id', $('#lgform').serialize(), function(data) { $('#rm$id').html(data); }); return false;\">delete</font> ";}
echo "
<a href='?post=$id' style='text-decoration: none; color: black; font-size: 16px;'>$subject</a>
<br>
<font style='position: relative; left: 450px;font-size: 8px; color: gray;'>Posted on $date</font>
</div>
";
}
			       
			       
			       }
			  
else
{
?>
<div class='ribbon' style='width: 600px;' onclick="document.location.href='?type=sli';"><z>Slider</z> click here to open all posts</div>
<link rel="stylesheet" type="text/css" media="screen" href="paginator/css.css" />
<script type="text/javascript">
$(document).ready(function(){
	
	function showLoader(){
	
		$('.search-background').fadeIn(200);
	}
	
	function hideLoader(){
	
		$('.search-background').fadeOut(200);
	};
	
	$("#paging_button li").click(function(){
		
		showLoader();
		
		$("#paging_button li").css({'background-color' : ''});
		$(this).css({'background-color' : '#006699'});

<?php	echo "$('#contentpag').load('paginator/data.php?type=$type&page=' + this.id, hideLoader);"; ?>
		
		return false;
	});
	
	$("#1").css({'background-color' : '#006699'});
	showLoader();
<?php	echo "$('#contentpag').load('paginator/data.php?type=$type&page=1', hideLoader);"?>
	
});
</script>
</head>
<body>
<?php

$per_page = 3;
$sql = "select * from w_posts where w_tip='sli' and active='y'";
$rsd = mysql_query($sql);
$count = mysql_num_rows($rsd);
$pages = ceil($count/$per_page);?>

<div align="center">

	<div id="containerpag">
	
		<div class="search-background">
			<label><img src="paginator/loader.gif" alt="" /></label>
		</div>
	
		<div id="contentpag">
		&nbsp;
		</div>
		
	</div>
	
	<div id="paging_button" align="center">
		<ul>
		<?php
		//Show page links
		for($i=1; $i<=$pages; $i++)
		{
			echo '<li id="'.$i.'">'.$i.'</li>';
		}?>
		</ul>
	</div>
</div>
<?php
}
?>
