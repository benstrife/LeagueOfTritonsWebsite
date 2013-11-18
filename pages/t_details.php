<?php

$status[0] = "<span style='color: green;'>Registration Pending</span>";
$status[1] = "<span style='color: green;'>Registration Open</span>";
$status[2] = "<span style='color: yellow;'>In Progress</span>";
$status[3] = "<span style='color: red;'>Completed</span>";


//Stream object
require 'classes/stream.php';
$stream = new stream("SoKorean");

?>

<div id="thome">
    <!-- Content to the Left -->
    <div id="thome_left">
        <div class="home_box">
            <div class="tborder">
                <div id="t_name"><?php echo $tournament['name']; ?>
                    <div>Starts: <?php echo date("F jS, o", strtotime($tournament['date'])); ?></div> 
                    <div>Status: <?php echo $status[$tournament['status']]; ?></div>
                    <div>Admins: <?php echo $tournament['admins']; ?></div>
                </div>
                <?php echo $tournament['summary']; ?>
            </div>
        </div>
        
        <div class="home_box">
            <div class="tborder">
                <div class="title">Rules</div>
                <?php echo $tournament['rules']; ?>
            </div>
        </div>
        
        <div class="home_box">
            <div class="tborder">
                <div class="title">Prizes</div>
                <?php echo $tournament['prizes']; ?>
            </div>
        </div>
        
        <?php
        if($general->logged_in()) {
            if($user['access'] > 900) {
                echo '<div class="tbox">
                    <a href="?page=t_index&tpage=admin&tid=' . $tournament['id'] . '">Administrate</a>
                </div>';
            }
        }
        ?>
    </div>
    <!-- Content to the right -->
    <div id="thome_right">
        <div class="home_box">
            <div class="tborder">
                <?php
                    $stream->render("392", "350");
                ?>
            </div>
        </div>
    </div>

    <div class="clear"></div>
</div>