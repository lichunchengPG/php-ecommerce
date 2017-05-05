<?php #Script 3.7 -add_cart.php
//This page adds books to the shopping cart.
$page_title = 'Add to cart';
include('includes/header.html');
if(!isset($_SESSION['user_id'])){//did not loggin
  echo "<p>Please login your accout first";
  
}elseif(isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT, array('min-range'=>1))){//check for a book ID.
   $pid = $_GET['pid'];
   
   //check if the cart already contains one of these books.
   //If so, increment the quantity.
   if(@($_SESSION['cart'][$pid])){
	   $_SESSION['cart'][$pid]['quantity']++; //add another.
	   //display a message.
	   echo '<p>another copy of the book has been added to your shopping cart.<p>';
   }else{//New product to the cart.
   //Get the book's price from the database.
     require('../mysqli_connect.php');
     $q = "SELECT price FROM books WHERE books.book_id=$pid";
     $r = mysqli_query($dbc, $q);
     if(mysqli_num_rows($r)== 1){//Valid book ID.
   // Fetch the information
       list($price) = mysqli_fetch_array($r, MYSQLI_NUM); //list() 函数用于在一次操作中给一组变量赋值。
	 //add to the cart.
	   $_SESSION['cart'][$pid] = array('quantity'=>1, 'price'=>$price);
	 
	 //display a message.
	   echo '<p>The book has been added to your shopping cart.<p>';
     }else{//Not a valid  book ID.
       echo '<div align=\"center\"> This page has been accessedd in error!</div>';
     }
    mysqli_close($dbc); 
    }//end of  $_SESSION['cart'][$pid] If.
}else{ //No book ID 
   echo '<div align="center">This page has been accessedd in error!</div>';
}
  include('./includes/footer.html');
?>