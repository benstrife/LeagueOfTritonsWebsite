<?php

//Display nothing if the tournament has already started
if($tournament['status'] <= 1) {
    echo '<div class="t_dialog_box"><div class="title">Bracket</div>Tournament has not started.</div>';
    exit();
}

?>

<?php

//Grab all the teams
$teamQuery = $db->prepare("SELECT id, name FROM `teams` WHERE `t_id` = ? ORDER BY id ASC");
$teamQuery->bindValue(1, $tournament['id']);
$teamQuery->execute();

//Populate the array with the teams data
while($teamData = $teamQuery->fetch()) {
    //Team[id][name]
    $team[$teamData['id']]['name'] = $teamData['name'];
}

//Set the ID 0 to bye/none
$team[0]['name'] = 'Bye';
//Set the Id -1 to empty
$team[-1]['name'] = '&nbsp';

//Create the group stage?
if($matches = $tournamentObj->getGroupStage()) {
    echo '<div id="group_stage">';
    //for each group
    for($i=0, $a='A'; $i<count($matches); ++$i) {
        
        echo '<div class="group_' . $i%2 . '">';
        echo '<div class="border">';
        echo '<div class="name">Group ' . $a++ . '</div>';
        
        echo '<div class="group_matches">';
        //for each match
        for($x=0; $x<count($matches[-$i]); ++$x) {
            echo '<div class="match">';
            echo '<div class="team1"';
            if($matches[-$i][$x]['winner'] == 1) {
                echo ' style="background: green; font-weight: bold;"';
            }
            echo '>';
            echo        $team[$matches[-$i][$x]['team1_id']]['name'];
            echo '</div>';
            echo '<div class="score">' . $matches[-$i][$x]['team1_score'] . '-' . $matches[-$i][$x]['team2_score'] .'</div>';
            echo '<div class="team2"';
            if($matches[-$i][$x]['winner'] == 2) {
                echo ' style="background: green; font-weight: bold;"';
            }
            echo '>';
            echo $team[$matches[-$i][$x]['team2_id']]['name'] . '</div>';    
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
}

    

?>


<div class="clear"></div>
<?php

//Grab all the matches to display
$bracketMatchesQuery = $db->prepare("SELECT * FROM `matches` WHERE `t_id` = ? AND rd > 0");
$bracketMatchesQuery->bindValue(1, $tournament['id']);
$bracketMatchesQuery->execute();

//Only load if the bracket exists
if($bracketMatchesQuery->RowCount() <= 0) {
    exit();
}

//Number of matches in the tournament
$num_matches = $bracketMatchesQuery->rowCount();
for($i=0, $x=0; $i<$num_matches; ++$i, ++$x) {
    $match = $bracketMatchesQuery->fetch();
    
    $matchResult[$i] = $match['winner'];
    
    //Insert the two teams ID
    $team_id[$x] = $match['team1_id'];
    $matchScore[$x] = $match['team1_score'];
    
    $team_id[++$x] = $match['team2_id'];
    $matchScore[$x] = $match['team2_score'];
    
    if($i == ($num_matches-1)) {
        $numRounds = $match['rd'];
    }
}


    //This is the height of a match box
    $match_height = 50;
    //The first margin at the top is Half of match size - borders(3)
    $first_margin_top = floor($match_height/2);
    //Width of each round
    $round_width = floor(100/$numRounds) . '%';
    //Font size
    $font_size = $round_width/3 . 'px';
    //Size of a team rectangle
    $team_size = $match_height/2 - 1 . 'px';
    

?>



<style type="text/css">
    #bracket {
        width: 100%;
        font-family: "TradeCn",Helvetica,Arial,sans-serif;
        overflow: auto;
    }
    
    /* All the teams vertically (one round) */
    #bracket .round {
        float: left;
        width: <?php echo $round_width; ?>;
        font-size: 10px;
        min-height: 100px;
    }
    
    /* Pair of two teams adjacent to each other */
    #bracket .pair {
        float: left;
        width: 80%;
    }
    #bracket .pair .name {
        width: 89%;
        float: left;
        padding-left: 5%;
        text-align: center;
    }
    #bracket .pair .score {
        width: 6%;
        border-left: 1px solid black;
        box-sizing: border-box;
        text-align: center;
        float: right;
        background: #1F3043;
        color: #aaa;
        height: <?php echo $team_size ?> ;
    }
    #bracket .top_match, #bracket .bot_match {
        position: relative;
        background: black;
        border: 1px solid black;
        width: 100%;
        height: <?php echo $match_height . 'px'; ?> ;
        color: black;
        text-align: center;
        box-sizing: border-box;
    }
    
    #bracket .teamBot {
        margin-top: 40px;
    }
    
    .team_top, .team_bot {
        border-bottom: 1px solid black;
        height: <?php echo $team_size ?> ;
        line-height: <?php echo $team_size ?> ;
        
        background: #598CC3;
    }
    /* Winning Team Swag */
    #bracket .pair .win {
        font-weight: bold;
        display: block;
        width: 100%;
        background: green;
    }
    .team_bot {
        border: 0px;
    }
    
    #bracket .connector {
        width: 20%;
        margin-top: <?php echo $first_margin_top . 'px'; ?>;
        min-height: 73px;
        box-sizing: border-box;
        border: 2px solid #4d7aaa;
        border-left: 0px;
        float: left;
    }
    
    
    .spacer {
        width: 100%;
        clear: both;
    }
    
    .round_headers {
        width: 100%;
        height: 30px;
        line-height: 30px;
    }
    .round_headers div {
        float: left;
        width: <?php echo $round_width; ?>;
        text-align: center;
    }
    
