<?php 

//Stream object
require 'classes/stream.php';

/* This page will either show the streamer that is requested, or a list
 * of all streamers.
 */

if(isset($_GET['stream'])) {
    //Set the stream object
    $stream = new Stream($_GET['stream']);
}

echo '
<div id="head_box">
    <img id="banner" src="css/img/banner.jpg"/>
</div>';

$stream->welcome();
$stream->render("1025", "800");


?>