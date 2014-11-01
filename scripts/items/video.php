<?php
if (isset($_GET['check']))
{
$url=$_POST['url'];
$c=explode("?",$url);
$c1=$c[1];
$c=explode("v=",$c1);
$v=$c[1];	
$c=explode("&",$v);
$v=$c[0];	
echo "<img src='http://img.youtube.com/vi/$v/1.jpg'>";
exit();
}

if (isset($_GET['add']))
{
include("/web/webpages/wmtools/site/scripts/config.php");
$url=$_POST['url'];
$name=$_POST['name'];
$c=explode("?",$url);
$c1=$c[1];
$c=explode("v=",$c1);
$v=$c[1];	
if ($v!="")
{
mysql_query("INSERT INTO video(url,name)values('$url','$name')");
echo "
<script>$('#vurl').val('');</script>
<div style='width: 150px; height: 150px; padding: 10px; border: 1px solid green; background-color: #CCFFCC; text-align: center;'>$name sucessfully added<br><br>
<img src='http://img.youtube.com/vi/$v/1.jpg'>
</div>";}
else
{
echo "<div style='width: 300px; height: 50px; padding: 10px; border: 1px solid red; background-color: #FFCCCC;'>Something wrong with your URL.<br>It anyway should contain ?v=VIDEO_ID.</div>";
}
exit();
}


echo "<form id='vadd'>
<input type=text id='vurl' name=url placeholder='URL like https://youtube.com/?v=1A2b3C4d' style='width: 300px;' onchange=\"$.post('scripts/items/video.php?check', $('#vadd').serialize(), function(data) { $('#win2').html(data); }); return false;\"><br><br>
<input type=text name=name placeholder=name><br><br>
<input type=button class='iButton' value='Add this video' onclick=\"$.post('scripts/items/video.php?add', $('#vadd').serialize(), function(data) { $('#win2').html(data); }); return false;\">
</form>
<br><br>
<div id='win2'></div>
";
?>
