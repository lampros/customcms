<?php
include("/web/webpages/wmtools/site/scripts/config.php");
if (isset($_GET['savenew']))
{
$message=$_POST['message'];
$subj=$_POST['subject'];
$chr=$_POST['chr'];
$text = mysql_real_escape_string(htmlspecialchars($message));
if ($chr=="fp")
{
$q="UPDATE w_posts set w_post='$text' where w_tip='fp' limit 1";
}
else
{
$q="INSERT INTO w_posts (w_post, w_tip, w_subj) values ('$text','$chr','$subj')";
}
mysql_query($q);
echo "<h3>New post was added, please continue</h3>";
exit();
}
echo "
<script type='text/javascript' src='/jquery.cleditor.min.js'></script>
<link rel='stylesheet' type='text/css' href='/jquery.cleditor.css' />
<div class='tbl' style='text-align: left; width: 570px; background-color: #fff;' id='newmsg'>
<h2>Create new post</h2><br>
<form id='newmes'>
<input type='text' name='subject' id='subject' placeholder='Subject' style='width: 560px;'>
<br><br>
<select name=chr>
<option value=ns>News</option>
<option value=sli>Slider</option>
<option value=fp>First page</option>
</select>
<br><br>
<textarea name='message' style='width: 560px; height: 200px;' id='message'></textarea><br>
<input type=button class='iButton' value='Publish' onclick=\"$.post('scripts/items/newpost.php?savenew', $('#newmes').serialize(), function(data) { $('#content').html(data); }); return false;\">  
<!-- <input type=Button onclick=\"currentval = $('#message').val();$('#message').val(currentval + '<img src=\'https://news.pn/photo/946f961562598fc1a4ecdbcc6da335d2.i240x159x299.jpeg\'>').blur();\" class='iButton' value='Upload and attach image'> -->
</form>
</div>
    <script type='text/javascript'>
      $(document).ready(function() {
        $('#message').cleditor({width: 560, height: 250, controls: 'bold italic underline strikethrough font size | alignleft center alignright justify | undo redo | image link'});
  
      });
      
    </script>
   
</div>

";

exit();
?>
