<?php #Script 3.6 -show_image.php

//This page retrieves and show an image.
//Flag avaliable.
$image = FALSE;
$name = (!empty($_GET['name']))?$_GET['name']:'book image';

//check for the image value in the URL.
if(isset($_GET['image']) && filter_var($_GET['image'], FILTER_VALIDATE_INT, array('min_range'=>1))){
	//Full image path.
	$image = './uploads/'.$_GET['image'];
	
	//check the image exists and is a file.
	if(!file_exists($image) || (!is_file($image))){
		$image = 'FALSE';
	}
}//end of $_GET['image'] IF.

//if there is a problem, use the default image:
if(!$image){
	$image = './image/unavaliable.jpg';
	$name = 'unavaliable.jpg';
}
//GET  the image information.
$info = getimagesize($image);//getimagesize() 函数用于获取图像大小及相关信息，成功返回一个数组，失败则返回 FALSE 

$fs = filesize($image);
//Send the content information:
header("Content-Type:{$info['mime']}\n");   //发送文件类型  \n 表示终止每个header的调用
header("Content-Disposition: inline; filename=\"$name\"\n");  //文件内嵌显示
header("Content-Length:$fs\n");  //文件大小

//send the file:
readfile($image);
	