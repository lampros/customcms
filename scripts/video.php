<style>
#video12 {
  position: relative;
  margin-bottom: 110px;
  padding-bottom: 75%;
}
#video12 iframe {
  position: absolute;
  width: 100px;
  height: 100px;
}
#video12 div {
  position: absolute;
  bottom: -110px;
  width: 100%;
  height: 100px;
  padding: 0;
  overflow-x: auto;
  white-space: nowrap;
  text-align: center;
}
#video12 img {
  height: calc(100% - (5px + 1px)*2 - 10px);
  margin: 0 5px 0 0;
  padding: 5px;
  border: 1px solid #555;
  border-radius: 5px;
  opacity: .7;
}
#video12 img:hover {
  opacity: 1;
  cursor: pointer;
}
#video12 img:focus {
  opacity: .2;
}
</style>

<?php
echo "<div onclick=\"$('#content').load('gallery/full/index.html');\" class='ribbon' style='width: 260px;'><z>Video</z> click here to open videos</div>";
$res = mysql_query("SELECT url,name from video order by id desc limit 4");
$number = mysql_num_rows($res); 
$n=0;
while ($row=mysql_fetch_array($res)) 
{
$n++;
$url=$row['url'];
$name=$row['name'];
$c=explode("?",$url);
$c1=$c[1];
$c=explode("v=",$c1);
$v=$c[1];
echo "<div class='author' style='display: table-cell; position: relative; cursor: pointer;'><img src='http://img.youtube.com/vi/$v/1.jpg' width=100 height=100 onclick=\"$('#content').html('<iframe style=\'width: 600px; height: 400px;\' src=\'http://www.youtube.com/embed/$v?rel=0\' allowfullscreen=\'\' frameborder=\'0\'></iframe>');\">
<br>
<span style='background: url(images/60per.png); font-size: 7px; height: 10px; width: 94px; padding: 1px; position: absolute; bottom: 5px; left: 5px;color: #fff; padding-left: 5px;border-radius: 6px;'>$name</span>
</div>";
if ($n%2==0) {echo "<br>";}
}

?>
<script>
var IMG = document.querySelectorAll('#video12 img'),
    IFRAME = document.querySelector('#video12 iframe');
for (var i = 0; i < IMG.length; i++) {
  IMG[i].onclick = function() {
    var idIMG = this.src.replace(/http...img.youtube.com.vi.([\s\S]*?).1.jpg/g, '$1');
    IFRAME.src = 'http://www.youtube.com/embed/' + idIMG + '?rel=0&autoplay=1';
    if(this.dataset.end) IFRAME.src = IFRAME.src.replace(/([\s\S]*)/g, '$1&end=' + this.dataset.end);
    if(this.dataset.start) IFRAME.src = IFRAME.src.replace(/([\s\S]*)/g, '$1&start=' + this.dataset.start);
    this.style.backgroundColor='#555';
  }
}
</script>
