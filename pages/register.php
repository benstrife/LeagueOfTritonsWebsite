<div id="head_box">
    <img id="banner" src="css/img/banner.jpg"/>
</div>

<div class="box" style="text-align: center;">
    <div class="box_title">Register an Account</div>
        <div id="register">
            <form id="signup_form" action="#" method="post">
                <div>Email <span class="warning">*Must be a UCSD email</span></div>
                <input type="text" name="email">
                
                <div>Password</div>
                <input type="password" name="password">
                
                <div>Verify Password</div>
                <input type="password" name="verify_password">
                
                <div class="section_title">Basic Information</div>
                
                <div>First/Last Name</div>
                <input type="text" name="name">
                
                <div>Summoner Name</div>
                <input type="text" name="summoner">
                
                
                <div class="section_title">Other</div>
                <div>Promotional Code <span class="optional">*optional</span></div>
                <input type="text" name="promo">

                <br/>
                <input id="submit" type="submit">
            </form>
        </div>
</div>

<!-- Pop-up Box -->
<div id="dialog-modal" style="display: none;">
    <p></p>
</div>