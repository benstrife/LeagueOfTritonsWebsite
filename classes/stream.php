<?php

class Stream {
    private $name;
    private $channel;
    
    public function __construct($channel) {
        $this->channel = $channel;
    }
    
    public function welcome() {
        echo '<div class="box">';
        echo "Welcome to " . $this->channel . "'s channel.";
        echo '</div>';
    }
    
    /* Renders the Stream onto the page */
    public function render($width, $height) {
        //Snippet of code stolen from the web to produce Twitch Stream
        echo '<object type="application/x-shockwave-flash" height="' . $height . '" width="' . $width . '" data="http://www.twitch.tv/widgets/live_embed_player.swf?channel=' . $this->channel . '">
        <param name="movie" value="http://www.twitch.tv/widgets/live_embed_player.swf?channel=' . $this->channel . '"></param>
        <param name="allowScriptAccess" value="always"></param><param name="allowNetworking" value="all"></param>
        <param name="allowFullScreen" value="true"></param>
        <param name="flashvars" value="hostname=www.twitch.tv&start_volume=25&auto_play=true&channel=' . $this->channel . '"></param>
        </object>';
    }
}