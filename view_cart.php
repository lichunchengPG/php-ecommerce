<?php #Script 3.8 -view_cart.php
//This page displays the contents of the shopping cart.
//This page also  lets the users update the contents of the cart.

//Set the page title and include the header.html.
$page_title = 'View Your Shopping Cart.';
include('./includes/header.html');

//Check if the form has been submmited(to update the cart).
if($_SERVER['REQUEST_METHOD'] =='POST'){
	//change any quantities:
	foreach($_POST['qty'] as $k => $v){
		//must be integers!
		$pid = (int)$k;
		$qty = (int)$v;
		if($qty == 0){//delete
		  unset($_SESSION['cart'][$pid]);
		}elseif($qty > 0){//Change quantity.
		  $_SESSION['cart'][$pid]['quantity'] = $qty ;
		}
	}//end of FOREACH.
}//end of submmited IF.

//display the cart if is not empty.
if(!isset($_SESSION['user_id'])){
	echo "Please login your account first.";
}elseif(@($_SESSION['cart'])){
	//retrieve all of the information for the book in the cart.
	require('../mysqli_connect.php');//connect to the database.
	$q = "SELECT book_id,CONCAT_WS(' ', first_name, last_name) AS author, book_name FROM authors,books 
	     WHERE authors.author_id = books.author_id AND books.book_id IN(";
	foreach($_SESSION['cart'] as $pid => $value){
		$q.=$pid.',';       //.= 表示连续定义变量
	}
	$q = substr($q,0,-1).')ORDER BY authors.last_name ASC'; // -1 表示倒数第
	$r = mysqli_query($dbc,$q);
	
	//Create a form and a table
	echo '<form action="view_cart.php" method="POST">
	<table border="0" cellspacing="3" cellpadding="3" width="90%" align="center">
	<tr>
	  <td align="left" width="30%"><b>Artist</b></td>
	  <td align="left  width="30%"><b>Book Name</b></td>
	  <td align="left" width="10%"><b>Price</b></td>
	  <td align="left" width="10%"><b>Qty</b></td>
	  <td align="right" width="10%"><b>Total Price</b></td>
	</tr>';
	
	//print each item...
	$total=0; //Total cost of order.
	while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
		$subtotal = $_SESSION['cart'][$row['book_id']]['price'] * $_SESSION['cart'][$row['book_id']]['quantity'];
		$total +=$subtotal;
		//print the row:
		echo "\t<tr><td align=\"left\">{$row['author']}</td>
		<td align=\"left\">{$row['book_name']}</td>
		<td align=\"left\">{$_SESSION['cart'][$row['book_id']]['price']}</td>
		<td align=\"left\"><input type=\"text\" size=\"3\" name=\"qty[{$row['book_id']}]\" value=\"{$_SESSION['cart'][$row['book_id']]['quantity']}\"></td>
		<td align=\"right\">$".number_format($subtotal,2)."</td></tr>\n";
	}//End of the WHILE loop.
	mysqli_close($dbc);//Close the database connection.
	
	echo '<tr><td align="right" colspan ="4"><b>Total:</b></td>
	<td align="right">$'.number_format($total,2).'</td>
	</tr>
	</table>
	<div align="center"><input type="submit" name="submit" value="Update My Cart"/></div>
	</form><p align="center">Enter a  quantity of 0 to remove an item.</p>
    <p align="center"><a href="checkout.php">Checkout</a></p>;
	<p align="center"><a href="submit_order.php?$total">Submit Order</a><p>';
}else{
  echo '<p>Your cart is currently empty.</p>';
}
include('./includes/footer.html');
 ?>
	
	
	
	
	
	
	