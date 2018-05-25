<?php

require_once("session_check.php");

require_once("dbconnect.php");

?>
<!DOCTYPE html>
<html lang="en">
    
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
     <style>
     
     
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
<h1 class="page-header">Edit Link</h1>
<form id="editform" autocomplete="off" >
<div class="form-group">
<input type="text" class="form-control" id="name" name="name" placeholder="Type a name..." value = "<?php echo $name; ?>" required>
</div>
<div class="form-group">
<input type="url" class="form-control" id="img_url" name="image_url" placeholder="Type image url..." value = "<?php echo $img_url; ?>" required>
</div>
<div class="form-group">
<input type="url" class="form-control" id="new_url" name="new_url" placeholder="Type a URL..." value = "<?php echo $url; ?>" required>
</div>
<div class="form-group">
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

$(document).ready(function () {
    $("#editform").validator({
        disable: true
    });

    $("#editform").validator().on("submit", function (e) {
        if (e.isDefaultPrevented()) {
            return false;
        } 
             bootbox.confirm("Editing this link will edit you affiliate as well.Do you want to continue?", function(result) {
            if (result == true) {
        $.ajax({
            type: "POST",
            url: "worker.php",
            data: $("#editform").serialize(),
            error: function() {
                $.notify({
                    message: "Ajax query failed!",
                    icon: "glyphicon glyphicon-warning-sign",
                },{
                    type: "danger",
                    allow_dismiss: true
                });
            },
            success: function(string) {
                $.notify({
                    message: string,
                    icon: "glyphicon glyphicon-ok",
                },{
                    type: "success",
                    allow_dismiss: true
                });
             console.log(string);
                window.location.href='mylinks.php';
            }
        });
		 }
        }); 
        return false;
    });
            
});
</script>
</body>
</html>