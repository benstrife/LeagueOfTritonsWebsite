<?php 

require 'core/init.php';

//Check to see if anything was sent to the file
if(empty($_POST) === false) {
    //Grab summoner name 
    $summoner = trim($_POST['summoner']);
    if(strlen($summoner) > 16) {
        echo "Summoner name must be between (3-16) characters";
        echo false;
        exit();
    }
    $college = $_POST['college'];
    $name = $_POST['name'];
    $shirt_size = $_POST['shirt_size'];

    $users->update_profile($user['id'], $name, $summoner, $college, $shirt_size);
    echo true;
    exit();
}

echo false;

?>