<?php
include("/web/webpages/wmtools/site/scripts/config.php");

if (isset($_GET['gallery']))
{
exit();	
}
?>
<style>
#slider-wrap{ /* Оболочка слайдера и кнопок */
	width:250px; 
	}
#slider{ /* Оболочка слайдера */
	width:250px;
	height:280px;
	overflow: hidden;
	position:relative;}
.slide{ /* Слайд */
	width:100%;
	height:100%;
	}
.sli-links{ /* Кнопки смены слайдов */
	margin-top:10px;
	text-align:center;}
.sli-links .control-slide{
	margin:2px;
	display:inline-block;
	width:16px;
	height:16px;
	overflow:hidden;
	text-indent:-9999px;
	background:url(images/radioBg.png) center bottom no-repeat;}
.sli-links .control-slide:hover{
	cursor:pointer;
	background-position:center center;}
.sli-links .control-slide.active{
	background-position:center top;}
#prewbutton, #nextbutton{ /* Ссылка "Следующий" и "Педыдущий" */
	display:block;
	width:15px;
	height:100%;
	position:absolute;
	top:7px;
	overflow:hidden;
	text-indent:-999px;
	background:url(images/arrowBg.png) left center no-repeat;
	opacity:0.8;
	z-index:3;
	outline:none !important;}
#prewbutton{left:10px;}
#nextbutton{
	right:10px;
	background:url(images/arrowBg.png) right center no-repeat;}
#prewbutton:hover, #nextbutton:hover{
	opacity:1;}
</style>
<script>
(function ($) {
var hwSlideSpeed = 700;
var hwTimeOut = 3000;
var hwNeedLinks = true;
$(document).ready(function(e) {
	$('.slide').css(
		{"position" : "absolute",
		 "top":'0', "left": '0'}).hide().eq(0).show();
	var slideNum = 0;
	var slideTime;
	slideCount = $("#slider .slide").size();
	var animSlide = function(arrow){
		clearTimeout(slideTime);
		$('.slide').eq(slideNum).fadeOut(hwSlideSpeed);
		if(arrow == "next"){
			if(slideNum == (slideCount-1)){slideNum=0;}
			else{slideNum++}
			}
		else if(arrow == "prew")
		{
			if(slideNum == 0){slideNum=slideCount-1;}
			else{slideNum-=1}
		}
		else{
			slideNum = arrow;
			}
		$('.slide').eq(slideNum).fadeIn(hwSlideSpeed, rotator);
		$(".control-slide.active").removeClass("active");
		$('.control-slide').eq(slideNum).addClass('active');
		}
if(hwNeedLinks){
var $linkArrow = $('<a id="prewbutton" href="#">&lt;</a><a id="nextbutton" href="#">&gt;</a>')
	.prependTo('#slider');		
	$('#nextbutton').click(function(){
		animSlide("next");
		return false;
		})
	$('#prewbutton').click(function(){
		animSlide("prew");
		return false;
		})
}
	var $adderSpan = '';
	$('.slide').each(function(index) {
			$adderSpan += '<span class = "control-slide">' + index + '</span>';
		});
	$('<div class ="sli-links">' + $adderSpan +'</div>').appendTo('#slider-wrap');
	$(".control-slide:first").addClass("active");
	$('.control-slide').click(function(){
	var goToNum = parseFloat($(this).text());
	animSlide(goToNum);
	});
	var pause = false;
	var rotator = function(){
			if(!pause){slideTime = setTimeout(function(){animSlide('next')}, hwTimeOut);}
			}
	$('#slider-wrap').hover(	
		function(){clearTimeout(slideTime); pause = true;},
		function(){pause = false; rotator();
		});
	rotator();
});
})(jQuery);
</script>
<?php
echo "<div onclick=\"$('#content').load('gallery/full/index.html');\" class='ribbon' style='width: 260px;'><z>Gallery</z> click here to open gallery</div>";
echo "<div id='slider-wrap'><div id='slider'>";
$res = mysql_query("SELECT photo_name, image100 from gallery order by RAND() limit 15");
$number = mysql_num_rows($res); 
$n=0;
while ($row=mysql_fetch_array($res)) 
{
$n++;
$img[$n]['image']=$row['image100'];
$img[$n]['name']=$row['photo_name'];
}
$z=0;
for($i=0; $i<$n;$i++){
$z++;
if ($z==1) {echo "<div class='slide'>";}
$image=$img[$i]['image'];
$name=$img[$i]['name'];
if ($image!="") {echo "<div class='author' style='display: table-cell; position: relative;'><img src='/site/gallery/$image'><br>
<span style='background: url(images/60per.png); font-size: 7px; height: 10px; width: 94px; padding: 1px; position: absolute; bottom: 5px; left: 5px;color: #fff; padding-left: 5px;border-radius: 6px;'>$name</span>

</div>";}
if ($z==3) {echo "<br>";}
if ($z=="5") {echo "</div>";$z=0;}
}

echo "
</div>
</div>
";
?>
