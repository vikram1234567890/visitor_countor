<?php

session_start();
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");


if (!file_exists("config.php")) {
    die("Error: Config file not found!");
}

require_once("config.php");
require_once("ip.php");
//Connect to database
@$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (mysqli_connect_errno()) {
    die("Error: Could not connect to database (" . mysqli_connect_error() . "). Check your database settings are correct.");
}


$user_id=$_SESSION['indication_user'];



 $ip = getUserIP();
$details = json_decode(file_get_contents("https://api.ipdata.co/{$ip}"));
  $details->region;

  $details->city;

 $details->country_name;

 $lat=$details->latitude;
$lng= $details->longitude;

if (isset($_POST["action"])) {
    $action = $_POST["action"];
} elseif (isset($_GET["action"])) {
    $action = $_GET["action"];
} else {
	die("Error: No action passed!");
}


//Check if ID exists
$actions = array("edit", "delete_affiliate_link", "delete", "deleteip", "register","password_reset");
if (in_array($action, $actions)) {
    if (isset($_POST["id"]) || isset($_GET["id"])) {
        if (isset($_POST["action"])) {
            $id = mysqli_real_escape_string($con, $_POST["id"]);
        } elseif (isset($_GET["action"])) {
            $id = mysqli_real_escape_string($con, $_GET["id"]);
        }
       
}
}


//Define variables
if (isset($_POST["email"])) {
    $email = mysqli_real_escape_string($con, $_POST["email"]);
}
if (isset($_POST["password"])) {
    $password = mysqli_real_escape_string($con, $_POST["password"]);
}
if (isset($_POST["name"])) {
    $name = mysqli_real_escape_string($con, $_POST["name"]);
}
if (isset($_POST["image_url"])) {
    $img_url = mysqli_real_escape_string($con, $_POST["image_url"]);
}
if (isset($_POST["url"])) {
    $url = mysqli_real_escape_string($con, $_POST["url"]);
}
if (isset($_POST["new_url"])) {
    $new_url = mysqli_real_escape_string($con, $_POST["new_url"]);
}
if (isset($_POST["description"])) {
    $description = mysqli_real_escape_string($con, $_POST["description"]);
}
  $private=0;
 if (isset($_POST["private"])) {
     $private=1;
 
 }
if ($action == "delete") {

    mysqli_query($con, "DELETE FROM `available_links` WHERE `url` ='$id' ");
   // mysqli_query($con, "DELETE FROM `counts` WHERE `link_id` = $id ");
   echo mysqli_error($con);
    if(mysqli_affected_rows($con)>0){
        echo "LInk deleted successfully";
    }else{
          echo "LInk delete failed.";
    }
    

}elseif ($action == "delete_affiliate_link") {

    mysqli_query($con, "DELETE FROM `links` WHERE links.id ='$id' ");
   // mysqli_query($con, "DELETE FROM `counts` WHERE `link_id` = $id ");

   echo mysqli_error($con);
  if(mysqli_affected_rows($con)>0){
        echo "Affiliate LInk deleted successfully";
    }else{
          echo "Affiliate LInk delete failed.";
    }
    

}elseif ($action == "add") {
 
     if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $img_url)) {
        die("Error: Invalid URL!");
    }
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
        die("Error: Invalid URL!");
    }


    mysqli_query($con, "INSERT INTO `available_links` (`user_id`,`name`, `img_url`, `url`, `protect`,`ip`,`description`)
    VALUES (\"$user_id\",\"$name\",\"$img_url\",\"$url\",$private,'$ip','$description')");
    echo mysqli_error($con);
        if(mysqli_affected_rows($con)>0){
        echo "LInk added successfully";
    }else{
          echo "LInk add failed.";
    }
     mysqli_query($con,"INSERT INTO `approximate_location`(`ip`, `lat`, `lng`) VALUES ('$ip',$lat,$lng)");
   
}elseif ($action == "edit") {
 
     if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $img_url)) {
        die("Error: Invalid URL!");
    }
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $new_url)) {
        die("Error: Invalid URL!");
    }




    mysqli_query($con, "UPDATE `available_links` set name='$name',img_url='$img_url',url='$new_url',protect=$private,description='$description'
    where url='$url' and user_id=$user_id");
      echo mysqli_error($con);
    if(mysqli_affected_rows($con)>0){
        echo "LInk Edited Successfully";
    }else{
          echo "LInk Edit Failed.";
    }
}
elseif ($action == "register") {
  mysqli_query($con,"INSERT INTO `affiliate_users`(`user_id`, `email`,`ip`, `joined_date`) VALUES (null,'$email','$ip','$date')");
  
    $user_id=mysqli_fetch_assoc(mysqli_query($con,"SELECT `user_id` FROM `affiliate_users` WHERE email='$email'"));
    $u_id=$user_id['user_id'];
      
    mysqli_query($con, "INSERT INTO `user_password` (`user_id`, `password`) VALUES ('$u_id', MD5('$password'))");
      echo mysqli_error($con);
    if(mysqli_affected_rows($con)>0)
    echo "Successfully Registered!!Login now";
    
}
elseif ($action == "login") {
    $getuser = mysqli_query($con, "SELECT affiliate_users.user_id FROM `affiliate_users` NATURAL JOIN user_password WHERE `email` = '$email' and password=MD5('".$password."') ");

     echo mysqli_error($con);

        if(mysqli_num_rows($getuser)==1){
    echo 1;
    
    	$userinforesult=mysqli_fetch_assoc($getuser);
    	
     $_SESSION["indication_user"] = $userinforesult['user_id'];
        }
	else
	echo 0;

}

elseif ($action == "password_reset") {

    $user_id=mysqli_query($con,"SELECT `user_id` FROM `affiliate_users` WHERE email='$email'");
  
        if(mysqli_num_rows($user_id)==1){
            
    	$u_id=mysqli_fetch_assoc($user_id);
    	$u=$u_id['user_id'];
    mysqli_query($con, "UPDATE `user_password` SET `password`= MD5('$password') WHERE user_id=$u");
        }else{
            echo "Email not found";
        }
      //echo mysqli_error($con);
    if(mysqli_affected_rows($con)>0)
    echo "Password changed";
    else
       echo "You have not registered";
}

mysqli_close($con);

?>