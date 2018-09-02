<?php

session_start();
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");

//require_once("show_errors.php");

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
    
    deleteFile();

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
 

 if(strlen($url)==0){
     if(isset($_SESSION['file_name'])){
         $url=WEBSITE."/m/aff/any-files/".$_SESSION['file_name'];
       
         $_SESSION['file_name']=null;
     }
 }

if(strlen($img_url)==0){
      if(isset($_SESSION['image_name'])){
         
         $img_url=WEBSITE."/m/aff/images/".$_SESSION['image_name'];
         $_SESSION['image_name']=null;
     }
 }
    mysqli_query($con, "INSERT INTO `available_links` (`user_id`,`name`, `img_url`, `url`, `protect`,`ip`,`description`)
    VALUES (\"$user_id\",\"$name\",\"$img_url\",\"$url\",$private,'$ip','$description')");
    echo mysqli_error($con);
        if(mysqli_affected_rows($con)>0){
        echo "LInk added successfully";
    }else{
          echo "LInk add failed.";
    }
     mysqli_query($con,"INSERT INTO `approximate_location`(`ip`, `lat`, `lng`) VALUES ('$ip',$lorderat,$lng)");
     
    deleteFile();
   
}elseif ($action == "edit") {
 


 if(strlen($new_url)==0){
     if(isset($_SESSION['file_name'])){
         $new_url=WEBSITE."/m/aff/any-files/".$_SESSION['file_name'];
       
         $_SESSION['file_name']=null;
     }
 }

if(strlen($img_url)==0){
      if(isset($_SESSION['image_name'])){
         
         $img_url=WEBSITE."/m/aff/images/".$_SESSION['image_name'];
         $_SESSION['image_name']=null;
     }
 }



    mysqli_query($con, "UPDATE `available_links` set name='$name',img_url='$img_url',url='$new_url',protect=$private,description='$description'
    where url='$url' and user_id=$user_id");
      echo mysqli_error($con);
    if(mysqli_affected_rows($con)>0){
        echo "LInk Edited Successfully";
    }else{
          echo "LInk Edit Failed.";
    }
    deleteFile();
}
elseif ($action == "register") {
              $user_id=mysqli_query($con,"SELECT `user_id` FROM `affiliate_users` WHERE email='$email'");
 
        if(mysqli_num_rows($user_id)==0){
          mysqli_query($con,"INSERT INTO `affiliate_users`(`user_id`, `email`,`ip`, `joined_date`) VALUES (null,'$email','$ip','$date')");
            $user_id=mysqli_fetch_assoc(mysqli_query($con,"SELECT `user_id` FROM `affiliate_users` WHERE email='$email'"));
 
            $u_id=$user_id['user_id'];
              
            mysqli_query($con, "INSERT INTO `user_password` (`user_id`, `password`) VALUES ('$u_id', MD5('$password'))");
            mysqli_query($con, " INSERT INTO `registration_incomplete`(`user_id`,`sec_key`) VALUES ('$u_id',MD5('$date.$u_id.$email'))");
        
            if(mysqli_affected_rows($con)>0){
                $register= mysqli_query($con, " SELECT  `sec_key` FROM `registration_incomplete` WHERE user_id='$u_id'");
          
              	$key=mysqli_fetch_assoc($register);
            	$sec_key=$key['sec_key'];
            	
            	
            	
            	
            	
            	
            	
                     // the message
        $msg = "<!DOCTYPE html><html><body>Click to register in <b>Visitors Counter</b> and start tracking traffic to your sites and many more.\nClick below link to confirm registration.\n".WEBSITE."/m/aff/confirm_registration.php?id=".$sec_key."</body></html>";
  

    $Headers  = "MIME-Version: 1.0\n";
    $Headers .= "Content-type: text/html; charset=iso-8859-1\n";
    $Headers .= "X-Sender: <no_replyRegisternoreply.com>\n";
    $Headers .= "X-Mailer: PHP\n"; 
    $Headers .= "X-Priority: 1\n"; 
    
    
      
        
        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg,70);
        
        // send email
                if(mail($email,"One click register",$msg,$Headers)){
                    
                   echo "Comfirm registration by clicking the link sent to your email id.";
                }
            }else{
                echo "Failed,try again";
            }
        }else{
            echo "You are already registered";
        }
    
}
elseif ($action == "login") {
    
$getuser = mysqli_query($con, "SELECT n.user_id FROM registration_incomplete n natural join affiliate_users a WHERE a.email = '$email'");
    if (mysqli_num_rows($getuser) > 0) {
        
    	$user_id=mysqli_fetch_assoc($getuser);
    	   	$u_id=$user_id['user_id'];
           $register= mysqli_query($con, " SELECT  `sec_key` FROM `registration_incomplete` WHERE user_id='$u_id'");
          
              	$key=mysqli_fetch_assoc($register);
            	$sec_key=$key['sec_key'];
            	
            	
            	
            	
            	
            	
            	
                     // the message
        $msg = "<!DOCTYPE html><html><body>Click to register in <b>Visitors Counter</b> and start tracking traffic to your sites and many more.\nClick below link to confirm registration.\n".WEBSITE."/m/aff/confirm_registration.php?id=".$sec_key."</body></html>";
  

    $Headers  = "MIME-Version: 1.0\n";
    $Headers .= "Content-type: text/html; charset=iso-8859-1\n";
    $Headers .= "X-Sender: <no_replyRegisternoreply.com>\n";
    $Headers .= "X-Mailer: PHP\n"; 
    $Headers .= "X-Priority: 1\n"; 
    
    
      
        
        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg,70);
        
        // send email
                if(mail($email,"Complete registration",$msg,$Headers)){
      	echo "You have not confirmed registration!!.Click the link received in your email inbox or spam folder";
}
    }else{
        
    $getuser = mysqli_query($con, "SELECT affiliate_users.user_id FROM `affiliate_users` NATURAL JOIN user_password WHERE `email` = '$email' and password=MD5('".$password."') ");

    // echo mysqli_error($con);

        if(mysqli_num_rows($getuser)==1){

    	$userinforesult=mysqli_fetch_assoc($getuser);
    	echo 1;
     $_SESSION["indication_user"] = $userinforesult['user_id'];
        }
	else
	echo "Incorrect email or password";
    }

}

