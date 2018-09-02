<?php

session_start();
  if(!empty($_FILES)){     
    $upload_dir = "images/";
    $fileName = microtime()."_".  $_FILES['file']['name'];
    
    $fileName=str_replace(' ','',$fileName);
    $uploaded_file = $upload_dir.$fileName;    
    if(move_uploaded_file($_FILES['file']['tmp_name'],$uploaded_file)){
        
        $_SESSION['image_name']=$fileName;
    }
  }
?>