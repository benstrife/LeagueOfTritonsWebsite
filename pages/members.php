<div class="box">
    <div id="member">
        <div id="member_headers">
            <div class="name">Name</div>
            <div class="summoner">Summoner</div>
            <div class="college">College</div>
            <div class="points">Points</div>
        </div>
        
        <?php 
        for($i = 0; $i < 50; ++$i) {
            echo '<div class="member">';
                echo '<div class="name">Michael Chin</div>';
                echo '<div class="summoner">Proto55</div>';
                echo '<div class="college">Muir</div>';
                echo '<div class="points">55</div>';
            echo '</div>';
        }
        ?>
    </div>
</div>