<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION["indication_user"]) ) {
     echo '<script language="javascript">window.location.href ="index.php"</script>';
 exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>

<title>Login</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="google-signin-client_id" content="639057428140-s7afab8uota1gm6beipr0haj6khjrm3j.apps.googleusercontent.com">
<script src="https://apis.google.com/js/platform.js" async defer></script>
<link rel="icon" href="assets/favicon.ico">

<link rel="apple-touch-icon" href="assets/icon.png">
<link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css" type="text/css" media="screen">
<link rel="stylesheet" href="assets/css/indication.css" type="text/css" media="screen">
</head>
<body>

<div class="container">
<center>
    
      <h3> <b><i><font color="green">Count visitors for your link easily!!</font></i></b></h3>
      <br><br>
    <b>Sign in with Google</b>
  <br><br>
<div  class="g-signin2" data-onsuccess="onSignIn"></div><br>
<b>OR</b><br>


<h3>Login</h3>
</center>
<form id="login" class="form-signin" >

<label for="email" class="sr-only">Email</label>
<input type="email" id="email" name="email" class="form-control" placeholder="Email..." required autofocus>
<label for="password" class="sr-only">Password</label>
<input type="password" id="password" name="password" class="form-control" placeholder="Password..." required>
<a class="pull-right"  href="register.html" >Register here</a>
<a  href="reset_password.html" >Forgot password?</a>
<input type="hidden" id="action" name="action" value="login">
<button class="btn btn-primary btn-block" type="submit">Sign in</button>
</form>
</div>
<script src="assets/bower_components/jquery/dist/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/bootstrap-validator/dist/validator.min.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/bootbox.js/bootbox.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function () {
    $("#login").validator({
        disable: true
    });
   
    $("#login").validator().on("submit", function (e) {
        if (e.isDefaultPrevented()) {
            return false;
        } 
        $.ajax({
            type: "POST",
            url: "worker.php",
            data: $("#login").serialize(),
            error: function() {
                $.notify({
                    message: "Ajax query failed!",
                    icon: "glyphicon glyphicon-warning-sign",
                },{
                    type: "danger",
                    allow_dismiss: true
                });
            },
            success: function(data) {
           
			 if(data==1){
				window.location="index.php";
            }else
			{
				alert(data);	
			}
			
            }
        });
        return false;
    });
});
</script>
<script>
 
  var profile;
function onSignIn(googleUser) {
   profile = googleUser.getBasicProfile();
  console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
  console.log('Name: ' + profile.getName());
  console.log('Image URL: ' + profile.getImageUrl());
  console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.

 // window.location.href = window.location.href+'?email='+profile.getEmail();
  refresh()
  
}

</script>



    <script src="js/bootstrap.js"></script>

 <script>

    
function refresh(){
    
    
    $.post("loginresult.php",
       {email: profile.getEmail()},
       function(response){
           console.log(response);
                 window.location.href ="index.php"
       }
);
 
}


				</script>
</body>
</html>
