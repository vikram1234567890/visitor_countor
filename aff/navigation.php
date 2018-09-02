<!DOCTYPE html>
<html lang="en">
  <head>
         <meta name="google-signin-client_id" content="639057428140-s7afab8uota1gm6beipr0haj6khjrm3j.apps.googleusercontent.com">
         
      <style>
          .user_name {
  float: left;
  height: 20px;
  padding: 15px 15px;
  font-size: 18px;
  line-height: 20px;
  color: #FFFFFF;
}

      </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title></title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
  </head>

  <body>

  <script>

    function signOut() {
        
      var auth2 = gapi.auth2.getAuthInstance();
      auth2.signOut().then(function () {
        console.log('User signed out.');
      
      });

                 destroy_session();
    }

    function onLoad() {
      gapi.load('auth2', function() {
        gapi.auth2.init();
      });
    }
    
    
    function destroy_session(){

     $.ajax({
            type: "POST",
            url: "destroy_session.php",
           
            data: '',
            error: function() {
                $.notify({
                    message: "Ajax query failed!",
                    icon: "glyphicon glyphicon-warning-sign",
                },{
                    type: "danger",
                    allow_dismiss: true
                });
            },
            success: function() {
           document.location.href = "index.php";
            }
        });
}
  </script>

    <div id="wrapper">

      <!-- Sidebar -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <font class="user_name"  >
              <?php





$user_id=$_SESSION["indication_user"];
require_once('dbconnect.php'); 
$q=mysqli_query($con,"SELECT `email` FROM `affiliate_users` WHERE user_id=$user_id");
$user=mysqli_fetch_assoc($q);
$u=explode('@',$user['email']);
echo 'Hi '.$u[0].' !!';
?>
</font>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav side-nav">
            <li><a href="index.php"> Dashboard<span class="sr-only">(current)</span></a></li>
           <li ><a href="affiliate_links.php">Affiliate links<span class="sr-only">(current)</span></a></li>
       <li ><a href="add_custom_link.php">Add custom links<span class="sr-only">(current)</span></a></li>
       
             <li ><a href="mylinks.php">My Links<span class="sr-only">(current)</span></a></li>
               <li ><a href="app_installs.php">App Installs<span class="sr-only">(current)</span></a></li>
         <li ><a href="app_uninstalls.php">App Uninstalls<span class="sr-only">(current)</span></a></li>
       
         
          </ul>

          <ul class="nav navbar-nav navbar-right navbar-user">
          
           
  <script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script>
                <li><a href="#" onclick="signOut();"><i class="fa fa-power-off"></i> Log Out</a>
                  </li>
              </ul>
          
       
        </div><!-- /.navbar-collapse -->
      </nav>



  </body>
</html>