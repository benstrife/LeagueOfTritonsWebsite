<?php

$event_id = -1;
if(isset($_GET['event'])) {
    //The current page displayed
    $event_id = $_GET['event'];
}

function event_does_not_exist() {
    echo '<div class="box">The Event you are looking for does not exist.</div>';
}


if($event_id != -1) {
    //Grab the event
    $query = $db->prepare("SELECT * FROM events WHERE id = ?");
    $query->bindValue(1, $event_id);
    try{
        $query->execute();

        }catch(PDOException $e){
        die($e->getMessage());
    }
    if($eventData = $query->fetch()) {
        $event['img'] = $eventData['img'];
        $event['title'] = $eventData['title'];
        $datetime = strtotime($eventData['date']);
        $event['location'] = $eventData['location'];
    }
    else {
        event_does_not_exist();
    }
}
else {
    event_does_not_exist();
}



?>

<div id="head_box">
    <img id="banner" src="css/img/banner.jpg"/>
</div>

<div id="event">
    <div class="box">
        <div class="event_title"><?php echo $event['title']; ?></div>
        <?php echo '<div class="article_date">' . date("l", $datetime) . ' | ' . date("F jS, o", $datetime) . ' | ' . date("g:ia", $datetime) . ' - <span>' . $event['location'] . '</span></div>'; ?>
        <div class="event_img">
            <img src="css/img/event/<?php echo $event['img']; ?>">
        </div>
        <div id="event_info">
            
        </div>
        <div class="clear"></div>
        <div id="event_details">
            <div id="summary">
We'll be setting up a large viewing screen for the match exhibition! The forum meeting room seats 150 people maximum, so we should have enough chairs for everybody, theatre style!
            </div>
            
            <div class="head_detail">Raffle</div>
<div class="detail">
We're going to be raffling away some $10 RP cards and other League of Legends swag. All members will get 1 raffle ticket for showing up. During the matches, we'll be holding votes for which team will win. Picking the correct team will net you an extra raffle ticket!
</div>
<div class="head_detail">Food</div>
<div class="detail">
We're also going to be serving some munchies and drinks! More details as the date gets closer.
</div>
<div class="head_detail">Free Play</div>
<div class="detail">
We'll be setting up some free-play tables in the back for members to mingle and play casual games during downtimes. Bring your own peripherals if you plan to play!
</div>

        </div>
    </div>
</div>