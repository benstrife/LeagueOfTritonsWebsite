
<div id="compete">
    <div id="left">
        <div id="tournament_list">
            <div id="headers">
                <div class="date">Date</div>
                <div class="name">Tournament</div>
                <div class="status">Status</div>
            </div>
            
            
            
            <?php

            $query = $db->prepare("SELECT * FROM tournaments ORDER BY date DESC");
            $query->execute();
            
            $status[0] = "<span style='color: green;'>Registration Pending</span>";
            $status[1] = "<span style='color: green;'>Registration Open</span>";
            $status[2] = "<span style='color: yellow;'>In Progress</span>";
            $status[3] = "<span style='color: red;'>Completed</span>";

            for($i=0; $i<5 && $i<$query->RowCount(); $i++) {
                $tournament = $query->fetch();
               
                
                echo '<a href="?page=t_index&tid=' . $tournament['id']  . '"><div class="tournament">';
                    echo '<div class="date">'; if(isset($tournament['date'])) { echo date("F jS, o", strtotime($tournament['date'])); } echo '</div>';
                    echo '<div class="name">' . $tournament['name'] . '</div>';
                    echo '<div class="status">';
                    if(isset($tournament['status'])) {
                        echo $status[$tournament['status']];
                        }
                    echo '</div>';
                echo '</div></a>';
            }

            ?>
        </div>
    </div>
    <div id="right">
        <a href="?page=ucsd_team">
        <div id="official_team">
            <!--<img src="css/img/ucsd_logo.png"/>-->
            UCSD Team Logo

        </div>
        </a>
    </div>
    <div class="clear"></div>
</div>

<div class="box">
    <div id="ladder">
        <div id="headers">
                <div class="rank">#</div>
                <div class="summoner">Summoner</div>
                <div class="college">College</div>
                <div class="points">Points</div>
        </div>
        <div id="body">
            <?php

            $ladderQuery = $db->prepare("SELECT ladder_sum.total, users.summoner, users.college FROM `ladder_sum` 
                INNER JOIN `users` ON ladder_sum.user_id=users.secret_id ORDER BY ladder_sum.total DESC LIMIT 20");
            //$ladderQuery->bindValue(1, $ladder_size);
            $ladderQuery->execute();
            
            for($i=1; $player = $ladderQuery->fetch(); ++$i) {
                $college = "-";
                if($player['college'] != NULL) {
                    $college = $player['college'];
                }
                
                
                if($i%2 == 0) {
                    echo '<div class="player_1">';
                }
                else {
                    echo '<div class="player_2">';
                }
                
                echo '<div class="rank">' . $i . '</div>';
                echo '<div class="summoner">' . $player['summoner'] . '</div>';
                echo '<div class="college">' . $college . '</div>';
                echo '<div class="points">' . $player['total'] . '</div>';
                
                echo '</div>';
            }

            ?>
        </div>
        <div class="clear"></div>
    </div>
</div>
