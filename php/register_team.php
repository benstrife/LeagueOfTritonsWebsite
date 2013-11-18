<?php

require('../db/database.php');

$MAX_NAME_SIZE = 20;

$captain = $_POST['0'];

//Tournament ID
$t_id = $_POST['t_id'];
//Team Name
$team_name = $_POST['team'];

// Max team name size 
if($team_name > $MAX_NAME_SIZE) {
    echo "Team name exceeds 20 characters.  Please find a smaller name.";
}

//Make sure the captain hasn't already registered...
$query = $db->prepare("SELECT name from teams WHERE t_id = ? AND captain = ?");
$query->bindValue(1, $t_id);
$query->bindValue(2, $captain);
try{
        $query->execute();
}catch(PDOException $e){
        die($e->getMessage());
}
if($query->RowCount() > 0) {
    echo 'You have already registered a team!';
    exit();
}

//Validate
if(trim($team_name) == "") {
    echo "No team name specified";
    exit();
}

//Validate all userID. MAKE sure they all correspond to someone.
$playerCheck = $db->prepare("SELECT id FROM users WHERE `secret_id` IN (?, ?, ?, ?, ?)");
for($i=0; $i<5; ++$i) {
    $playerCheck->bindValue(($i+1), $_POST[$i]); 
}
$playerCheck->execute();
if($playerCheck->RowCount() != 5) {
    echo "One or more of your players does not exist!";
    exit();
}
     
//Put the team in
$query = $db->prepare("INSERT INTO teams (t_id, name, captain, register_time) VALUES (?, ?, ?, ?)");
$query->bindValue(1, $t_id);
$query->bindValue(2, $team_name);
$query->bindValue(3, $captain);
$query->bindValue(4, date('Y-m-d H:i:s'));
try{
            $query->execute();
    }catch(PDOException $e){
            die($e->getMessage());
    }
    
//Get the Team ID 
$query = $db->prepare("SELECT id FROM teams WHERE t_id = ? AND captain = ?");
$query->bindValue(1, $t_id);
$query->bindValue(2, $captain);
try{
            $query->execute();
    }catch(PDOException $e){
            die($e->getMessage());
    }
    
$team_id = $query->fetchColumn();


for($i=0; $i<5; ++$i) {
    $query = $db->prepare("INSERT INTO players (user_id, t_id, team_id) VALUES (?, ?, ?)");
    $query->bindValue(1, $_POST[$i]);
    $query->bindValue(2, $t_id);
    $query->bindValue(3, $team_id);
    
    try{
            $query->execute();
    }catch(PDOException $e){
            die($e->getMessage());
    }    
}

//Success
echo true;

     


//Put all the players in


//Put the team in 

?>