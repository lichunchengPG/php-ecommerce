<?php #Script 1.6 -login_functions.inc.php
//This page defines two functions used by login/logout process.

/*This function determines an absolute URL and redirects the user there.
*This funciton take one argument:the page to be redirected to.
*This page defaults to the index.php.
*/

function redirect_user($page="index.php"){
	//start define the url
	$url = 'http://'.$_SERVER['HTTP_HOST']; //dirname($_SERVER['PHP_SELF']);//dirname 返回路径文件名，PHP_self ：域名之后的地址
	
	//remove any trailing slashed:
	$url = rtrim($url, '/\\');
	//add the $page
	$url.='/'.$page;
	
	//redirect the user:
	header("location:$url");
	exit(); //quit the script.
}//end of redirect_user() funciton.

/*This function validates the form date(the email address and password)
*If both precent, the database is queried.
*The funcition requires a database connection.
*The funcition return an array of information,including:
*- a TRUE/FALSE variable indicating success
*- an array of either errors or database result.
*/
function check_login($dbc, $email='', $password=''){
	$errors = array();//initialize error array.
	
	//validate the email address:
	if(empty($email)){
		$errors[] = 'Your forgot to enter your email address.';
	}else{
		$e = mysqli_real_escape_string($dbc, $email); //mysql_real_escape_string() 函数转义 SQL 语句中使用的字符串中的特殊字符,预防数据库攻击。
	}
	//validate the password.
	if(empty($password)){
		$errors[] = 'Your forgot to enter your password.';
	}else{
		$p = mysqli_real_escape_string($dbc, $password);
	}
	
	if(empty($errors)){ //IF everyting's OK.
        $q = "SELECT customer_id, email FROM customers WHERE email = '$e' AND pass= '$p' ";   //'$e','$p'
		$r = @mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) == 1){
			$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
			//return true and record
			return array(true, $row);
		}else{
		     $errors[] = 'The email address and password entered do not match those on file.';
		}
	}//end of empty($errors) IF.
	
	return array(false, $errors);
}//end of check_login() function.
			
		


	