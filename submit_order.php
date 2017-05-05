<?php #Script 3.9 -submit_order.php
//This page insert the order information into the table.
//This page would come after the billing process.
//This page assumes that the billing process worked (the money has been taken).

//Set the page tile and include the HTML header:
$page_title = 'Order Confirmation';
include('includes/header.html');

//Assume that the customer is logged in and that this page has access to the customer's ID.
$cid = $_SESSION['user_id']; //Temporary.
//Assume that this page receives the order total:
// Temporary.

require('../mysqli_connect.php');//Connect to the database.
mysqli_autocommit($dbc, FALSE);
$q = "INSERT INTO orders(customer_id, total)VALUES($cid, $total)";
$r = mysqli_query($dbc, $q);
if(mysqli_affected_rows($dbc) == 1){
	//Need the order ID.
	$oid = mysqli_insert_id($dbc);
	//Insert the specific order contents into the database.
    //prepare the query.
	$q = "INSERT INTO order_contents(order_id, book_id, quantity, price)VALUES(?,?,?,?)";
	$stmt = mysqli_prepare($dbc, $q);
	mysqli_stmt_bind_param($stmt, 'iiid', $oid, $pid, $qty, $price);
	//Execute each query and the total affected:
	$affected = 0;
	foreach($_SESSION['cart'] as $pid => $item){
		$qty = $item['quantity'];
		$price = $item['price'];
		mysqli_stmt_execute($stmt);
		$affected += mysqli_stmt_affected_rows($stmt);
	}
	//close this prepared statement:
	mysqli_stmt_close($stmt);
	
	//report on the success:
	if($affected == count($_SESSION['cart'])){
		//commit the transaction . 提交事务
		mysqli_commit($dbc);
		//clear the cart.
		unset($_SESSION['cart']);
		
		//Message to the customer.
		echo '<p>Thank for your order.You will be notified when the items ship.</p>';
		
		//Send email and do whatever else.
		
	}else{//Rollback and report the problem.
	    mysqli_rollback($dbc);
		echo '<p>Your order could not be processed due to a system error. You will be contacted in order to 
		have the problem fixed. We apologize for the inconvenience.</p>';
		//Send the order information to the administrater.
	}
}else{//Rollback and report the problem.
		echo '<p>Your order could not be processed due to a system error. You will be contacted in order to 
		have the problem fixed. We apologize for the inconvenience.2</p>';
		//Send the order information to the administrater.
}
mysqli_close($dbc);
include('includes/footer.html');
?>	
	
	
	