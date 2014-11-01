<?php
include("/web/webpages/wmtools/site/scripts/config.php");
if (isset($_GET['logout']))
{
setcookie("login","",time()+3600, "/site/", "wm-tools.net");
setcookie("name","",time()+3600, "/site/", "wm-tools.net");
echo "<script>document.location.href=''</script>";
}

if (isset($_GET['check']))
{
$login=$_POST['login'];
$pass=$_POST['pass'];
$res = mysql_query("SELECT id,name from users where login='$login' and pass='$pass' limit 1");
$number = mysql_num_rows($res); 
while ($row=mysql_fetch_array($res)) 
{
$id=$row['id'];
$name=$row['name'];
}

if ($id=="") {
echo "<div style='width: 300px; height: 50px; padding: 10px; border: 1px solid red; background-color: #FFCCCC;'>User not found, please re-check!</div>";	
exit();
}
else
{
// Изменить путь для реального сайта
setcookie("login","$login",time()+3600, "/site/", "wm-tools.net");
setcookie("name","$name",time()+3600, "/site/", "wm-tools.net");
echo "<script>document.location.href='';</script>";	
}
exit();	
}	


if (isset($_GET['drawform']))
{
echo "
<div class='loginform' id='lgf'>
<center><font size=2><h2>Please authorize</h2></font></center><br><br>
<form id='lgform'>
<input type=text placeholder='login' name=login><br><br>
<input type=password placeholder='password' name=pass><br><br>
<input type=button value='Login' class='iButton' onclick=\"$.post('scripts/auth.php?check', $('#lgform').serialize(), function(data) { $('#content').html(data); }); return false;\"><br><br>
</form>
<font style='position: relative;margin-left: 120px; cursor: pointer;' onclick=\"$('#win1').html('');$.post('scripts/auth.php?new', $('#lgform').serialize(), function(data) { $('#content').html(data); }); return false;\"><b>Register new user</b></font>
</div>
<script>$('#lgf').fadeIn(500);</script>
";	
exit();	
}

if (isset($_GET['checklogin']))
{
$newuser=$_POST['login'];
$res = mysql_query("SELECT id from users where login='$newuser' limit 1");
$number = mysql_num_rows($res); 
while ($row=mysql_fetch_array($res)) 
{
$id=$row['id'];
}
if ($id!="")
{
echo "<div style='width: 300px; height: 50px; padding: 10px; border: 1px solid red; background-color: #FFCCCC;'>'$newuser' username already used, please change to something else.</div>
<script>$('#lgn').val('');</script>";	
}
else
{
echo "<div style='width: 300px; height: 50px; padding: 10px; border: 1px solid green; background-color: #CCFFCC;'>'$newuser' username is free, you can continue.</div>";	
}
exit();
}


if (isset($_GET['create']))
{
$user=$_POST['login'];
$pass=$_POST['pass'];
$name=$_POST['name'];
if (($user=="")or($pass=="")or($name==""))
{
echo "<div style='width: 300px; height: 50px; padding: 10px; border: 1px solid red; background-color: #FFCCCC;'>Login, name, or password cannot be empty.</div>";
exit();	
}

$res = mysql_query("SELECT id from users where login='$newuser' limit 1");
$number = mysql_num_rows($res); 
while ($row=mysql_fetch_array($res)) 
{
$id=$row['id'];
}
if ($id!="")
{
echo "<div style='width: 300px; height: 50px; padding: 10px; border: 1px solid red; background-color: #FFCCCC;'>'$newuser' username already used, please change to something else.</div>
<script>$('#lgn').val('');</script>";	
}
else
{
mysql_query("INSERT INTO users (login,pass,name) values ('$user','$pass','$name')");
echo "
<script>$('#content').html('<div style=\'width: 300px; height: 50px; padding: 10px; border: 1px solid green; background-color: #CCFFCC;\'>\'$user\' created succesfully, please <a href=\'\'>continue</a>.</div>');</script>";	
}
exit();
}



if (isset($_GET['new']))
{
echo "<h2>New user registration</h2><br>";
echo "
<script>$('#sidebar').css('opacity','0.2');</script>
<form id='newuser'>
<input id='lgn' type='text' name=login placeholder='Please enter your login here' style='width: 250px;' onchange=\"$.post('scripts/auth.php?checklogin', $('#lgn').serialize(), function(data) { $('#win2').html(data); }); return false;\"><br><br>
<input type='password' name=pass placeholder='Please enter your password here' style='width: 250px;'><br><br>
<input type='text' name=name placeholder='Please enter your name here' style='width: 250px;'><br><br>
<input type='button' class='iButton' value='Register!' onclick=\"$.post('scripts/auth.php?create', $('#newuser').serialize(), function(data) { $('#win2').html(data); }); return false;\"><br><br><div id='win2'></div>
</form>
";

exit();
}
?>
