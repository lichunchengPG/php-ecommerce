<!DOCTYPE html>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8" /> <!--http-equiv 文件头文件作用，文本/网页模式-->
   <title>Add an Author</title>
</head>
<body>
<?php #Script 0.1 - add_author.php
//This page allows the administrator to add an author.
include('../includes/login_functions.inc.php');
session_start();
if(!isset($_SESSION['name']) || $_SESSION['name'] != 'lichuncheng'){
	//not admin account
	//redirect to index.php
	redirect_user();
	
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){//Handle the form. $_SERVER:预定义服务器变量的一种
    //Validate the first name
	$fn=(!empty($_POST['first_name']))?trim($_POST['first_name']):NULL; #三目运算 trim():去除字符串左右两边空格
	//Check for last name
	if (!empty($_POST['last_name'])){
       $ln = trim($_POST['last_name']);
	   //add the author to the database:
	   require('../../mysqli_connect.php'); //引入文件 变成网页的一部分 include:放在流程控制中，读到include文件时，才将其读进来。
       $q = 'INSERT INTO authors(first_name,last_name)VALUES(?,?)'; #? 表示匹配任何字符
	   $stmt = mysqli_prepare($dbc, $q);   #stmt:statment , 预处理指令
	   mysqli_stmt_bind_param($stmt, 'ss', $fn, $ln);  //绑定参数
	   mysqli_stmt_execute($stmt); //执行，更新数据库
	   
	   //Check the results....
	   if(mysqli_stmt_affected_rows($stmt)==1){  #函数返回前一次mysql操作所影响的记录行数
	       echo '<p>The author has been added.</p>';
		   $_POST = array();
	   }else{//Error!
		   $error = 'The new author could not be added to the database';
	   }
	   //Close this prepared statement;
	   mysqli_stmt_close($stmt);
	   mysqli_close($dbc); //Close the database connection.
	}else{//No last name value.
	    $error = "Please enter the author's name!";
	}
}//end of the submission IF.
//Check for an error and print it:
if(isset($error)){
	echo '<h1>Error!</h1>
	<p style="font_weight:bold; color:#C00">'.$error.'Please try again.</p>';
}
?>
<!--//Displace the form... -->
<h1>Add an Author</h1>
<form action='add_author.php' method='post'>
  <fieldset><legend>Fill out the form to add an author:</legend>
  <!-- fieldset:对表单进行分组 legend：说明每组的内容描述 -->
  <p><b>First Name:</b><input type="text" name="first_name" size="10" maxlength='20' value="<?php 
  if(isset($_POST['first_name'])) echo $_POST['first_name']; ?>" > </p>
  
  <p><b>Last Name:</b><input type="text" name="last_name" size="10" maxlength='40' value="<?php 
  if(isset($_POST['last_name'])) echo $_POST['last_name']; ?>" > </p>
  
  </fieldset>
  <div align="center"><input type="submit" value="Submit" name="submit"></div>
</form>

</body>
</html>