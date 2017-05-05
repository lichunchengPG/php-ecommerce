//Script -3.12 -register.js
//This script is included by register.php
//This script handles and validate the form submission.
//This script then makes an Ajax request of register_ajax.php

//Do someting when the document is ready:
$(document).ready(function(){
	//Hide all error message:
	$('.errorMessage').hide();
	//Assign an event handler to the form:
	$('#register').submit(function(){
		//Initialize some variables:
		var email, password;
		//validate the email address:
		var regex = /^[a-z]([a-z0-9]*[-_]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/;
		if($('#email').val().length >=6 && regex.test($('#email')){
			//get the email address.
			email = $('#email').val();
			
			//clear an error ,if one existed:
			$('#emailP').removeClass('error');
			//Hide the error message, if it was visible:
			$('#emailError').hide();
		}else{//invalide email address!
		//add an error class:
		$('#emailP').addClass('error');
		//Show the error message:
		$('#emailError').show();
		}
		
		
	    //validate the password
		if($('password').val().length>0){
			password= $('password').val();
			$('#passwordP').removeClass('error');
			$('#passwordError'),hide();
		}else{
			$('#passwordP').addClass('error');
			$('#passwordError').show();
		}
		//IF appropriate perform the Ajax request.
		if(email && password){
           //Create an object for the form data;
		   var data = new Object();
		   data.email =email;
		   data.password = password;
		   //Create an object of Ajax options:
		   var options = new Object();
		   //Establish each setting:
		   options.data = data;
		   options.datatype = 'text';
		   options.type = 'get';
		   options.success = function(response){
		     //worked
			 if(response == 'CORRECT'){
				 //hide the form:
				 $('#register').hide();
				 //Show a message；
				 $('#results').removeClass('error');
				 $('#results').text('You  has been register in ');
			 }else if (response == 'INVALID_EMAIL'){
                 $('#results').addClass('error');
                 $('#results').text('Please provide a vaild email address');
			 }else if(response == 'INCOMPLETE'){
				 $('#results').addClass('error');
				 $('#results').text('Please provide your email_address and password');
			 }
				 
		   };
		   option.url = 'register_ajax.php';
           //preform the request.
           $.ajax(options);		   
		} // end of if(email && password)
		//return false to prevent an actual form submission.
	    return false;
		
		  
		
	}) // end of the form submission
	
	
	
	
	
	
})  //end of document ready.