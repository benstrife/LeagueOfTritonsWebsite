<?php

/*
 * 
 * Description: Sums up the ladder points for each player
 */

require('../db/database.php');

$query = $db->prepare("SELECT DISTINCT user_id FROM ladder");
$query->execute();

/* ADD A DATE? Maybe that can help so we don't redo things */

//Fetch each person... and then sum up their points
while($player = $query->fetch()) {
    echo $player['user_id'];
    
    $sumQuery = $db->prepare("SELECT SUM(points) FROM ladder WHERE user_id = ?");
    $sumQuery->bindValue(1, $player['user_id']);
    $sumQuery->execute();

    
    $total_points = $sumQuery->fetchColumn();
    
    $insertSum = $db->prepare("INSERT INTO ladder_sum (user_id, total) VALUES (:user_id, :total)
ON DUPLICATE KEY UPDATE
user_id = :user_id, total = :total");
    $insertSum->bindValue(':user_id', $player['user_id']);
    $insertSum->bindValue(':total', $total_points);
    $insertSum->execute();
}


?>