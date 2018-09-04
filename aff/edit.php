<?php

require_once("session_check.php");

require_once("dbconnect.php");

?>
<!DOCTYPE html>
<html lang="en">
    
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
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
</style>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="assets/favicon.ico">
<title>Edit link</title>
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

if (isset($_GET["url"])) {
    $url =$_GET["url"];
}
$getdata=mysqli_query($con,"SELECT  `name`, `img_url`, `protect`,description FROM `available_links` WHERE url='$url'");
echo mysqli_error($con);
    $data = mysqli_fetch_assoc($getdata);
    $img_url=$data['img_url'];
    $name=$data['name'];
    $protect=  $data['protect'];   
        $description=  $data['description'];   
	 ?>

<div class="margin">
     <div class="margin">
     <div id="loading">
<img id="loading-image" src="ajax-loader.gif" alt="Loading..."  height="42" width="42"/>
</div>
<h1 class="page-header">Edit Link</h1>
<form id="editform"  action="/m/aff/worker.php"   method="POST"  enctype='multipart/form-data'  >
<div class="form-group">
    
    <b>Item name</b>
<input type="text" class="form-control" id="name" name="name" placeholder="Type a name..." value = "<?php echo $name; ?>" required>
</div>
<div class="form-group">
      <b>Item image </b>  
      </br>
      <select name="Select method" onchange="showDivImage(this)">
  <option value ="0">Enter Url</option>
  <option value ="1">Upload image manually</option>
</select>
<input type="url" class="form-control" id="img_url" name="image_url" placeholder="Enter image url..." value = "<?php echo $img_url; ?>" >
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
<input type="url" class="form-control" id="new_url" name="new_url" placeholder="Enter a URL..." value = "<?php echo $url; ?>" >
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
    <textarea name="description" cols="40" rows="5" class="form-control" id="description" name="description" placeholder="Type a Description..."    maxlength="700"><?php echo $description; ?></textarea>

</div>
<div class="checkbox">
<label>
<input type="checkbox" id="private" name="private">Make link private (Will not be shown publicly)

</label>
</div>




<div class="form-group">
<input type="hidden" id="url" name="url" value="<?php echo $url; ?>">


<input type="hidden" id="action" name="action" value="edit">

<button type="submit" class="btn btn-default">Edit</button>
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
   var frm = $('#editform');
 
    frm.submit(function (e) {

        e.preventDefault();
  bootbox.confirm("Editing this link will edit your affiliate link as well.Do you want to continue?", function(result) {
            if (result == true) {
 
document.getElementById("loading").style.display = "block" ;

        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),
            success: function (result) {
                    $.notify({
                    message: result,
                    icon: "glyphicon glyphicon-ok",
                },{
                    type: "success",
                    allow_dismiss: true
                });
             console.log(result);
             
document.getElementById("loading").style.display = "none" ;
              window.location.href="mylinks.php";
            },
            error: function (data) {
                
document.getElementById("loading").style.display = "none" ;
                console.log('An error occurred.');
                console.log(data);
            },
        });
    }

});
    });

 function showDivImage(elem){
   if(elem.value == "0"){
      document.getElementById('img_url').style.display = "block";
      
      document.getElementById("img_url").value = "<?php echo $img_url; ?>";
      document.getElementById('manual_upload_image').style.display = "none";
      }else   if(elem.value == "1")
    {
      document.getElementById('img_url').style.display = "none";
      
      document.getElementById("img_url").value = "";
      document.getElementById('manual_upload_image').style.display = "block";
 
    }
      
}

 function showDivFile(elem){
   if(elem.value == "0"){
      document.getElementById('new_url').style.display = "block";
      document.getElementById("new_url").value = "<?php echo $url; ?>";
      document.getElementById('manual_upload_file').style.display = "none";
      }else   if(elem.value == "1")
    {
      document.getElementById('new_url').style.display = "none";
      
      document.getElementById("new_url").value = "";
      document.getElementById('manual_upload_file').style.display = "block";
 
    }
      
}
 function hideElements(){
     
          
      document.getElementById('manual_upload_image').style.display = "none";

   
      document.getElementById('manual_upload_file').style.display = "none";
  
      
}

document.getElementById("loading").style.display = "none" ;
</script>
</body>
</html>