elseif ($action == "password_reset") {


  
    
    $user_id=mysqli_query($con,"SELECT `user_id` FROM `affiliate_users` WHERE email='$email'");
  
        if(mysqli_num_rows($user_id)==1){
            
    	$u_id=mysqli_fetch_assoc($user_id);
    	$u=$u_id['user_id'];
      $user_id=mysqli_query($con,"	SELECT `user_id` FROM `temp_password` WHERE user_id=$u");
            if(mysqli_num_rows($user_id)==0){
            	mysqli_query($con,"INSERT INTO `temp_password`(`user_id`, `secured_password`,`original_password`, `date`) VALUES ('$u',MD5('$date.$u.$password'),MD5('$password'),'$date')");
            }else{
                	mysqli_query($con,"UPDATE `temp_password` SET `secured_password`=MD5('$u.$password'),`original_password`=MD5('$password'),`date`='$date' WHERE user_id=$u");
            }	
        }else{
            echo "Email not found";
        }
        
       

      
      
    if(mysqli_affected_rows($con)>0){
          $password=mysqli_query($con,"	SELECT `secured_password` FROM `temp_password` WHERE user_id='$u'");
         	$pass=mysqli_fetch_assoc($password);
    	$sp=$pass['secured_password'];
      // the message


        $msg = "<!DOCTYPE html><html><body>Someone has requested to change password for your account on <b>Visitors Counter</b> from ip address $ip.\nClick below link to change your password.\n".WEBSITE."/m/aff/confirm_password.php?password=".$sp."</body></html>";
  

    $Headers  = "MIME-Version: 1.0\n";
    $Headers .= "Content-type: text/html; charset=iso-8859-1\n";
    $Headers .= "X-Sender: <no_reply@changePasswordnoreply.com>\n";
    $Headers .= "X-Mailer: PHP\n"; 
    $Headers .= "X-Priority: 1\n"; 
    
// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
        if(mail($email,"Password change requested",$msg,$Headers)){
            
            echo "Password verification link has been sent to your email";
        }else{
             echo "Failed,try again";
            }
    }
    else{
       echo "You are not registered ".mysqli_error($con);
    }
}

function deleteFile(){
    global $con;
    $file_contains=false;
    $img_url=array();
     $url=array();
     $any_file_dir="any-files";
     
     $images="images";
          $url_data=mysqli_query($con,"	SELECT `img_url`,`url` FROM `available_links`");
         while($a=mysqli_fetch_array($url_data)){
           
array_push($img_url,$a["img_url"]);

array_push($url,$a["url"]);
         }
    
    foreach (new DirectoryIterator($images) as $file) {

  if ($file->isFile() ) {
      for($i=0;$i<sizeof($img_url);$i++){
     
      $temp=array();
        $temp=explode("/",$img_url[$i]);
         if(strcmp($temp[sizeof($temp)-1],$file->getFilename())==0){
               $file_contains=true;
               
                  break;
         }
            
      }
        if(!$file_contains){
             
                  unlink($images."/".$file->getFilename());
           
     
          }else{
                   $file_contains=false;
          }
  }
}
    foreach (new DirectoryIterator($any_file_dir) as $file) {
  if ($file->isFile()) {
       for($i=0;$i<sizeof($url);$i++){
      $temp=array();
        $temp=explode("/",$url[$i]);
        if(strcmp($temp[sizeof($temp)-1],$file->getFilename())==0){
                
                $file_contains=true;
                
                  break;
         }
             
    }
           if(!$file_contains){
        
                  unlink($any_file_dir."/".$file->getFilename());
        
     
          }else{
                   $file_contains=false;
          }
  }
}
}
mysqli_close($con);

?>