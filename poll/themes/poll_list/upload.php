<?php
$file = $_POST['file'];
$img = $_POST['image'];
$site = $_POST['site'];


$dir = "../../files/".$site;


$image = preg_replace('/^data:image\/png;base64,/', '', $img);

$im = imagecreatefromstring(base64_decode($image));

imagealphablending($im, false);
imagesavealpha($im, true);

if(!is_dir($dir)){
	mkdir($dir, 0777);
	chmod($dir, 0707);
}
if(!is_dir($dir."/sign_file")){
	mkdir($dir."/sign_file", 0777);
	chmod($dir."/sign_file", 0707);
}


$data = $dir."/sign_file/".$file;

header("Content-Type: image/png");
imagepng($im, $data);

?>