<?php

//Grab the match to display
$matchQuery = $db->prepare("SELECT * FROM `matches` WHERE `t_id` = ? AND `rd` = ? AND `number` = ?");
$matchQuery->bindValue(1, $tournament['id']);
$matchQuery->bindValue(2, $_GET['rd']);
$matchQuery->bindValue(3, $_GET['num']);
$matchQuery->execute();

$matchData = $matchQuery->fetch();

//Get Data on the two teams
/* CHANGE SQL TO "in" statement */
$teamQuery = $db->prepare("SELECT name FROM `teams` WHERE `id` = ? OR `id` = ?");
$teamQuery->bindValue(1, $matchData['team1_id']);
$teamQuery->bindValue(2, $matchData['team2_id']);
$teamQuery->execute();


?>

<div id="t_match">
    <div class="box">
<?php

//echo $matchData['team1_id'];
for($i=0; $i<2; ++$i) {
    $teamData = $teamQuery->fetch();
    //echo $teamData['name'];
}

?>
    
    <div id="match_header">
        <div class="team"></div>
        <div id="score">0 - 0</div>
        <div class="team">Team 2</div>
    </div>
    <div class="teams">
        <div class="team_left">
            <div class="player">Player</div>
        </div>
        <div class="team_right">
            <div class="player">Player</div>
        </div>
    </div>
    <div class="clear"></div>
    
    </div>
</div>