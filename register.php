 <!--#Scirpt -3.11 -register.php
// This page allow customer to register an account.-->


<html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
  <title>Register</title>
  <link rel="stylesheet" href="css/style.css" type="text/css" media="screes"/>
  <script type="text/javascript" src="js/jquery-3.2.0.min.js" charset="utf-8"></script>
  <script type="text/javascript" src="js/register.js" charset="utf-8"></script>
</head>  
<body>  
<h1>Register</h1>
<p id="results"></p>
<form action="register.php" method="post" id="register">
  <p id="emailP">Email_address:<input type="text" name="email" id="email" size="20" maxlength="60"
  value="<?php if(isset($_POST['email']))echo htmlspecialchars($_POST['email']);?>"/>
  <span class="errorMessage" id="emailError">Please enter your email address!</span></p>
  <p id="passwordP">Password:<input type="password" name="password" id="password" size="20" maxlength="60"/>
  <span class="errorMessage" id="passwordError">Please enter your password!</span></p>
  <input type="submit" name="submit" value="register"> 
</form>
<body>
</html> 