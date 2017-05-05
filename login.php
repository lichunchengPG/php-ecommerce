<?php #Script 3.10 -login.php
//This page prcesses the login form submision.
//Upon successful login, the user is redirected.
//Two included files are necessary.

//Check if the form has been submitted:
if($_SERVER['REQUEST_METHOD'] == 'POST'){

   //for processing the login.
   require('../mysqli_connect.php'); 
   require('./includes/login_functions.inc.php');
   list($check, $data) = check_login($dbc, $_POST['email'], $_POST['password']);
   if($check){// OK!
      session_start();
      $_SESSION['user_id'] = $data['customer_id'];
	  $_SESSION['name'] = $data['email'];
	  redirect_user(); // redirect index.php
   }else{//unsuccessful
     //assign $data to $error for error reporting.
	 //in the login_page.inc.php file.
	 $errors =$data;
   }
   mysqli_close($dbc);
}// end of the main submit conditional.
   //creat the page:
include('includes/login_page.inc.php');
?>