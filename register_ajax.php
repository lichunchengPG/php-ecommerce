<?php #Script 3.12 -register_ajax.php
//This script is called via Ajax form regiter.php.
//This script expects to receive two values in the URL : an email address and password .
//This script returns a string indicating the results.


require('../mysqli_connect.php');
//check if the form  has been submitted:
$errors = array();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//check the $_POST['email'] and the $_POST['password']
	if(isset($_POST['email'],$_POST['password'])){
		//add check email regulation
		if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
			$e = trim($_POST['email']);
			$p = trim($_POST['password']);
			echo 'CORRECT';
	    }else{
		    $errors[] = "Please enter a correct email format.";
			echo 'INVALID_EMAIL';
		}
	}else{
		$errors[] = 'Email and password can not be null';
		echo 'INCOMPLETE';
	}

	//connect to the database and insert information
	if(!$errors){//ok!
	    $q = 'INSERT INTO customers(email,pass)VALUES(?,?)';
		$stmt = mysqli_prepare($dbc,$q);
		mysqli_stmt_bind_param($stmt,'ss',$e,$p);
		mysqli_stmt_execute($stmt);
		if(mysqli_stmt_affected_rows($stmt) == 1){
			$cid = mysqli_stmt_insert_id($stmt);
			//echo 'SUCCESS';
		    $_POST =array();
			//success to redirect index.php //重定向
			//creat  user session.
			session_start();
			$_SESSION['user_id'] = $cid;
			$_SESSION['name'] = $e;
		}else{
			//echo 'FAILE' ;
		}
		mysqli_stmt_close($stmt);
		mysqli_close($dbc);
		
	
	}
}
?>  