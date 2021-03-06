<?php
require_once("session_check.php");

require_once("dbconnect.php");

$user_id=$_SESSION["indication_user"];



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-1211635675454735",
          enable_page_level_ads: true
     });
</script>
     <style>
   #loading {width: 100%;height: 100%;top: 0px;left: 0px;position: fixed;display: block; z-index: 99}

#loading-image {position: absolute;top: 40%;left: 45%;z-index: 100} 
body{
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.margin {
    
    margin: 10px 10px 10px 10px;
    
}
.tab{
    margin-left:5em;
}
</style>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="assets/favicon.ico">
<title>Add link</title>
<link rel="apple-touch-icon" href="assets/icon.png">
<link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css" type="text/css" media="screen">
<link rel="stylesheet" href="assets/css/indication.css" type="text/css" media="screen">
<link rel="stylesheet" type="text/css" href="css/dropzone.css" />
<script type="text/javascript" src="js/dropzone.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
<?php

     require_once("navigation.php");
	 ?>

<div class="margin">
    <div id="loading">
<img id="loading-image" src="ajax-loader.gif" alt="Loading..." height="42" width="42" />
</div> 
<h1 class="page-header">Add Custom Link</h1>
<form id="addform" action="/m/aff/worker.php"   method="POST"  enctype='multipart/form-data' >
<div class="form-group">
  <b>Item Name</b>  
<input type="text" class="form-control" id="name" name="name" placeholder="Type a name..." maxlength="100" required>
</div>
 
<div class="form-group">
      <b>Item image </b>  
      </br>
      <select name="Select method" onchange="showDivImage(this)">
  <option value ="0">Enter Url</option>
  <option value ="1">Upload image manually</option>
</select>
<input type="url"   class="form-control" id="img_url" name="image_url" placeholder="Type image url..." >
<div id="manual_upload_image" class="container">
	<div class="file_upload">
	    <div action="upload_image.php" class="dropzone" >
          <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
  <div class="dz-default dz-message" data-dz-message="">
          <span>Upload or drag  photo here</span>
     </div>
		  <div class="fallback">
    <input name="image_file" type="image"  />
  </div>
  </div>
	</div>		
	
</div>
</div>

<div class="form-group">
      <b>Item </b>  
      </br>
         <select name="Select method" onchange="showDivFile(this)">
  <option value ="0">Enter Url</option>
  <option value ="1">Upload file manually</option>
</select>
<input type="url"   class="form-control" id="url" name="url" placeholder="Type a URL..." >
</div>
<div id="manual_upload_file"  class="container" >
	<div class="file_upload">
	    <div action="upload_any_file.php" class="dropzone" >
          <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
 
		  <div class="fallback">
    <input name="any_file" type="file"  />
  </div>
  </div>
	</div>		
	
</div>
<div class="form-group">
      <b>Item description</b>  
    <textarea name="description" cols="40" rows="5" class="form-control" id="description"  placeholder="Type a Description..." maxlength="700"></textarea>

</div>
<div class="checkbox">
<label>
    
<input type="checkbox" id="private" name="private">Make link private (Will not be shown in "Affiliate links")

</label>
</div>

<div class="form-group">

<input type="hidden" id="id" name="id" value="<?php echo $user_id; ?>">


<input type="hidden" id="action" name="action" value="add">

<button type="submit" class="btn btn-default">Add</button>
</div>
</form>

</div>

<script src="assets/bower_components/jquery/dist/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/bootstrap-validator/dist/validator.min.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/bootbox.js/bootbox.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
hideElements();


    var frm = $('#addform');

    frm.submit(function (e) {

 document.getElementById("loading").style.display = "block";  
        e.preventDefault();

        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),
            success: function (data) {
                    $.notify({
                    message: data,
                    icon: "glyphicon glyphicon-ok",
                },{
                    type: "success",
                    allow_dismiss: true
                });
             console.log(data);
             
 document.getElementById("loading").style.display = "none";  
                window.location.reload();
                
            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
                
 document.getElementById("loading").style.display = "none";  
            },
        });
    });
    function showDivImage(elem){
   if(elem.value == "0"){
      document.getElementById('img_url').style.display = "block";
      
      document.getElementById('manual_upload_image').style.display = "none";
      }else   if(elem.value == "1")
    {
      document.getElementById('img_url').style.display = "none";
      
      document.getElementById('manual_upload_image').style.display = "block";
 
    }
      
}
 function showDivFile(elem){
   if(elem.value == "0"){
      document.getElementById('url').style.display = "block";
      
      document.getElementById('manual_upload_file').style.display = "none";
      }else   if(elem.value == "1")
    {
      document.getElementById('url').style.display = "none";
      
      document.getElementById('manual_upload_file').style.display = "block";
 
    }
      
}
 function hideElements(){
     
          
      document.getElementById('manual_upload_image').style.display = "none";

   
      document.getElementById('manual_upload_file').style.display = "none";
  
      
}
 document.getElementById("loading").style.display = "none";  
</script>
</body>
</html>