<?php


if($tournament['status'] != 1) {
    echo '<div class="t_dialog_box"><div class="title">Register a Team</div>
        Registrations are closed at this time.</div>';
    exit();
}

if(!$general->logged_in()) {
    echo '<div class="t_dialog_box">
        <div class="title">Register a Team</div>
        You must be logged in to register a team for this tournament
        </div>';
    exit();
}
else {
    //Figure out if the player is already registered
    $query = $db->prepare("SELECT name FROM teams WHERE captain = ? AND t_id = ?");
    $query->bindValue(1, $user['secret_id']);
    $query->bindValue(2, $tournament['id']);
    $query->execute();
    if($query->rowCount() > 0) {
        echo '<div class="t_dialog_box">
        <div class="title">Register a Team</div>
        You are already registered for this tournament!
        </div>';
        exit();
    }
}


?>

<div id="t_register">
    <div class="tbox">
        <div class="title">Register a Team</div>
        Please enter a team name, and the 4 IDs of your teammates.  Their names and summoners will appear as you correctly
        input their IDs into the slots below.  Once you finish registering, your team will appear in the applicants section.
    </div>

    <form action="#" method="post" id="team_register">
    
    <div class="tbox">
        <div id="team_name">
            <div>Team Name</div>
            <input type="text" name="team"/>
        </div>

        <div id="teammates">
            <div id="team_headers">
                <div>ID</div>
                <div>Name</div>
                <div>Summoner</div>
            </div>
            
            <?php
            
            /* The Player */
            echo '<div class="teammate">';
            
            //Input ID
            echo '<div><input class="id" type="text" name="0" value="' . $user['secret_id'] . '" readonly/></div>';

            echo '<div id="name_0">' . $user['name'] . '</div>';
            echo '<div id="summoner_0">' . $user['summoner'] . '</div>';
            
            echo '</div>';
            
            /* The Team */
            for($i=1; $i<=4; ++$i) {
                echo '<div class="teammate">';
                
                //Input ID
                echo '<div><input class="id" type="text" name="' . $i . '"/></div>';
                
                echo '<div id="name_' . $i . '"></div>';
                echo '<div id="summoner_' . $i . '"></div>';
                
                echo '</div>';
            }
            
            echo '<input type="text" value="' . $tournament['id'] . '" name="t_id" hidden/>';
            
            ?>

            <!--<div id="add_teammate">Add Teammate</div>-->
        </div>
    </div>

    <div class="tbox" style="text-align: center;">
        <input type="submit" value="Register"/>
    </div>
    
    </form>
</div>


<div id="dialog-modal" style="display: none;">
    <p></p>
</div>