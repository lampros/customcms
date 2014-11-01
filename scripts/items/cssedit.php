<?php
if (isset($_GET['save']))
{
	echo "
	<style>
	#wrap {  
    width: 500px;
    height: 220px;
    padding: 0;
    overflow: hidden;
    
}
#frame {  
    -ms-zoom: 0.4;
    -ms-transform-origin: 0 0;
    -moz-transform: scale(0.4);
    -moz-transform-origin: 0px 50px;
    -o-transform: scale(0.4);
    -o-transform-origin: 0px 50px;
    -webkit-transform: scale(0.4);
    -webkit-transform-origin: 0 0;
    border: 1px solid white;
}
#frame {
    width: 1300px;
    height: 530px;
    overflow: hidden;
}
	</style>
	";
$css=$_POST['css'];
if (isset($_GET['restore']))
{
$css=file_get_contents("../original.css");
}
file_put_contents("../default.css","$css");
echo "
<center><h3>Please check CSS changes into frame</h3></center>
<div id='wrap'>
    <iframe id='frame' src='/site/'></iframe>
</div>
<br>
<font color=red size=2><b>CSS file is saved, and you will see changes after refresh. Please restore default CSS file NOW, if something wrong!</b></font>
<br><br>
";
}
	
$c=file_get_contents("../default.css");
echo "
<center><h3>Please edit here your custom CSS file</h3></center>
<textarea name=css placeholder='CSS3 code' style='width: 500px; height: 300px; font-size: 8px;' id='css'>$c</textarea><br>
<input type=button class='iButton' value='Save changes' onclick=\"$.post('scripts/cms.php?cssedit&save', $('#css').serialize(), function(data) { $('#content').html(data); }); return false;\">
<input type=button class='iButton' value='Restore default CSS file' onclick=\"$.post('scripts/cms.php?cssedit&save&restore', $('#css').serialize(), function(data) { $('#content').html(data); }); return false;\">
";
exit();
?>
