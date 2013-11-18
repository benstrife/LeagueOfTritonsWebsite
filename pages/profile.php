<?php

//Page under construction
require('issue.php');
exit();

?>


<?php

//Get the user ID
$user_id = $_GET['user'];
//Grab his data
$query = $db->prepare("SELECT * FROM users WHERE id = ?");
$query->bindValue(1, $user_id);
try{
    $query->execute();

    }catch(PDOException $e){
    die($e->getMessage());
}
$userData = $query->fetch();

$levels = array(
    0,
    10,
    20,
    40
);

$points = $userData['points'];

//Figure out what level the person is
for($i = 1; $i <= count($levels); ++$i) {
    if($points < $levels[$i]) {
        $level = $i;
        //Xp needed for the next level
        $next_xp = $levels[$i];
        break;
    }
}

//Width of the xp bar
$bar_width = $points/$next_xp * 100;

?>

<div id="head_box">
    <img id="banner" src="css/img/banner.jpg"/>
</div>
    
<div id="profile">
    <div id="user_bar">
        <div id="user_info">
            <div id="name">
                <?php echo $userData['name'] . ' - ' . $userData['summoner']; ?>
            </div>
           
        </div>
        <div id="level">
            <?php echo $level; ?>
        </div>
        <div id="xp_bar">
            <div id="filled_bar" style="width: <?php echo $bar_width . '%'; ?>">
                
            </div>
            <div id="bar_value"><?php echo $points . '/' . $next_xp; ?></div>
        </div>
    </div>

    <div id="secret_id">
        <div id="tier">
            Tier<br/>
            Gold
        </div>
        <div id="rank">
            Rank<br/>
            1
            
        </div>
        <div id="points">
            Points<br/>
            123
        </div>
    </div>
    
    <div class="clear">
    </div>
    
    <div id="achievement_bar">
        <?php
        //Max number of achievements to display
        $max_achievements = 10;
            for($i = 0; $i < $max_achievements; ++$i) {
                echo '<div class="achievement">';
                echo '?';
                echo '</div>';
            }
        ?>
        <div class="clear"></div>
    </div>
    
    <div id="recent_match_box">
        Recent Matches
    </div>
</div>