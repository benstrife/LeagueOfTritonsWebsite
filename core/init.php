<?php 

#starting the users session
session_start();
require 'db/database.php';
require 'classes/users.php';
require 'classes/general.php';
 
$users 		= new Users($db);
$general 	= new General();
 
if ($general->logged_in() === true)  { // check if the user is logged in
	$user_id 	= $_SESSION['id']; // getting user's id from the session.
	$user 	= $users->userdata($user_id); // getting all the data about the logged in user.
}


$errors 	= array();

/* ------------------------------------------------- */
/* THIS CODE DEALS WITH PAGE NAVIGATION AND SECURITY */

//Defaults
$current_page = 'home';
$page_access = 0;

//Parse the GET vraiables
if(isset($_GET['page'])) {
    //Create the appropriate file path
    $file_path = "pages/" . strtolower($_GET['page']) . ".php";
    //Make sure the page they are accessing exists
    if(file_exists($file_path)) {
        //The current page displayed
        $current_page = $_GET['page'];
    }
}

//Figure out the access level of the page
$query 	= $db->prepare("SELECT access FROM `pages` WHERE `name` = ?");
$query->bindValue(1, $current_page);
try{
        $query->execute();
}catch(PDOException $e){
        die($e->getMessage());
}

//Compare against the users access level..
if($page_access = $query->fetchColumn()) {
    if($user['access'] < $page_access) {
        header("Location: index.php?page=forbidden");
        die();
    }
}

/* ------------------------------------------------- */

?>