<?php

$picture = array( 
    "com_1.jpg",
    "choco.jpg",
    "com_2.jpg"
);

//List of Streamers to grab data of
/*
$streamList = array(
    'sokorean',
    'streamerhouse',
    'itrinitron',
    'voyboy',
);*/

$stream_list = "";

//Get current time
$currentTime = new DateTime(date('Y-m-d H:i:s'));
//Add 5 minutes
$currentTime->sub(new DateInterval('PT' . 5 . 'M'));
//Set it as the check time
$checkTime = $currentTime->format('Y-m-d H:i');

//Max number of streams
$max_streams = 6;

//Check the database
$query = $db->prepare("SELECT * FROM streams ORDER BY timestamp LIMIT $max_streams");
$query->execute();

$streamData = $query->fetch();

//Does not
if($streamData['timestamp'] > $checkTime) {
    $i=0;
    do {
        $stream[$i]['img'] = $streamData['img'];
        $stream[$i]['count'] = $streamData['count'];
        $stream[$i]['name'] = $streamData['name'];
        $i++;
    } while($streamData = $query->fetch());
}
//Needs Updating
else {
    $i = 0;
    do {
        $streamList[$i] = $streamData['name'];
        $i++;
    } while($streamData = $query->fetch());
    
    //Put it into readable code for the website
    foreach($streamList as $i) {
        $stream_list = $stream_list . strtolower($i) . ",";
    }
    //Send in the request
    $mycurl = curl_init();
    curl_setopt ($mycurl, CURLOPT_HEADER, 0);
    curl_setopt ($mycurl, CURLOPT_RETURNTRANSFER, 1); 
    //Build the URL 
    $url = "http://api.justin.tv/api/stream/list.json?channel=" . $stream_list; 
    curl_setopt ($mycurl, CURLOPT_URL, $url);
    $web_response =  curl_exec($mycurl); 
    $results = json_decode($web_response); 
    
    //Save the number of streams
    $num_streams = count($streamList);
    
    /* Give the data to the webpage */
    //Print out all online streams
    for($i=0; $i < count($results); ++$i) {
        $stream[$i]['img'] = $results[$i]->channel->screen_cap_url_small;
        $stream[$i]['count'] = $results[$i]->channel_count;
        
        $index = array_search($results[$i]->channel->login, array_map('strtolower', $streamList)); 
        $temp = $streamList[$index];
        $streamList[$index] = $streamList[$i];
        $streamList[$i] = $temp;
        
        //Name
        $stream[$i]['name'] = $temp;
        //Swap $i with the key where this value is
        //
        //$streamList = array_diff($streamList, array($stream[$i]['name']));
    }
    //Print out the rest
    for(; $i < count($streamList); ++$i) {
        $stream[$i]['img'] = "css/img/offline.png";
        $stream[$i]['count'] = 0;
        $stream[$i]['name'] = $streamList[$i];
    }
    
    //Save the data to the database
    for($i=0; $i<$num_streams; ++$i) {
        $query = $db->prepare("UPDATE streams SET img = ?, count = ?, timestamp = ? WHERE name = ? ");
        $query->bindValue(1, $stream[$i]['img']);
        $query->bindValue(2, $stream[$i]['count']);
        $query->bindValue(3, date('Y-m-d H:i:s'));
        $query->bindValue(4, $stream[$i]['name']);
        $query->execute();
    }
}

//Save the number of streams
$num_streams = $i;
//Number of pictures
$NUM_PICTURES = 3;

?>

<!-- Three Pictures -->
<div id="picture_container">
    <?php
    //Display the three pictures
    for($i = 0; $i < $NUM_PICTURES; ++$i) {
        if($i == 0) {
            echo '<div id="first_picture_box">';
        }
        else {
            echo '<div class="com_picture_box">';
        }

        echo '<img src="css/img/community/' . $picture[$i] . '" class="com_picture"/>';
        echo '</div>';
    }
    ?>
</div>

<!-- Start Home -->
<div id="home">
    <div id="left">
        <div id="navbar">
            <ul>
                <li>Member List</li>
                <li>Triton Store</li>
                <li>Pictures</li>
            </ul>
        </div>
        <div id="stream_list">
            <div id="title">Featured Streamers</div>
            <?php
            
            //Sort the streams in order of count
            usort($stream, function($a, $b) {
                return $b['count'] - $a['count'];
            });
            
            //Print out the streams
            for($i=0; $i<$num_streams; ++$i) {
                echo '<a href="http://www.twitch.tv/' . $stream[$i]['name'] . '"><div class="stream">';
                echo '<div id="stream_img"><img src="' . $stream[$i]['img'] . '"/></div>';
                echo '<div id="stream_name">' . $stream[$i]['name'];
                //Add appropriate tags
                if($stream[$i]['count'] == 0) {
                    $stream[$i]['count'] = 'offline';
                }
                else {
                    $stream[$i]['count'] = $stream[$i]['count'] . ' viewers';

                }
                echo '<div>' . $stream[$i]['count'] . '</div></div>';

                echo '</div></a>';
            }
            
            ?>
        </div>
    </div>
    <div id="middle">

    <?php

    //Grab 3 Recent Events AND tournaments
    $query = $db->prepare("SELECT * FROM events ORDER BY id DESC LIMIT 5");
    try{
        $query->execute();

        }catch(PDOException $e){
        die($e->getMessage());
    }	

    //Print them out
    for($i=0; $i<5 && $i<$query->rowCount(); ++$i) {
        $events = $query->fetch();
        
        $datetime = strtotime($events['date']);

        echo '<div class="article">';
        
        //If the location is an event..link to the facebook
        if(filter_var($events['link'], FILTER_VALIDATE_URL)) {
            $event_link = '<a href="' . $events['link'] . '">';
        }
        //If the location is a tournament..link to the tournament page
        else {
            $event_link = '<a href="?page=t_index&tid=' . $events['link'] . '">';
        }
        
        echo $event_link . '<div class="article_img"><img src="css/img/event/' . $events['img'] . '"/></div></a>';
        echo '<div class="article_info">';

        //Event Title
        echo $event_link . '<div class="article_title">' . $events['title'] . '</div></a>';
        //Event Date
        echo '<div class="article_date">' . date("l", $datetime) . ' | ' . date("F jS, o", $datetime) . ' | ' . date("g:ia", $datetime) . ' - <span>' . $events['location'] . '</span></div>';
        //Event Summary
        echo '<div class="article_summary">' . $events['summary'] . '</div>';

        echo '</div>
                </div>';
        
    }
    
    ?>
        <!-- Link to all news -->
        <div id="past_news">Past News</div>
        <div class="clear"></div>
    </div>
</div>

<?php


?>