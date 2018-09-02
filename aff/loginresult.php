<?php
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");

session_start();
require_once('show_errors.php');
require_once('ip.php');
if(isset($_POST['email'])){
  
 $ip = getUserIP();
$details = json_decode(file_get_contents("https://api.ipdata.co/{$ip}"));
  $details->region;

  $details->city;

 $details->country_name;

 $lat=$details->latitude;
$lng= $details->longitude;

$email=$_POST['email'];
	require_once('dbconnect.php');
		require_once('config.php');
  mysqli_query($con,"INSERT INTO `affiliate_users`(`user_id`, `email`,`ip`, `joined_date`) VALUES (null,'$email','$ip','$date')");
    mysqli_query($con,"INSERT INTO `approximate_location`(`ip`, `lat`, `lng`) VALUES ('$ip',$lat,$lng)");
echo mysqli_error($con);
$getuser = mysqli_query($con, "SELECT `user_id` FROM `affiliate_users` WHERE `email` = '$email'");


    if (mysqli_num_rows($getuser) == 0) {
      	echo '<script language="javascript">window.location.href ="logout.php"</script>';

        exit;
    }
	
	while($a= mysqli_fetch_array($getuser)){
	$userinforesult=$a['user_id'];
}

     $_SESSION["indication_user"] = $userinforesult;
     
    
mysqli_close($con);
	

}
?>