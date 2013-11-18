<?php

//Include the initialize page
require 'core/init.php';

//Check to see if anything was sent to the file
if(empty($_POST) === false) {
	//Trim whitespace
    /*FIX*/ //MAKE ALL LOWER-CASE EMAILS
	$email = strtolower(trim($_POST['email']));
	$password = trim($_POST['password']);
 
	/* Check for errors... */
	//Empty columns
	if (empty($email) === true || empty($password) === true) {
		$errors[] = 'Sorry, but we need your username and password.';
	} 
	//Non-existant account
	else if ($users->email_exists($email) === false) {
		$errors[] = 'Sorry that username doesn\'t exists.';
	} 
	//Non-activated account
	else if ($users->email_confirmed($email) === false) {
		$errors[] = 'Sorry, but you need to activate your account. 
					 Please check your email.';
	}
	
	//Else..everything is fine
	else {
		//Attempt to login the user
		$login = $users->login($_POST['email'], $_POST['password']);

		//Either it failed..
		if ($login === false) {
			$errors[] = 'Sorry, that username/password is invalid';
		}
		//It passed!
		else {
			//Store the ID of the user
 			$_SESSION['id'] =  $login;
			echo true;
		}
	}	
}

if(empty($errors) === false){
        echo implode('</p><p>', $errors);
}


?>