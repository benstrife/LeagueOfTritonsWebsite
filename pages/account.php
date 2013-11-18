<?php 

$colleges = array(
    "None",
    "Muir",
    "Warren",
    "Revelle",
    "Sixth",
    "Marshall",
    "ERC"
);

?>
<div id="head_box">
    <img id="banner" src="css/img/banner.jpg"/>
</div>

<div id="account">
    <div class="box">
        <div id="secret_id">
            <div class="box_center_title">Secret ID</div>
        <?php echo $user['secret_id']; ?>
        </div>
    </div>

    <div class="box">
        <div class="box_center_title" style="margin-bottom: 5px;">Account Information</div>
        <form id="update_profile" action="#" method="post" style="text-align: center;">
            <div>Summoner Name</div>
            <input value="<?php echo $user['summoner']; ?>" type="text" name="summoner">
            
            <div>First/Last Name</div>
            <input value="<?php echo $user['name']; ?>" type="text" name="name">

            <div>Email <span class="warning">*locked</span></div>
            <input value="<?php echo $user['email']; ?>" type="text" name="email" disabled>

            <div>College</div>
            <select name="college">
                <?php
                $college = $user['college'];

                $colleges = array(
                    "N/A",
                    "Muir",
                    "Revelle",
                    "Warren",
                    "Sixth",
                    "Marshall",
                    "ERC"
                );
                
                //Have the one that user is using displayed
                echo '<option value="' . $college . '" selected="selected">' . $college . '</option>';
                $index = array_search($college, $colleges); 
                $colleges[$index] = $colleges[0];
                $colleges[0] = $college;
                
                for($i=1; $i<count($colleges); ++$i) {
                    echo '<option value="' . $colleges[$i] . '">' . $colleges[$i] . '</option>';
                }
                
                ?>
            </select>
            
            <div>T-Shirt Size</div>
            <select name="shirt_size">
                <?php
                $shirt_size = $user['shirt_size'];

                $shirt_sizes = array(
                    "S",
                    "M",
                    "L",
                    "XL"
                );
                
                //Have the one that user is using displayed
                echo '<option value="' . $shirt_size . '" selected="selected">' . $shirt_size . '</option>';
                $index = array_search($shirt_size, $shirt_sizes); 
                $shirt_sizes[$index] = $shirt_sizes[0];
                $shirt_sizes[0] = $shirt_size;
                
                for($i=1; $i<count($shirt_sizes); ++$i) {
                    echo '<option value="' . $shirt_sizes[$i] . '">' . $shirt_sizes[$i] . '</option>';
                }
                
                ?>
            </select>
            <br/>
            <br/>
            <input type="submit" value="Update"/>
        </form>
    </div>
    
    <div class="box" style="text-align: center;">
        <div class="box_center_title">Tespa Membership <a href="?page=tespa"><span class="question_icon">?</span></a></div>
        <?php
        
        if($user['tespa'] == 0) {
            echo '
        
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <table>
        <tr><td><input type="hidden" name="on0" value="2013-2014 LoT Membership"><input type="hidden" name="os0" maxlength="200" value="' . $user['secret_id'] . '">
        </table>
        <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
        ">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
        <div class="warning">After you have paid, your account will be updated within 24 hours of your purchase.</div>';
        }
        else {
            echo "You are a registered TESPA Member."; 
        }
        ?>
    </div>
</div>

<div id="dialog-modal" style="display: none;">
    <p></p>
</div>