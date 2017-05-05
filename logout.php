<?php #Script 3.11 -logout.php

//set title and include header
$page_title = 'Logout';
require('includes/login_functions.inc.php');
include('includes/header_login.html');
session_start();
if(isset($_SESSION['user_id'])){
	$_SESSION = array();
	session_destroy();
	
	echo "<h1>Logout</h1>
	  <p>You has been  logged out</p>";
}else{//did not login then redirect index.php
    redirect_user();
}
include('includes/footer_login.html');