<?php #Scirpt -3.11 -register.php
// This page allow customer to register an account.

$page_title = 'Register an account';
include('./includes/header_login.html');
include('./includes/login_functions.inc.php');
require('../mysqli_connect.php');
//check if the form  has been submitted:
$errors = array();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//check the $_POST['email']
	if(!empty($_POST['email'])){//add check email regulation
	   $e = trim($_POST['email']);
	}else{
		$errors[] = "Please enter a correct email format and not null<br>";
	}
	
	//check the $_POST['password']:
	if(!empty($_POST['password'])){ //add check email regulation
	   $p = trim($_POST['password']);
	}else{
		$errors[]="Please enter a correct password format and not null<br>";
	}
	//connect to the database and insert information
	if(!$errors){//ok!
	    $q = 'INSERT INTO customers(email,pass)VALUES(?,?)';
		$stmt = mysqli_prepare($dbc,$q);
		mysqli_stmt_bind_param($stmt,'ss',$e,$p);
		mysqli_stmt_execute($stmt);
		if(mysqli_stmt_affected_rows($stmt) == 1){
			$cid = mysqli_stmt_insert_id($stmt);
			echo "Your account has been added to the database<br>";
		    $_POST =array();
			//success to redirect index.php //重定向
			//creat  user session.
			session_start();
			$_SESSION['user_id'] = $cid;
			$_SESSION['name'] = $e;
		}else{
			$errors[] = "Sorry, is fail to add the account<br>";
		}
		mysqli_stmt_close($stmt);
		mysqli_close($dbc);
		
	}else{// print errors
	   foreach($errors as $msg){
	     echo $msg;
	   }
	}
}
//display register form:
?>

<h1>Register</h1>
<form action="register.php" method="post">
  <label for="email">Email_address</label>
  <input type="text" name="email" id="email" size="20" maxlength="60"
  value="<?php if(isset($_POST['email']))echo htmlspecialchars($_POST['email']);?>">  
  <label for="psd">Password</label>
  <input type="password" name="password" id="psd" size="20" maxlength="20">
  <input type="submit" name="submit" value="register"> 
</form>
<?php include('./includes/footer_login.html');?>  

