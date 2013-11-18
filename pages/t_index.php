<?php
//Import the tournament classse
require('classes/tournament.php');

//Parse the GET requests
if(isset($_GET['tid'])) {
    $tpage = "details";
    if(isset($_GET['tpage'])) {
        $tpage = $_GET['tpage'];
    }
}
else {
    "<div class='box'>The page you are looking for does not exist</div>";
    exit();
}

//Initialize the new tournament object, exit() if it cannot be created
$tournamentObj = new Tournament($db, $_GET['tid'], $tpage);
//Fetch the tournaments data
$tournament = $tournamentObj->tournamentData();
?>

<div id="head_box">
    <div id="tbackground">
        <div id="tname"><?php echo $tournament['name']; ?></div>
        <div id="t_navbar">
            <?php 
    
            //Generate the navbar; in reverse order
            $pages = $tournamentObj->get_navbar();
            for($i=0; $i<count($pages); ++$i) {
                echo '<a href="?page=t_index&tpage=' . $pages[$i] . '&tid=' . $tournament['id'] . '"><div class="link">' . $pages[$i] . '</div></a>';
            }


            ?>
        </div>
    </div>
</div>

<!--
<div id="t_navbar">
    <?php 
    /*
    //Generate the navbar
    $pages = $tournamentObj->get_navbar();
    for($i=0; $i<count($pages); ++$i) {
        echo '<a href="?page=t_index&tpage=' . $pages[$i] . '&tid=' . $tournament['id'] . '"><div class="link">' . $pages[$i] . '</div></a>';
    }
     * */
     
    ?>
</div>
-->

<?php
//Looks for the pages in the same directory with the prefix "t_"
include('t_' . $tournamentObj->page . '.php'); 
?>
