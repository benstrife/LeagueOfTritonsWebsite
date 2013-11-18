//Documentation: http://malsup.com/jquery/form/

// Prepare everything here after the DOM loads
$(document).ready(function() { 
    //Validate the form: check if all of the fields are 
    //$('#register_form').ajaxForm( { beforeSubmit: validate } ); 
   
    var type = "post";
    
    var options = { 
        //target:        '#output2'   // target element(s) to be updated with server response 
        //beforeSubmit:  validate,  // pre-submit callback 
        //success:       alert("hi",  // post-submit callback 
 
        // other available options: 
        //url:  url,        // override for form's 'action' attribute 
        type: type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
    /* FUNCTIONS */
 
    // bind to the form's submit event 
    $('#login_form').submit(function() { 
        options['url'] = "login.php";
        options['success'] = function(responseText) {
            if(responseText == true) {
                window.location = 'index.php';
            }
            else {
                $( "#dialog-modal" ).html(responseText);
                $( "#dialog-modal" ).dialog({
                    draggable: false,
                    resizable: false,
                    modal: true,
                    width: 400,
                    title: "Login Unsuccessful"
                });;
                }
            }
        
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 
        $(this).ajaxSubmit(options); 
 
        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    }); 
    
    // Signup Function
    $('#signup_form').submit(function() { 
        options['url'] = "register.php";
        options['success'] = function(responseText) {
            if(responseText == true) {
                $( "#dialog-modal" ).html("A verification letter has been delivered to your email.  Please activate your account to begin using it!");
                $( "#dialog-modal" ).dialog({
                    draggable: false,
                    resizable: false,
                    modal: true,
                    width: 400,
                    title: "Registration Successful",
                    close: function() {
                        window.location = 'index.php';
                    }
                });;
            }
            else {
                $( "#dialog-modal" ).html(responseText);
                $( "#dialog-modal" ).dialog({
                    draggable: false,
                    resizable: false,
                    modal: true,
                    width: 400,
                    title: "Error Registering"
                });;
                }
        }
        $(this).ajaxSubmit(options); 
        return false; 
    });

    
    /* Logout Function */
    $("#logout").click(function() {
      $.ajax({url: 'logout.php', 
            success: function() {
            window.location = 'index.php';}
        });
    });
    
    /* Update Profile Function */
    $("#update_profile").submit(function() {
        options['url'] = "update_profile.php";
        options['success'] = function(responseText) {
            if(responseText == true) {
                $( "#dialog-modal" ).html("Your account has successfully been updated");
                $( "#dialog-modal" ).dialog({
                    draggable: false,
                    resizable: false,
                    modal: true,
                    width: 400,
                    title: "Account Updated"
                });;
            }
            else {
                $( "#dialog-modal" ).html(responseText);
                $( "#dialog-modal" ).dialog({
                    draggable: false,
                    resizable: false,
                    modal: true,
                    width: 400,
                    title: "Account Update Failed"
                });;
                }
        }
        $(this).ajaxSubmit(options); 
        return false;
    });
    
    /* FUNCTIONS: Team Registration on Tournament ------------------*/
    /* After an ID is entered, the user is found */
    $("#teammates .id").keyup(function() {
        var value = $(this).val();
        var id = $(this).attr("name");
        //The length of a secret ID is 7.
        if(value.length == 7) {
            $.ajax({
                url: 'php/find_user_by_id.php', 
                success: function(text) {
                    //Set the users data
                    if(text != false) {
                        var data = text.split(",");
                        $("#name_" + id).html(data[0]);
                        $("#summoner_" + id).html(data[1]);
                    }
                    //Make them empty
                    else {
                        $("#name_" + id).html(" ");
                        $("#summoner_" + id).html(" ");
                    }
                },
                data: { id: value }
            });
        }
    });
    
    //Register Team
    $('#team_register').submit(function() {
        options['url'] = "php/register_team.php";
        options['success'] = function(responseText) {
            if(responseText == true) {
                $( "#dialog-modal" ).html("Your team has been registered! Good luck.");
                $( "#dialog-modal" ).dialog({
                    draggable: false,
                    resizable: false,
                    modal: true,
                    width: 400,
                    title: "Team Registered",
                    close: function() {
                        window.location = 'index.php';
                    }
                });;
            }
            else {
                $( "#dialog-modal" ).html(responseText);
                $( "#dialog-modal" ).dialog({
                    draggable: false,
                    resizable: false,
                    modal: true,
                    width: 400,
                    title: "Error Registering Team"
                });;
                }
        }
        
        $(this).ajaxSubmit(options); 
        return false; 
    });
    
    //Toggles FAQ Page
    $('.question').click(function() {
        //Hide all
        $(".answer").css("display", "none");
        //Open the one selected
        $(this).children(".answer").css("display", "block");
    });
    
    /* Logout Function */
    $("#create_bracket").click(function() {
        //Get the ID of the tourny, by the class name stored in the div
        var id = $(this).attr("class");
        $.ajax({url: 'php/create_bracket.php',
            type: "POST",
            data: "id=" + id,
            success: function() {
            window.location = '?page=t_index&tpage=bracket&tid=' + id;}
        });
    });
});



/* Returns the information of a user by ID */
function find_user_by_id() {
    alert($(this).name);
    
}

/* Makes sure a form has all fields filled */
function validate(formData, jqForm, options) { 
    // formData is an array of objects representing the name and value of each field 
    // that will be sent to the server;  it takes the following form: 
    // 
    // [ 
    //     { name:  username, value: valueOfUsernameInput }, 
    //     { name:  password, value: valueOfPasswordInput } 
    // ] 
    // 
    // To validate, we can examine the contents of this array to see if the 
    // username and password fields have values.  If either value evaluates 
    // to false then we return false from this method. 
    for (var i=0; i < formData.length; i++) { 
        if (!formData[i].value) { 
            alert('A field is empty!');
            return false; 
        } 
    }
    return true;
}

// post-submit callback 
function warningRedirect(responseText, statusText, xhr, $form)  { 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
 
    // if the ajaxSubmit method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 
 
    // if the ajaxSubmit method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 
 
    if(responseText == true) {
        window.location = 'index.php';
    }
    else {
        alert(responseText);
    }
} 

// post-submit callback 
function warning(responseText, statusText, xhr, $form)  { 
    alert(responseText); 
} 

/* Integrate this with the validate function*/
// pre-submit callback 
function showRequest(formData, jqForm, options) { 
    // formData is an array; here we use $.param to convert it to a string to display it 
    // but the form plugin does this for you automatically when it submits the data 
    var queryString = $.param(formData); 
 
    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
    // var formElement = jqForm[0]; 
 
    alert('About to submit: \n\n' + queryString); 
 
    // here we could return false to prevent the form from being submitted; 
    // returning anything other than false will allow the form submit to continue 
    return true; 
} 

//Add in call from logout button to session destroy


//The AJAX request to create a bracket
function createBracket(tID) {
    var url = "php/createBracket.php";
    //Send an ajax request
    $.ajax({
        //Page to send it to
        url: url,
        //Type of request
        type: 'POST',
        //The paramaters
        data:{ 
            //Parameter : Value
            tID : tID
        },
        //On Success.. do function
        success:function(result) {
            alert(result);
        }
    });
}

//The AJAX requset to create a team
function createTeam(tID, id) {
    var url = "php/createTeam.php";
    //Send an ajax request
    $.ajax({
        //Page to send it to
        url: url,
        //Type of request
        type: 'POST',
        //The paramaters
        data:{ 
            //Parameter : Value
            tID : tID,
            id : id
        },
        //On Success.. do function
        success:function(result) {
            alert(result);
        }
    });
}