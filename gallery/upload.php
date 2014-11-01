<?php
$login=$_COOKIE['login'];
if ($login=="") {$login="admin";}
include("/web/webpages/wmtools/site/scripts/config.php");
echo '<script type="text/javascript" src="http://wm-tools.net/site/gallery/js/jquery-1.8.2.min.js"></script>
<link href="../default.css" rel="stylesheet" type="text/css" media="all" />
';
if (isset($_GET['setnames']))
{
$c=$_POST['count'];
for($i=0; $i<$c;$i++){
	$name=$_POST["name$i"];
	$nameo=$_POST["nameo$i"];
//	echo "NAME: $name <br>";
mysql_query("UPDATE gallery SET photo_name='$name' where photo_name='$nameo' limit 1");
	}
echo "<script>$('#go', window.parent.document).html('<h3>Images were processed succesfully</h3>');</script>";

exit();
	}

function resize($ini_path, $dest_path, $params = array()) {
    $width = !empty($params['width']) ? $params['width'] : null;
    $height = !empty($params['height']) ? $params['height'] : null;
    $constraint = !empty($params['constraint']) ? $params['constraint'] : false;
    $rgb = !empty($params['rgb']) ?  $params['rgb'] : 0xFFFFFF;
    $quality = !empty($params['quality']) ?  $params['quality'] : 100;
    $aspect_ratio = isset($params['aspect_ratio']) ?  $params['aspect_ratio'] : true;
    $crop = isset($params['crop']) ?  $params['crop'] : true;
 
    if (!file_exists($ini_path)) return false;
 
 
    if (!is_dir($dir=dirname($dest_path))) mkdir($dir);
 
    $img_info = getimagesize($ini_path);
    if ($img_info === false) return false;
 
    $ini_p = $img_info[0]/$img_info[1];
    if ( $constraint ) {
        $con_p = $constraint['width']/$constraint['height'];
        $calc_p = $constraint['width']/$img_info[0];
 
        if ( $ini_p < $con_p ) {
            $height = $constraint['height'];
            $width = $height*$ini_p;
        } else {
            $width = $constraint['width'];
            $height = $img_info[1]*$calc_p;
        }
    } else {
        if ( !$width && $height ) {
            $width = ($height*$img_info[0])/$img_info[1];
        } else if ( !$height && $width ) {
            $height = ($width*$img_info[1])/$img_info[0];
        } else if ( !$height && !$width ) {
            $width = $img_info[0];
            $height = $img_info[1];
        }
    }
 
    preg_match('/\.([^\.]+)$/i',basename($dest_path), $match);
    $ext = $match[1];
    $output_format = ($ext == 'jpg') ? 'jpeg' : $ext;
 
    $format = strtolower(substr($img_info['mime'], strpos($img_info['mime'], '/')+1));
    $icfunc = "imagecreatefrom" . $format;
 
    $iresfunc = "image" . $output_format;
 
    if (!function_exists($icfunc)) return false;
 
    $dst_x = $dst_y = 0;
    $src_x = $src_y = 0;
    $res_p = $width/$height;
    if ( $crop && !$constraint ) {
        $dst_w  = $width;
        $dst_h = $height;
        if ( $ini_p > $res_p ) {
            $src_h = $img_info[1];
            $src_w = $img_info[1]*$res_p;
            $src_x = ($img_info[0] >= $src_w) ? floor(($img_info[0] - $src_w) / 2) : $src_w;
        } else {
            $src_w = $img_info[0];
            $src_h = $img_info[0]/$res_p;
            $src_y    = ($img_info[1] >= $src_h) ? floor(($img_info[1] - $src_h) / 2) : $src_h;
        }
    } else {
        if ( $ini_p > $res_p ) {
            $dst_w = $width;
            $dst_h = $aspect_ratio ? floor($dst_w/$img_info[0]*$img_info[1]) : $height;
            $dst_y = $aspect_ratio ? floor(($height-$dst_h)/2) : 0;
        } else {
            $dst_h = $height;
            $dst_w = $aspect_ratio ? floor($dst_h/$img_info[1]*$img_info[0]) : $width;
            $dst_x = $aspect_ratio ? floor(($width-$dst_w)/2) : 0;
        }
        $src_w = $img_info[0];
        $src_h = $img_info[1];
    }
 
    $isrc = $icfunc($ini_path);
    $idest = imagecreatetruecolor($width, $height);
    if ( ($format == 'png' || $format == 'gif') && $output_format == $format ) {
        imagealphablending($idest, false);
        imagesavealpha($idest,true);
        imagefill($idest, 0, 0, IMG_COLOR_TRANSPARENT);
        imagealphablending($isrc, true);
        $quality = 0;
    } else {
        imagefill($idest, 0, 0, $rgb);
    }
    imagecopyresampled($idest, $isrc, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
    $res = $iresfunc($idest, $dest_path, $quality);
 
    imagedestroy($isrc);
    imagedestroy($idest);
 
    return $res;
}

	include('Class.upload.php');
	
	//Basic
	$upload = new uploadClass();
	$obj = $upload->upload($file = 'file');
	
	#return
	$status = $upload->status;
	echo "<div id='gal'>";
	if($status == true){
		//success
		$data = $upload->data;
		echo '<h2>First Input</h2>';
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}else{
		//error
		$data = $upload->data;
	//	echo implode('<br>',$data);
	}
	
	/* ================================================================ */
	
	//Custom Options & filerIndex
	$upload = new uploadClass();
	
	$upload->fields = 'name,extension,type,size,tmpName,uploadDir,newFile,replaced,date,perms'; //custom return fields
	$obj2 = $upload->upload($file = 'file2', 
	                        $options = array(
								'limit'=>10, //Limit of maximum upload files
								'maxSize'=>50, //Max size of each file
								'title'=>array('auto',12,'file'), //new title for file
								'uploadDir'=>"uploads/", //Upload directory
								'types'=>"Image, Audio, Video", //Type of uploads, just for alert
								'extensions'=>array("jpg","jpeg","png","gif","mp3","wmv","mp4",'txt','zip'), //Allowed Extensions, if null than any extension.
								'removeFiles'=>true, //Option for removing files
								'required'=>true, //If files are required
								'onUpload'=>'onUploadCallback',
								'onCheck'=>'onCheckCallback',
							   ), 
							 $filerIndex = 1
							);
															   
	#return
	$status = $upload->status;
	if($status == true){
		//success
		$data = $upload->data;
		echo '<h2>Images were uploaded.</h2>';
		echo '<pre>But you can rename it<br><br>';
		$files=$data['files'];
		$z=explode("|",$files);
echo "<form action='gallery.php' id='nms'><table border=0>";			
		for($i=0; $i<count($z);$i++){
        $f=explode("/",$z[$i]);
        $fl=$f[1];
$params = array('width' => 50,'height' => 50,'aspect_ratio' => true,'crop' => true);resize("uploads/$fl", "uploads/50-$fl", $params);
$params = array('width' => 100,'height' => 100,'aspect_ratio' => true,'crop' => true);resize("uploads/$fl", "uploads/100-$fl", $params);
$params = array('width' => 600,'height' => 400,'aspect_ratio' => true,'crop' => true);resize("uploads/$fl", "uploads/$fl", $params);
mysql_query("INSERT INTO gallery(owner,full_image,image50,image100,photo_name) VALUES('$login','uploads/$fl','uploads/50-$fl','uploads/100-$fl','$fl')");
echo "
<tr style='height: 50px' height=50>
<td><img src='uploads/50-$fl' style='box-shadow: 0 0 5px;'></td>
<td><input type='text' name='name$i' value='$fl'>
<input type='hidden' name='nameo$i' value='$fl'>
</td>
</tr>";
		}
echo "</table>
<input type=hidden name=count value='$i'>
<input type=button value='Save changes' onclick=\"$.post('upload.php?setnames', $('#nms').serialize(), function(data) { $('#gal').html(data); }); return false;\" class='iButton'>
</form>";
		
		echo '</pre>';
	}else{
		//error
		// echo '<h1>Error of status!!!</h1>';
		$data = $upload->data;
		echo implode('<br>',$data);
	}
	
	/* CALLBACKS */
	
	function onUploadCallback($data, $file){
        /*On Upload callback*/
		$array = array();
		
		if($data['type'][0] == 'image' && @getimagesize($data['newFile'])){
			$imgInfo = @getimagesize($data['newFile']);
			
			$array['image'] = array('width'=>$imgInfo[0],'height'=>$imgInfo[1]);
		}
		
		return $array;
	}
	
	function onCheckCallback($file){
		/*On Check callback*/
		
		$array = array();
		
		for($i=0; $i<count($file['name']);$i++){
			//$array[] = 'Error';
		}
		
		return $array;
	}
echo "</div>";
?>
