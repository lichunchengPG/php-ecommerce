<?php #Script 1.5 -login_page.inc.php
//This page prints any errors associated with logging in 
//and it create the entire login page, including the form.

//Include the header；
$page_title = 'Login';
include('./includes/header_login.html');

//Print any error messages, if they exist:
if(isset($errors) && !empty($errors)){
	echo '<h1>Error</h1>
	<p class ="error">The following error(s) ocurred:<br>';	
	foreach($errors as $msg){
		echo "- $msg<br>\n";
	}
    echo '</p><p>Please try again.</p>';
}
//Display the form 
?>
<h1>Login</h1>
<form action="login.php" method="post">
  <label for="email">Email_address</label>
  <input type="text" name="email" id="email" size="20" maxlength="60"
  value="<?php if(isset($_POST['email']))echo htmlspecialchars($_POST['email']);?>">  
  <label for="psd">Password</label>
  <input type="password" name="password" id="psd" size="20" maxlength="20">
  <input type="submit" name="submit" value="Login"> 
</form>
<?php include('./includes/footer_login.html');?>  