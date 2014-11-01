<?php
if (isset($_GET['appear']))
{
echo "<input type=text style='width: 90px; height: 30px;' id='srch' name=req><input type=button value='Go' class='iButton' style='height: 35px;' onclick=\"$.post('paginator/index.php?search', $('#srch').serialize(), function(data) { $('#content').html(data); }); return false;\">";
// echo "TEST";
exit();
}

?>
