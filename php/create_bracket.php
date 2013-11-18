<?php

/*
 * This page takes all of the registered teams, and setups a fair bracket
 * based on tthe amount of points each team has accumalated.
 * 
 */

//Only execute this code if a post was sent
if(isset($_POST['id'])) {
    //Import Database
    require('../db/database.php');

    //Tournament ID
    $t_id = $_POST['id'];

    //Figure out the tournament's status
    $startQuery = $db->prepare("SELECT status FROM tournaments WHERE id=?");
    $startQuery->bindValue(1, $t_id);
    $startQuery->execute();
    
    //Make sure the tournament hasn't already started (PRECAUTION)
    if($startQuery->fetchColumn() == 0) { 
        //Grab all the teams
        $teamQuery = $db->prepare("SELECT id FROM `teams` WHERE `t_id` = ? ORDER BY id ASC");
        $teamQuery->bindValue(1, $t_id);
        $teamQuery->execute();

        //Number of teams playing
        $num_teams = $teamQuery->rowCount();

        //Sum up each teams points
        for($i=0; $team_id = $teamQuery->fetchColumn(); ++$i) {
            //Reset the Team total
            $teamData[$i]['points'] = 0;
            //Store the ID
            $teamData[$i]['id'] = $team_id;

            //Query all of the players on that specific team
            $playerQuery = $db->prepare("SELECT user_id FROM `players` WHERE `team_id` = ?");
            $playerQuery->bindValue(1, $team_id);
            $playerQuery->execute();

            //Grab all of the players from that team
            while($playerData = $playerQuery->fetch()) {
                //Store the secret ID of the player
                $user_id = $playerData['user_id'];

                //Sum up the player's points
                $pointsQuery = $db->prepare("SELECT SUM(points) FROM ladder WHERE user_id = ?");
                $pointsQuery->bindValue(1, $user_id);
                $pointsQuery->execute();

                $points = $pointsQuery->fetchColumn();

                $teamData[$i]['points'] += $points;
            }

            echo $teamData[$i]['id'] . $teamData[$i]['points'] . '<br/>';
        }

        //Sorts the teams from highest to lowest points
        usort($teamData, function($a, $b) {
            return $b['points'] - $a['points'];
        });

        for($x=0; $x<count($teamData); ++$x) {
            echo $teamData[$x]['id'] . ':' . $teamData[$x]['points'] . ' ';
        }

        echo '<br/>';

        //Tournament Variables
        $NUM_PLAYERS = $num_teams;

        //Create the tournament bracket with seeds (1,2) already inserted
        $tournament_position = array(1, 2);

        /* For Loop creates the seeding for the tournament */
        for($num_seeds = 2; $num_seeds < $NUM_PLAYERS; $num_seeds*=2)
        {
                //Figure out the new sum that will determine new seed placement
                $sum = ($num_seeds * 2) + 1;

                //Counter runs the loop; i is a previous seed
                for($counter = 0, $i = 0; $counter < $num_seeds; ++$counter, $i+=2)
                {
                        $seed = $sum - $tournament_position[$i]; 

                        //If it is even, we want to place the new seed to the right
                        if($counter%2 == 0)
                        {
                                $index = $i + 1;
                        }
                        //If it is odd, we want to place the new seed to the left
                        else
                        {
                                $index = $i;
                        }

                        //Insert the seed into the array
                        array_splice($tournament_position, $index, 0, $seed);
                }
        }

        for($x=0; $x<count($tournament_position); ++$x) {
            echo $tournament_position[$x] . ' ';
        }

        $num_matches = count($tournament_position)/2;

        echo '<br/>';

        //Make matches based on the sums
        for($i=0, $x=0; $i<$num_matches; ++$i, $x+=2) {
            //Check Team 1
            if(!empty($teamData[$tournament_position[$x] - 1]['id'])) {
                $team1_id = $teamData[$tournament_position[$x] - 1]['id'];
            }
            else {
                $team1_id = 0;
            }
            //Check Team 
            if(!empty($teamData[$tournament_position[$x+1] - 1]['id'])) {
                $team2_id = $teamData[$tournament_position[$x+1] - 1]['id'];
            }
            else {
                $team2_id = 0;
            }


            $insertMatchQuery = $db->prepare("INSERT INTO matches (t_id, number, rd, team1_id, team2_id) VALUES (?, ?, ?, ?, ?)");
            $insertMatchQuery->bindValue(1, $t_id); 
            $insertMatchQuery->bindValue(2, $i); 
            $insertMatchQuery->bindValue(3, 1); 
            $insertMatchQuery->bindValue(4, $team1_id); 
            $insertMatchQuery->bindValue(5, $team2_id);
            $insertMatchQuery->execute();
        }

        //Figure out the number of rounds and add the appropriate BLANK matches
        for($num_matches=($i/2), $num_round=2; $num_matches >= 1; $num_matches/=2, $num_round++) {
            for($i=0; $i<$num_matches; ++$i) {

                $insertMatchQuery = $db->prepare("INSERT INTO matches (t_id, number, rd) VALUES (?, ?, ?)");
                $insertMatchQuery->bindValue(1, $t_id); 
                $insertMatchQuery->bindValue(2, $i); 
                $insertMatchQuery->bindValue(3, $num_round); 
                $insertMatchQuery->execute();
            }
        }

        //Set the tournament as started
        $startQuery = $db->prepare("UPDATE tournaments SET status=2 WHERE id=?");
        $startQuery->bindValue(1, $t_id);
        $startQuery->execute();
    }
}

?>