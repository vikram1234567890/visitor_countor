<?php

session_start();
if (!file_exists("config.php")) {
    die("Error: Config file not found!");
}
	date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
require_once("config.php");

//Connect to database
@$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

//Get the ID from $_GET OR $_POST
if (isset($_GET["id"])) {
    $abbreviation = mysqli_real_escape_string($con, $_GET["id"]);
} elseif (isset($_POST["id"])) {
    $abbreviation = mysqli_real_escape_string($con, $_POST["id"]);
} else {
    die("Error: ID not found.");
}

//Check if ID exists
$getinfo = mysqli_query($con, "SELECT `id`,links.user_id, links.name, links.url, links.protect, `password`,available_links.img_url,available_links.description FROM `links`,available_links WHERE `abbreviation` = \"$abbreviation\" and available_links.url=links.url");
$getinforesult = mysqli_fetch_assoc($getinfo);

if (mysqli_num_rows($getinfo) == 0) {
    die("Error: ID does not exist.");
}




if (!isset($_SESSION["indication_user"]) and  strcmp($_COOKIE[$abbreviation],$abbreviation)!=0) {
   
    $id = $getinforesult["id"];
    
    //Get IP
 
 $ip = getUserIP();
 $details = json_decode(file_get_contents("https://api.ipdata.co/{$ip}"));
  $details->region;

  $details->city;

 $details->country_name;

 $lat=$details->latitude;
$lng= $details->longitude;
    //Get referrer
    $referrer = $_SERVER["HTTP_REFERER"];

    if (empty($referrer)) {
        $referrer = "";
    }
    
 /*   //Check against blacklist
    $checkblacklist = mysqli_query($con, "SELECT `id`, `ip` FROM `blacklist` WHERE `ip` = \"$ip\"");
    $checkblacklistresult = mysqli_fetch_assoc($checkblacklist);
    if (mysqli_num_rows($checkblacklist) == 1) {
        die("Error: The IP address " . $checkblacklistresult["ip"] . " has been blocked by the site administrator.");
    }*/

 
    if (strcmp(COUNT_UNIQUE_ONLY_STATE , "Enabled")==0) {

              mysqli_query($con, "INSERT INTO `counts` (link_id, date, ip, referrer)
            VALUES (\"$id\",'$date',\"$ip\",\"$referrer\")");
            
    mysqli_query($con,"INSERT INTO `approximate_location`(`ip`, `lat`, `lng`) VALUES ('$ip',$lat,$lng)");
        setcookie($abbreviation,$abbreviation,  time()+ (86400 * 30), "/");//cokie for 30 days
   
    } 


}

$redirect=$getinforesult['url'];


mysqli_close($con);

?>
<!DOCTYPE html>
<html>
    <head>
        <style>
        
body{
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

            .loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.container {
    padding: 16px;
}


/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
    span.psw {
       display: block;
       float: none;
    }
    .cancelbtn {
       width: 100%;
    }
}

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    padding-top: 60px;
}

/* Modal Content/Box */
.modal-content {
       background-color: #e5e5ff;

    margin: 5px auto; /* 15% from the top and centered */
    border: 5px solid #ccccff;
    width: 40%; /* Could be more or less, depending on screen size */
}





/* Add Zoom Animation */
.animate {
    -webkit-animation: animatezoom 0.6s;
    animation: animatezoom 0.6s
}

@-webkit-keyframes animatezoom {
    from {-webkit-transform: scale(0)} 
    to {-webkit-transform: scale(1)}
}

@keyframes animatezoom {
    from {transform: scale(0)} 
    to {transform: scale(1)}
}

.middle {
    width: auto;
    height: auto;

    position: absolute;
    top:0;
    bottom: 0;
    left: 0;
    right: 0;

    margin: auto;
}

        </style>
        <meta property="og:title" content="<?php echo $getinforesult['name'];?>">
<meta property="og:image" content="<?php echo $getinforesult['img_url'];?>">
<meta property="og:description" content="<?php echo $getinforesult['description'];?>">
<meta property="og:url" content="<?php echo $getinforesult['url'];?>">
    </head>
    <body>
         <!-- JavaScript -->
         <center>
    <div class="loader"></div> <br>  <b>Please wait...</b>
    </center>
          </div>

 <script src="assets/bower_components/jquery/dist/jquery.min.js" type="text/javascript" charset="utf-8"></script>

         
            <script src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.1/jquery-ui.js" type="text/javascript"></script>
    <link href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.1/themes/blitzer/jquery-ui.css"
        rel="stylesheet" type="text/css" />
        
     <script src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.1/jquery-ui.js" type="text/javascript"></script>
    <link href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.1/themes/blitzer/jquery-ui.css"
        rel="stylesheet" type="text/css" />
 
 

    <script src="js/bootstrap.js"></script>
    
<script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/bootbox.js/bootbox.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/js-cookie/src/js.cookie.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/modernizr-load/modernizr.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">


  window.onload = function () {
document.getElementById('id01').style.display='block'
}

document.addEventListener('contextmenu', event => event.preventDefault());
   var redirect ="<?php echo $redirect;  ?>";
         getLocation();
         
    function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition,showError);
    } else { 
                
                  window_close();
    }
    
    }
  

function showPosition(position) {
           var lat,lng;
 
var click_id=<?php echo $c_id['id'];?>+'';

		lat=position.coords.latitude;
		lng=position.coords.longitude;

		var array = {lat:lat,lng:lng,click_id:click_id};
		$.ajax({
                    type: "POST",
                    url: "store_location.php",
                    data: array,
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
                 // window.open(redirect, '_blank');
                    window_close();
                      
                    }
                });
}
function window_close() {
    window.location.replace (redirect);
  /// window.close();   
}

function showError(error) {
                
       window_close();
  /*switch(error.code) {
    case error.PERMISSION_DENIED:
          
                   window.location.href =redirect;
                   break;
    case error.POSITION_UNAVAILABLE:
     // x.innerHTML = "Location information is unavailable."
      window.location.href =redirect;
      break;
    case error.TIMEOUT:
     // x.innerHTML = "The request to get user location timed out."
      window.location.href =redirect;
      break;
    case error.UNKNOWN_ERROR:
      //x.innerHTML = "An unknown error occurred."
       window.location.href =redirect;
      break;
  }*/
}

</script>
        
    </body>
</html>
