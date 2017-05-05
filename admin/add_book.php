<!DOCTYPE html>
<html>
<head>
  <meta http-equiv='content-type' content='text/html ; charset=utf-8'>
  <title>Add a book</title>
</head>
<body>
<?php #Script 0.2 -add_book.php
//This page allows the administrator to add a book.
require('../../mysqli_connect.php');
include('../includes/login_functions.inc.php');
session_start();
if(!isset($_SESSION['name']) || $_SESSION['name'] != 'lichuncheng'){
	//not admin account
	//redirect to index.php
	redirect_user();

}elseif($_SERVER['REQUEST_METHOD'] == 'POST'){//Handle the form.
//validate the incoming data....
  $errors=array();
  //check for a book name;
  if(!empty($_POST['book_name'])){
	  $bn = trim($_POST['book_name']);
  }else{
	  $errors[] = "Please enter tne book's name";   //添加数组元素
  }
  //check for an image:
  if(is_uploaded_file($_FILES['image']['tmp_name'])){      //检测是否上传了文件
	  //create a temporary file name:
	  $temp = '../uploads/'.md5($_FILES['image']['name']);
	  //move the file over:
	  if(move_uploaded_file($_FILES['image']['tmp_name'],$temp)){    //移动文件位置
		  echo '<p>The file had been uploaded!</p>';
		  //set the $i variable to the image's name;
		  $i = $_FILES['image']['name'];
	  }else{//Couldn't move the file over
	    $errors[]='The file could not be moved.';
		$temp = $_FILES['image']['tmp_name'];
	  }
  }else{//No uploaded file.
      $errors[]='No file was uploaded.';
	  $temp = NULL;
  }
  //Check for a price:
  if(is_numeric($_POST['price'])&& ($_POST['price']>0)){
    $p = (float)$_POST['price'];
  }else{
    $errors[]="Please enter the book's price.";
  }
  //Check for the publisher(not required)
  $pb = (!empty($_POST['publisher']))?trim($_POST['publisher']):NULL;

  //Check for the description (not requried):
  $d = (!empty($_POST['description']))?trim($_POST['description']):NULL;
  
  //Validate the author..
  if(isset($_POST['author']) && filter_var($_POST['author'], FILTER_VALIDATE_INT, array('min_range'=>1))){
	  $a = $_POST['author'];    //filter_var() 函数通过指定的过滤器过滤变量,规定要使用的过滤器的 ID,规定包含标志/选项的数组
  }else{//No book selected.
    $errors[]= "Please select the book's author";
  }
  if(empty($errors)){//if everyting's OK!
    //add the book to the database
	$q = 'INSERT INTO books(author_id, book_name, price, publisher, description, image_name)VALUES(?,?,?,?,?,?)';
    $stmt = mysqli_prepare($dbc, $q);
	mysqli_stmt_bind_param($stmt,'isdsss',$a,$bn,$p,$pb,$d,$i);
    mysqli_stmt_execute($stmt);
	//check the results:
	if(mysqli_stmt_affected_rows($stmt) == 1){
		//Print the massage:
		echo "<p>The print has been added.</p>";
		//rename the image;
		$id = mysqli_stmt_insert_id($stmt); //Get the ID generated from the previous INSERT operation.
		rename($temp,"../uploads/$id"); //重命名
		//clear the $_POST.
		$_POST = array();
	}else{//ERROR!
	   echo '<p style="font-weight:bold, color：#C00”>Your submission could not be processed due to a system error.</p>';
	}
	mysqli_stmt_close($stmt);
  }//end of $errors IF.
  //delete the uploaded file if it still exists:
  if(isset($temp) && file_exists($temp) && is_file($temp)){ //检查文件是否存在
	  unlink($temp);    //删除文件
  }
}//end of the submission IF.
//check for any errors and print them:
if (!empty($errors) && is_array($errors)){
	echo '<h1>Error!</h1>
	<p style="font-weight:bold; color:#C00">The following error(s) occurred:<br>';
	foreach($errors as $msg){   //循环语句 foreach (array_expression as $value)
		echo "- $msg<br>\n";
	}
	echo 'Please reselect the print image and try again.</p>';
}
?>
<!-- dislpace the form-->
<h1> Add a book </h1>
<form enctype="multipart/form-data" action="add_book.php" method="post">  <!-- enctype 编码类型，上传文件使用 “multipart...” -->
  <input type="hidden" name="MAX_FILE_SIZE" value="524288">
  <fieldset><legend>Fill out the form to add a print to the catalog:</legend>
  <p><b>Book Name:</b><input type='text' name="book_name" size="30" maxlength="60" value="<?php if(isset($_POST['book_name']))echo htmlspecialchars($_POST['book_name']);?>"></p>
  <p><b>Image:</b><input type='file' name='image'></p>
  <p><b>Author:<b><select name="author"><option>Select One</option>
  <?php //retrieve all the authors and add to the pull-down menu.
  $q = "SELECT author_id, CONCAT_WS(' ',first_name,last_name)FROM authors ORDER BY last_name,first_name ASC";  //concat_ws：连接两个数组
  $r = mysqli_query($dbc, $q);
  if(mysqli_num_rows($r)>0){                            //mysql_num_rows() 函数返回结果集中行的数目
	  while($row = mysqli_fetch_array($r, MYSQLI_NUM)){ //mysql_fetch_array() 函数从结果集中取得一行作为关联数组，或数字数组，或二者兼有
		  echo "<option value=\"$row[0]\"";
		  //check for the stickyness:
		  if(isset($_POST['existing'])&&($_POST['existing']==$row[0]))echo 'select="selected"';
		  echo ">$row[1]</option>\n";
	  }
  }else{
	  echo '<option>Please add a new author firsr.</option>';
  }
  mysqli_close($dbc);//close the database connection.
  ?>
  </select></p>
  
  <p><b>Price:</b><input type='text' name="price" size="10" maxlength="10" value="
  <?php if(isset($_POST['price']))echo $_POST['price'];?>"></p>
   
  <p><b>Publisher:</b><input type='text' name="publisher" size="20" maxlength="30" value="
  <?php if(isset($_POST['publisher']))echo htmlspecialchars($_POST['publisher']);?>"></p>
   
  <p><b>Description:</b><textarea  name="description" cols="40" rows="5"> 
  <?php if(isset($_POST['description']))echo $_POST['description'];?></textarea>(option)</p>

  </fieldset>
  <div align="center"><input type='submit' name='submit' value="Submit"></div>
</form>
</body>
</html>  
  
  

	
	
	
</body>
<html>