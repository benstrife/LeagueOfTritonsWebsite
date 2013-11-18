<?php

//Page under construction
//require('issue.php');
//exit();

?>

<div id="head_box">
    <img id="banner" src="css/img/banner.jpg"/>
</div>
<div class="box">
    <div class="box_title">Frequently Asked Questions</div>
    Got a question regarding the League of Tritons? If it is a common question you can probably find the answer below!

    <div id="faq">
    <?php
    
    $query = $db->prepare("SELECT * FROM faq");
    $query->execute();
    
    for($i=1; $faqData = $query->fetch(); ++$i) {
        echo '<div class="question">> ' . $faqData['question'];
        echo '<div class="answer">' . $faqData['answer'] . '</div></div>';
    }
    
    ?>
    </div>
</div>