<?php
include("/web/webpages/wmtools/site/scripts/config.php");
if ($_COOKIE['login']!="admin") {echo "<script>alert('You have no access to this area');document.location.href='/site';</script>";}
if (isset($_GET['cssedit']))
{
include("items/cssedit.php");
}

if (isset($_GET['newpost']))
{
include("items/newpost.php");
}

if (isset($_GET['video']))
{
include("items/video.php");
exit();
}

if (isset($_GET['gallery']))
{
echo "
<div id='go'><iframe src='gallery/index.html' style='width: 600px; height: 500px; border: none;'></iframe></div>
";
exit();
}


if (isset($_GET['clrposts']))
{

if (isset($_GET['yes']))
{
$q="DELETE from w_posts where active='n'";
echo "$q executed...";
// mysql_query($q);
echo "
<div style='width: 100px; height: 20px; padding: 10px; border: 1px solid green; background-color: #CCFFCC; text-align: center;'>Deleted.</div><br><br>";
exit();
}

$n=0;$l=0;
$res = mysql_query("SELECT w_post from w_posts where active='n'");
$number = mysql_num_rows($res); 
while ($row=mysql_fetch_array($res)) 
{
$n++;
$post=$row['w_post'];
$l=$l+mb_strlen("$post")+1024;
}

$l=round($l/1024,1);
echo "When you delete some post, in a fact it just marked as deleted and doesn't displayed, but still exist on server.<br>Here you can really delete posts to free space or database.<br>Currently you have $n deleted posts, <b>$l kbytes could be freed</b><br><br>";
echo "<div style='width: 300px; height: 50px; padding: 10px; border: 1px solid red; background-color: #FFCCCC;'>Are you sure to delete hidden posts?<br><br>
<font style='cursor: pointer; font-weight: bold; color: red;' onclick=\"$.post('scripts/cms.php?clrposts&yes', $('#lgform').serialize(), function(data) { $('#content').html(data); }); return false;\">Yes</font> &nbsp; <font style='cursor: pointer; font-weight: bold; color: green;' onclick=\"document.location.href='?admin'\">No</font>
</div>";

exit();
}


if (isset($_GET['users']))
{

if (isset($_GET['delete']))
{
$id=$_GET['delete'];
mysql_query("DELETE FROM users where id='$id' limit 1");
echo "
<div style='width: 100px; height: 20px; padding: 10px; border: 1px solid green; background-color: #CCFFCC; text-align: center;'>User '$id' deleted</div><br><br>";
}
	
$res = mysql_query("SELECT id,login,name from users");
$number = mysql_num_rows($res); 
while ($row=mysql_fetch_array($res)) 
{
$id=$row['id'];
$login=$row['login'];
$name=$row['name'];
echo "<div style='border: 1px solid #ccc; margin-bottom: 10px;'>
<table width=500>
<tr>
<td width=100><b>$login</b></td>
<td width=100><b>$name</b></td>
<td align=right>
";
if ($login!="admin") { echo "<font onclick=\"$.post('scripts/cms.php?users&delete=$id', $('#lgform').serialize(), function(data) { $('#content').html(data); }); return false;\">Delete</font>";}
echo "</td>
</tr>
</table>
</div>";
}
exit();
}




echo 
"
<div id='page' class='container'>
<div id='content'>
<h1>Site's administration</h1>
</div>

	<div id='sidebar'>

<div class='menu-style1'>
<h2>Posts</h2>
<z onclick=\"$.post('scripts/cms.php?newpost', $('#lgform').serialize(), function(data) { $('#content').html(data); }); return false;\">Add new post</z><br>
<z onclick=\"$.post('scripts/cms.php?gallery', $('#lgform').serialize(), function(data) { $('#content').html(data); }); return false;\">Add new images</z><br>
<z onclick=\"$.post('scripts/cms.php?video', $('#lgform').serialize(), function(data) { $('#content').html(data); }); return false;\">Add new video</z><br>
<z onclick=\"$('#content').load('paginator/index.php?type=ns');\">Delete news</z><br>
<z onclick=\"$('#content').load('paginator/index.php?type=sli');\">Delete sliders</z><br>
</div>

<div class='menu-style1'>
<h2>Users control</h2>
<z onclick=\"$.post('scripts/cms.php?users', $('#lgform').serialize(), function(data) { $('#content').html(data); }); return false;\">Users</z><br>
</div><br>

<h2>Site settings</h2>
<z onclick=\"$.post('scripts/cms.php?cssedit', $('#lgform').serialize(), function(data) { $('#content').html(data); }); return false;\">Theme settings</z><br>
<z onclick=\"$.post('scripts/cms.php?clrposts', $('#lgform').serialize(), function(data) { $('#content').html(data); }); return false;\">Clear deleted posts</z><br>
	</div>
</div>
";
?>
