<html>
        <body>
<?php
$password=$_GET['password'];
require_once("dbconnect.php");
 $pass=mysqli_query($con,"	SELECT user_id,original_password,`secured_password` FROM `temp_password` WHERE secured_password='$password'");
            if(mysqli_num_rows($pass)==1){
      	$pass=mysqli_fetch_assoc($pass);
    	$p=$pass['original_password']; 
    	
    	$u=$pass['user_id']; 
    mysqli_query($con, "UPDATE `user_password` SET `password`= '$p' WHERE user_id=$u");
     mysqli_query($con, "delete from  `temp_password`  WHERE user_id=$u");
 
    ?>
    <center>
        
            Password changed successfully<br>
    <a href="login.php">Login</a>
     
    </center>
  
    <?php
            }else{
                 ?>
        <center>
            
            Password expired.try again<br>
    <a href="login.php">Login</a>
        </center>
     
  
    <?php
            }
        mysqli_close($con);    
?>
   </body>
    
    </html>