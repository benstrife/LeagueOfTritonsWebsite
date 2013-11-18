<?php 

require('../db/database.php');

$secret_id = $_GET['id'];

$query 	= $db->prepare("SELECT summoner, name FROM `users` WHERE `secret_id` = ?");
$query->bindValue(1, $secret_id);
try{
        $query->execute();
}catch(PDOException $e){
        die($e->getMessage());
}

$data = $query->fetch();
if($data['summoner']) {
    echo $data['name'] . "," . $data['summoner'];
}
else {
    echo false;
}
?>