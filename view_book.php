<?php #Script 3.5 -view_book.php
//This page displays the details for a particular book.
$row =FALSE; //Assume nothing!
if(isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT, array('minrange'=>1))){//Make sure there's a book ID!
   $pid = $_GET['pid'];
   //get the book info:
   require('../mysqli_connect.php');//connect to de database.
   $q = "SELECT CONCAT_WS(' ',first_name, last_name) as author, book_name, price, description, publisher, image_name
   FROM authors, books WHERE authors.author_id = books.author_id AND books.book_id = $pid ";
   $r = mysqli_query($dbc, $q);
   if(mysqli_num_rows($r) == 1){// good to go!
       //fetch the information:
       $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
       //Start the HTML page:
       $page_title = $row['book_name'];
       include('./includes/header.html');
       //display a header.
       echo "<div align=\"center\"><b>{$row['book_name']}</b>
           	by {$row['author']}<br>";
       echo (is_null($row['publisher']))? '(No publisher information available)' : $row['publisher'];
       echo "<br>\${$row['price']}<a href =\"add_cart.php?pid=$pid\">Add to Cart</a>
            </div><br>";
       
       //get the image information and  display the image.
       if($image = @getimagesize("./uploads/$pid")){ //索引 3 给出的是一个宽度和高度的字符串
          echo "<div align =\"center\"> <img src=\"show_image.php?image=$pid&name=".urlencode($row['image_name']).
	      "\" $image[3] alt=\"{$row['book_name']}\"/></div>\n";
	   }else{
            echo "<div align=\"center\">No image avaliable.</div>\n";
	   }
        
       //add the description or default message:
       echo '<p align=\"center\">'.((is_null($row['description']))?'(no description avaliable)':$row['description']).'</p>';
   }//end of the mysqli_num_rows IF
    mysqli_close($dbc);
}// end of the $_GET['pid'] IF.
if(!$row){// Show an error message.
   $page_title = 'Error';
   include('./includes/header.html');
   echo '<div align=\"center\">This page has been accessed in error!</div>';
}
//Complete the page:
include('./includes/footer.html');
?>