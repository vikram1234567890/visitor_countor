<html>
        <body>
<?php
$sec_key=$_GET['id'];
require_once("dbconnect.php");
mysqli_query($con,"DELETE FROM `registration_incomplete` WHERE sec_key='$sec_key'");
          echo mysqli_error($con);
  if(mysqli_affected_rows($con)>0){
    ?>
    <center>
        
            Registered successfully<br>
    <a href="login.php">Login</a>
     
    </center>
  
    <?php
            }else{
                 ?>
        <center>
            
           Registration failed.try again<br>
    <a href="login.php">Login</a>
        </center>
     
  
    <?php
            }
        mysqli_close($con);    
?>
   </body>
    
    </html>