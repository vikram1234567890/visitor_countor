<?php

	$con = mysqli_connect("localhost","id1135219_vikram001", "123456", "id1135219_marketing");
	if (mysqli_connect_errno())
	  {
	  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
		
	mysqli_select_db($con,"id1135219_marketing");

?>