</style>

<?php
  
    
    $topSpace = 0;

?>

<?php

//Group Stages


?>


<div class="tbox">
    <div class="tborder">
    <div id="tBracket">
        <div id="bracket">
            <div class="round_headers">
                <?php
                for($i=0; $i<$numRounds; ++$i) {
                    echo '<div>';
                    switch($numRounds-$i) {
                        case 1: 
                            echo 'Finals';
                            break;
                        case 2:
                            echo 'Semifinals';
                            break;
                        case 3: 
                            echo 'Quarterfinals';
                            break;
                        default:
                            echo 'Round ' . ($i+1);
                            break;
                    }
                    echo '</div>';
                }
                ?>
                 <div class="clear"></div>
            </div>
           
            
            <?php
            /*
            * This for loop creates the tournament bracket round by round, match 
            * by match.
            */
            for($i = 0, $a=0, $totalMatch=0, $round=1; $round<=$numRounds; ++$round) {
                //Reduce the number of matches, since we are creating $num_matches many
                $num_matches = ceil($num_matches/2);

                /* Figure out the dimensions of each div */
                //Base case Round 1
                if($round == 1) {
                    //Space between pairs of matches
                    $space = $match_height/2;
                    //Space between two matches
                    $match_margin_bot  = $match_height + 10;
                    //Size of Match * 2 + Half of Match - 4(border)
                    $connect = $match_height + $match_margin_bot + 2;
                }
                //All other cases
                else {
                    //Uses the old Y so must be calculated first
                    $topSpace = $topSpace + $match_height + floor(($match_margin_bot-$match_height)/2);
                    //Uses the old space so must be calculated before new space is found
                    $tempY = $match_margin_bot ;
                    $match_margin_bot = 2*$match_height + $space + ($match_margin_bot-$match_height);
                    //Now space can be changed
                    $space = 2*$match_height + $space + ($tempY-$match_height);
                    $connect = $match_margin_bot  + $match_height + 5;
                }

                //The round wrapper
                echo '<div class="round">';

                //After the first round a spacer is inserted here at the top
                if($round != 1) {
                    echo '<div class="spacer" style="min-height: ' . $topSpace . 'px"></div>';
                }
                    
                /* Create all of the matches for a round */
                for($match=0; $match<$num_matches;) {
                    //If last round, no margin-bottom is needed
                    if($round == $numRounds) {
                        $match_margin_bot = 0;
                    }

                    //Create a pair of matches
                    echo '<div class="pair">';
                        //Create a match
                            echo '<div class="top_match" style="margin-bottom:' . $match_margin_bot  . 'px">';
                                echo       '<div class="team_top">';
                                if($matchResult[$totalMatch] == 1) {
                                    echo '<div class="win">';
                                }
                                echo '<div class="name">' . $team[$team_id[$i++]]['name'] . '</div><div class="score">' . $matchScore[$a++] . '</div><div class="clear"></div>'; 
                                if($matchResult[$totalMatch] == 1) {
                                    echo '</div>';
                                }
                                echo '</div>';
                                echo       '<div class="team_bot">';
                                if($matchResult[$totalMatch] == 2) {
                                    echo '<div class="win">';
                                }
                                echo '<div class="name">' . $team[$team_id[$i++]]['name'] . '</div><div class="score">' . $matchScore[$a++] . '</div><div class="clear"></div>'; 
                                if($matchResult[$totalMatch] == 2) {
                                    echo '</div>';
                                }
                                echo '</div>';
                            echo '</div>';
                        //echo '</a>';

                    //Increment match counter as we create them
                    $match++;
                    $totalMatch++;

                    //If Last round, it is just the finals match
                    if($round == $numRounds) {
                        echo '</div>';
                    }
                    //Else create the opposing match
                    else {
                        //Create a match
                        //echo '<a href="?page=t_index&tpage=match&tid=' . $t_id . '&rd=' . $round . '&num=' . $match . '">';
                        echo '   <div class="bot_match">';
                        echo       '<div class="team_top">';
                                    if($matchResult[$totalMatch] == 1) {
                                    echo '<div class="win">';
                                }
                                echo '<div class="name">' . $team[$team_id[$i++]]['name'] . '</div><div class="score">' . $matchScore[$a++] . '</div><div class="clear"></div>'; 
                                if($matchResult[$totalMatch] == 1) {
                                    echo '</div>';
                                }
                                    echo '</div>';
                                    echo       '<div class="team_bot">';
                                    if($matchResult[$totalMatch] == 2) {
                                    echo '<div class="win">';
                                }
                                echo '<div class="name">' . $team[$team_id[$i++]]['name'] . '</div><div class="score">' . $matchScore[$a++] . '</div><div class="clear"></div>'; 
                                if($matchResult[$totalMatch] == 2) {
                                    echo '</div>';
                                }
                                    echo '</div>';
                        echo '   </div>';
                        //echo '</a>';

                        echo '</div>';
                        echo '<div class="connector" style="min-height: ' . $connect . 'px;">

                        </div>';

                        //Increment match counter as we create them
                        $match++;
                        $totalMatch++;

                        //If not the last pair of matches, put a spacer
                        if($match != ($num_matches)) {
                            echo '<div class="spacer" style="min-height: ' . $space . '"px></div>';
                        }
                    }
                }
                echo '</div>';
            }

            ?>
            <div class="clear"></div>
        </div>
    </div>
    </div>
</div>

