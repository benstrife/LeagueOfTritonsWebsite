<?php

Class Tournament {
    private $db;
    public $id;
    public $page = "home";
 
    public function __construct($database, $id, $page) {
        $this->db = $database;
        $this->id = $id;
        $this->page = $page;
    }
    
    /*
     * Returns the data from the tournament.  If the tournament does not
     * exist, returns false.
     */
    public function tournamentData() {
        //Figure out the information of the tournament
        $query 	= $this->db->prepare("SELECT * FROM `tournaments` WHERE `id` = ?");
        $query->bindValue(1, $this->id);
        try{
            $query->execute();
        }catch(PDOException $e){
            die($e->getMessage());
        }
        
        //Compare against the users access level..
        if($query->RowCount() <= 0) {
            return false;
        }
        
        return $query->fetch();
    }
    
    /*
     * Function: createSeeds()
     * 
     * Description: This function loads the teams in the tournament,
     *   and seeds them based on their ladder points.  An array is
     *   then returned that contains the teams in seeded order.
     */
    private function createSeeds() {
        //Grab all the teams
        $teamQuery = $this->db->prepare("SELECT id FROM `teams` WHERE `t_id` = ? ORDER BY id ASC");
        $teamQuery->bindValue(1, $this->id);
        $teamQuery->execute();

        //Sum up each teams points
        for($i=0; $team_id = $teamQuery->fetchColumn(); ++$i) {
            //Reset the Team total
            $teamData[$i]['points'] = 0;
            //Store the ID
            $teamData[$i]['id'] = $team_id;

            //Query all of the players on that specific team
            $playerQuery = $this->db->prepare("SELECT user_id FROM `players` WHERE `team_id` = ?");
            $playerQuery->bindValue(1, $team_id);
            $playerQuery->execute();

            //Grab all of the players from that team
            while($playerData = $playerQuery->fetch()) {
                //Store the secret ID of the player
                $user_id = $playerData['user_id'];

                //Sum up the player's points
                $pointsQuery = $this->db->prepare("SELECT SUM(points) FROM ladder WHERE user_id = ?");
                $pointsQuery->bindValue(1, $user_id);
                $pointsQuery->execute();

                $points = $pointsQuery->fetchColumn();

                $teamData[$i]['points'] += $points;
            }
        }

        //Sorts the teams from highest to lowest points
        usort($teamData, function($a, $b) {
            return $b['points'] - $a['points'];
        });
        
        return $teamData;
    }
    
    /*
     * Function: endTournament()
     * 
     * Invariant: Tournament is in progress
     */
    public function endTournament() {
        //Query all matches linked to this tournament
        $query = $this->db->prepare("SELECT * FROM `matches` WHERE `t_id` = ?");
        $query->bindValue(1, $this->id);
        $query->execute();
        
        //Fetch each match
        while($result = $query->fetch()) {
            echo "Looking at match";
            //Winning team: team$_id
            $winning_team = "team" . $result['winner'] . "_id";
            
            if($result['rd'] > 0) {
                //The amount of points the team receives
                $match_value = 16;
                if($result['rd'] == 1) {
                    $match_value = 32;
                }
            }
            else {
                $match_value = 12;
            }
            //Get the players on the winning team
            $playerQuery = $this->db->prepare("SELECT user_id FROM `players` WHERE team_id = ?");
            $playerQuery->bindValue(1, $result[$winning_team]);
            $playerQuery->execute();
            
            //Allocate points to each player on the winning team1
            while($playerResult = $playerQuery->fetch()) {
                $insertPoints = $this->db->prepare("INSERT INTO ladder (user_id, t_id, points) 
                                                    VALUES (?, ?, ?)");
                $insertPoints->bindValue(1, $playerResult['user_id']);
                $insertPoints->bindValue(2, $this->id);
                $insertPoints->bindValue(3, $match_value);
                $insertPoints->execute();
            }
        }
        
        //Close the tournament
        $query = $this->db->prepare("UPDATE `tournaments` SET status=3 WHERE id=?");
        $query->bindValue(1, $this->id);
        $query->execute();
    }
    
    public function get_navbar() {
        $pages = array(
            "applicants",
            "register",
            "bracket",
            "details"
        );
        
        return $pages;
    }
    
    /*
     * Function: createGroupStage
     * 
     * Description: Creates the group stage matches
     */
    public function createGroupStage() {
        //Create an array with the teams in seeded order
        $cleanTeamSeeds = $this->createSeeds();
        //Number of groups to create
        $numGroups = 4;
        
        //Reorder teamSeeds
        //Chunk the array
        $chunkedA = array_chunk($cleanTeamSeeds, $numGroups);
        $teamSeeds = array_reverse($chunkedA[0]);
        for($i=1; $i<count($chunkedA); ++$i) {
            //Splice the array back together
            $teamSeeds = array_merge($teamSeeds, $chunkedA[$i]);
        }
        
        
        /*
         * $i traverses through teamSeeds [Each player]
         * $x is the number a person is in a specific group. 
         *   ie. group 1: person 1
         *       group 2: person 1
         * $groupNum is the group number 
         */
        
        //Place people into groups
        for($i=0, $groupNum=0, $x=0; $i<count($teamSeeds); ++$i) {
            //Insert person into group
            $group[$groupNum][$x] = $teamSeeds[$i]['id'];
            
            ++$groupNum;
            //Reset if we have reached max groups
            if($groupNum == $numGroups) {
                $groupNum = 0;
                ++$x;
            }
        }
        
        //DEBUG
        /*
        for($i=0; $i<$numGroups; ++$i) {
            echo 'Group ' . $i . ' ';
            for($x=0; $x<count($group[$groupNum]); ++$x) {
                echo $group[$i][$x] . ' ';
            }
            echo '</br>/';
        }
         * */

        
        //Create matches based on those groups
        for($groupNum=0, $x=0; $groupNum < $numGroups; ++$groupNum) {
            for($i=0; $i<count($group[$groupNum]); ++$i) {
                for($j=$i+1; $j<count($group[$groupNum]); ++$j) {
                    $this->addMatch(-$groupNum, $x, $group[$groupNum][$i], $group[$groupNum][$j]);
                    ++$x;
                }
            }
        }
        
        //Start the tournament
        $this->startTournament();
    }
    
    /*
     * Starts a tournament by setting its status to 2
     */
    private function startTournament() {
        $query = $this->db->prepare("UPDATE tournaments SET status=2 WHERE id = ?");
        $query->bindValue(1, $this->id);
        $query->execute();
    }
    
    /*
     * Function: getGroupStage
     * 
     * Description: Returns the group stage matches
     */
    public function getGroupStage() {
        $query = $this->db->prepare("SELECT rd, team1_id, team2_id, winner, team1_score, team2_score FROM matches WHERE t_id = ? AND rd < 1");
        $query->bindValue(1, $this->id);
        $query->execute();
        
        if($query->RowCount() > 0) {
            while($matchData = $query->fetch()) {
                //Performs a stack 'push' for element
                $matches[$matchData['rd']][] = $matchData;
            }
        }
        else {
            return false;
        }
        
        //Return the array full of data
        return $matches;
    }
    
    /*
     * Function: endGroupStage
     * 
     * Description: Ends the group stage and returns an array of 
     *   teams that got out.  Returns false if failure
     */
    public function endGroupStage() {
        /*
        for($i=0; $i<1; ++$i) {
            //Grab all the group matches
         //   $query = $this->db->prepare("SELECT team1_id FROM matches WHERE winner = 1 AND t_id = ? AND rd = ?");
            $query = $this->db->prepare("SELECT team1_id, COUNT(*) AS count FROM matches WHERE winner = 1  AND t_id = ? AND rd = ? GROUP BY team1_id");
            $query->bindValue(1, $this->id);
            $query->bindValue(2, -$i);
            $query->execute();
            while($matchData = $query->fetch()) {
                $teamSums[$matchData['team1_id']] = $matchData['count'];
            }
            

            
            $query = $this->db->prepare("SELECT team2_id, COUNT(*) AS count FROM matches WHERE winner = 2  AND t_id = ? AND rd = ? GROUP BY team2_id");
            $query->bindValue(1, $this->id);
            $query->bindValue(2, -$i);
            $query->execute();
            while($matchData = $query->fetch()) {
                $teamSums[$matchData['team2_id']] = $matchData['count'];
            }
            
            //Sorts the teams from highest to lowest points
            /*
            usort($teamSums, function($a, $b) {
                return $b - $a;
            });
            
            
            
            print_r($teamSums);
            
        }
        
        */
        
        //8 teams
        $advancedTeams = array(
          30,
          28,
            31,
            25,
            26,
          35,
           37,
           39
          
        );
        
        print_r($advancedTeams);
        
        //Tournament Variables
        $NUM_PLAYERS = count($advancedTeams);

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
        
        print_r($tournament_position);

        for($x=0; $x<count($tournament_position); ++$x) {
            echo $tournament_position[$x] . ' ';
        }

        $num_matches = count($tournament_position)/2;

        echo '<br/>';

        //Make matches based on the sums
        for($i=0, $x=0; $i<$num_matches; ++$i, $x+=2) {
            //Check Team 1
            if(!empty($advancedTeams[$tournament_position[$x] - 1])) {
                $team1_id = $advancedTeams[$tournament_position[$x] - 1];
            }
            else {
                $team1_id = 0;
            }
            //Check Team 
            if(!empty($advancedTeams[$tournament_position[$x+1] - 1])) {
                $team2_id = $advancedTeams[$tournament_position[$x+1] - 1];
            }
            else {
                $team2_id = 0;
            }
            
            echo $team1_id . ' ';
            echo $team2_id . ' ';


            $insertMatchQuery = $this->db->prepare("INSERT INTO matches (t_id, number, rd, team1_id, team2_id) VALUES (?, ?, ?, ?, ?)");
            $insertMatchQuery->bindValue(1, $this->id); 
            $insertMatchQuery->bindValue(2, $i); 
            $insertMatchQuery->bindValue(3, 1); 
            $insertMatchQuery->bindValue(4, $team1_id); 
            $insertMatchQuery->bindValue(5, $team2_id);
            $insertMatchQuery->execute();
        }

        //Figure out the number of rounds and add the appropriate BLANK matches
        for($num_matches=($i/2), $num_round=2; $num_matches >= 1; $num_matches/=2, $num_round++) {
            for($i=0; $i<$num_matches; ++$i) {

                $insertMatchQuery = $this->db->prepare("INSERT INTO matches (t_id, number, rd) VALUES (?, ?, ?)");
                $insertMatchQuery->bindValue(1, $this->id); 
                $insertMatchQuery->bindValue(2, $i); 
                $insertMatchQuery->bindValue(3, $num_round); 
                $insertMatchQuery->execute();
            }
        }

        //Set the tournament as started
        $startQuery = $this->db->prepare("UPDATE tournaments SET status=2 WHERE id=?");
        $startQuery->bindValue(1, $this->id);
        $startQuery->execute();

    }
    
    /*
     * Inserts a match into the DB
     */
    private function addMatch($rd, $number, $team1, $team2) {
        $query = $this->db->prepare("INSERT INTO matches (t_id, number, rd, team1_id, team2_id) VALUES (?, ?, ?, ?, ?)");
        $query->bindValue(1, $this->id);
        $query->bindValue(2, $number);
        $query->bindValue(3, $rd);
        $query->bindValue(4, $team1);
        $query->bindValue(5, $team2);
        $query->execute();
    }
}

?>