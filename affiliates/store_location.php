<?php
	
		require_once('dbconnect.php');
		
$login=$_POST['login'];

	$lat=$_POST['lat'];
	$lng=$_POST['lng'];
	
	if(isset($login)){
		$user_id=$_POST['user_id'];

	
$available=mysqli_query($con,"SELECT `user_id`, `lat`, `lng` FROM `affilate_users_location` WHERE user_id=$user_id and lat=$lat and lng=$lng");
if(mysqli_num_rows($available)==0){
mysqli_query($con,"INSERT INTO affilate_users_location (`user_id`, `lat`, `lng`) VALUES ('$user_id','$lat','$lng')");
		echo mysqli_error($con);
}
}else{
    	$click_id=$_POST['click_id'];

    mysqli_query($con,"INSERT INTO clicks_users_location (`click_id`, `lat`, `lng`) VALUES ('$click_id','$lat','$lng')");
		echo mysqli_error($con);
    
}

	  
?>