<?php

//Only allow admins on the page
if($user['access'] < 900) {
    echo 'No access';
    exit();
}

//create seeds
//$tournamentObj->createGroupStage();


//$tournamentObj->endGroupStage();
$tournamentObj->endTournament();
?>

<div class="box">
    <?php
    
    //If the tournament has not started
    if($tournament['status'] == 0) {
        echo ' <div id="create_bracket" class="' . $t_id . '">Create Bracket</div>';
    }
    
    //If the tournament has started
    else if($tournament['status'] == 1) {
        //Print out all of the matches
        echo '<div class="match">';
        echo '<div>';
        echo '<div class="team1">Team1</div>';
        echo '<div class="score1">Score1</div>';
        echo '<div id="winner">Winner</div>';
        echo '</div>';  
            
        echo '<div>';
        echo '<div class="team2">Team2</div>';
        echo '<div class="score2">Score2</div>';
        echo '<div id="update">Update</div>';
        echo '</div>
        </div>';
    }
    else {
        //$tournamentObj->endTournament();
    }
    ?>
</div>