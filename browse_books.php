<?php #Scirpt 3.4 -brows_books.php
//This  page displaces the available books.
//Set the page title and include the HTML header.
include('./includes/header.html');
require('../mysqli_connect.php');

//default query for this page:
$q = "SELECT authors.author_id, CONCAT_WS(' ',first_name,last_name)AS author, book_name, price,
publisher, book_id FROM authors, books WHERE authors.author_id=books.author_id ORDER BY  authors.last_name
,books.book_name ASC";

//Are we looking at a perticular author?
if(isset($_GET['aid']) && filter_var($_GET['aid'], FILTER_VALIDATE_INT, array('min_range'=>1))){
	//Overwrite the query:
	$q = "SELECT authors.author_id, CONCAT_WS(' ',first_name,last_name)AS author, book_name, price,
    publisher, book_id FROM authors, books WHERE authors.author_id=books.author_id AND 
	books.author_id={$_GET['aid']} ORDER BY  authors.last_name,books.book_name ASC";
}

//Create the table head:
echo '<table border="0" width="90%" cellspacing="0" cellpadding="0" align="center">
     <tr id="book_thead">
	   <td align="left" width="30%"><b>Author</b></td>
	   <td align="left" width="30%"><b>Book Name</b></td>
	   <td align="left" width="30%"><b>Publisher</b></td>
	   <td align="right" width="10%"><b>Price</b></td>
	 </tr>';
//display all the books, linked to URLs:
$r = mysqli_query($dbc, $q);

if(mysqli_num_rows($r)>0){
	while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
		//display each record:
		echo "<tr id=\"book_tbody\">
		     <td align=\"left\" width=\"20%\"><a href=\"browse_books.php?aid={$row['author_id']}\">{$row['author']}</a></td>
			 <td align=\"left\" width=\"20%\"><a href=\"view_book.php?pid={$row['book_id']}\">{$row['book_name']}</a></td>
			 <td align=\"left\" width=\"40%\">{$row['publisher']}</td>
			 <td align=\"right\" width=\"20%\">{$row['price']}</td>
			 </tr>";
	}
}else{
	echo "Please add author and book first.";
}
echo '</table>';
mysqli_close($dbc);
include('./includes/footer.html');
?>