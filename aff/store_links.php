<?php
session_start();
date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d h:i:sa");
if (!isset($_SESSION["indication_user"])) {
    echo '<script language="javascript">window.location.href ="'.WEBSITE.'/marketing/affiliates/login.php"</script>';
     exit;
}
require_once("dbconnect.php");
require_once("config.php");
$url=$_POST['url'];

$user_id=$_SESSION['indication_user'];

 $n=mysqli_fetch_assoc(mysqli_query($con,"SELECT  name,`url` from `available_links` WHERE url='$url'"));
$name=$n['name'];
$url=$n['url'];
 $e=mysqli_fetch_assoc(mysqli_query($con,"SELECT  `email` from `affiliate_users` WHERE user_id=$user_id"));
 $arr=explode("@",$e['email']);
 $email=$arr[0];
 $abbrevation=$url."_".$email;
 
  $abbrevation= str_replace(' ','', $abbrevation);
  
$availability = mysqli_query($con, "SELECT  abbreviation FROM `links` WHERE user_id=$user_id and abbreviation='$abbreviation'  ");

  if(mysqli_num_rows($availability)==0){

mysqli_query($con,"INSERT INTO `links`(`id`, `user_id`, `name`, `abbreviation`, `url`, `protect`, `password`,`date`) VALUES (null,'$user_id','$name',MD5('$abbrevation'),'$url','0','','$date')");
 echo mysqli_error($con);
 if(mysqli_affected_rows($con)>0){
        echo "Affiliate LInk added to dashboard";
    }else{
          echo "Affiliate LInk add failed.";
    }
  }else{
        echo "Affiliate LInk already added to dashboard";
  }
  
?>