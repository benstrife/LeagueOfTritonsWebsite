<?php

//Grab all the teams
$query = $db->prepare("Select name FROM teams WHERE t_id = ?");
$query->bindValue(1, $tournament['id']);
try{
            $query->execute();
    }catch(PDOException $e){
            die($e->getMessage());
    }

//Grab all the players
$query2 = $db->prepare("Select users.summoner FROM players INNER JOIN users ON players.user_id = users.secret_id WHERE t_id = ? ORDER BY team_id ASC");
$query2->bindValue(1, $tournament['id']);
try{
            $query2->execute();
    }catch(PDOException $e){
            die($e->getMessage());
    }


?>


<div class="tbox">
        <div class="tborder">
            <div id="applicants">
        <?php
       
        //If there is no one...
        if($query->RowCount() <= 0) {
            echo 'There are no registered teams at this time.';
        }
        
        //Produce all of the teams
        for($x=0; $teams = $query->fetch(); ++$x) {
            echo '<div class="team_' . $x%2 . '">';
                //Team Name
                echo '<div class="name">' . $teams['name'] . '</div>';
                //Players
                for($i=0; $i<5; ++$i) {
                    //Fetch the player
                    $players = $query2->fetch();
                    echo '<div class="player">' . ($i+1) . '. '  . $players['summoner']  . '</div>';
                }
            echo '</div>';
        }
       
        ?>
        
        <div class="clear"></div>
        </div>
    </div>
</div>