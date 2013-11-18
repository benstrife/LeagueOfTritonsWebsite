<div id="head_box">
    <img id="banner" src="css/img/banner.jpg"/>
</div>

<?php 
if (isset ($_GET['email'], $_GET['email_code']) === true) {
    $email =trim($_GET['email']);
    $email_code	=trim($_GET['email_code']);

    if ($users->email_exists($email) === false) {
        $errors[] = 'Sorry, we couldn\'t find that email address.';
    } else if ($users->activate($email, $email_code) === false) {
        $errors[] = 'Sorry, we couldn\'t activate your account.  Your account may
            already be activated!';
    }
    echo '<div class="box" style="text-align: center;">';
        if(empty($errors) === false){

        echo '<p>' . implode('</p><p>', $errors) . '</p>';	

        } else {
            
            echo 'Activation Successful! You may now login.';

    }
    echo '</div>';
}
    
    ?>
