<?php 

# We are storing the information in this config array that will be required to connect to the database.
$config = array(
	'host'		=> 'localhost',
	'username'	=> 'root',
	'password'	=> '',
	'dbname' 	=> 'lot'
);
#connecting to the database by supplying required parameters
$db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['username'], $config['password']);
 
#Setting the error mode of our db object, which is very important for debugging.
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>