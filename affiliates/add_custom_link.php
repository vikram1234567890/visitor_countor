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
<h1 class="page-header">Add Custom Link</h1>
<form id="addform" autocomplete="off" >
<div class="form-group">
<input type="text" class="form-control" id="name" name="name" placeholder="Type a name..." maxlength="100" required>
</div>
<div class="form-group">
<input type="url" class="form-control" id="img_url" name="image_url" placeholder="Type image url..." required>
</div>
<div class="form-group">
<input type="url" class="form-control" id="url" name="url" placeholder="Type a URL..." required>
</div>
<div class="form-group">
    <textarea name="description" cols="40" rows="5" class="form-control" id="description"  placeholder="Type a Description..." maxlength="700"></textarea>

</div>
<div class="checkbox">
<label>
    
<input type="checkbox" id="private" name="private">Make link private (Will not be shown publicly)

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

$(document).ready(function () {
    $("#addform").validator({
        disable: true
    });

    $("#addform").validator().on("submit", function (e) {
        if (e.isDefaultPrevented()) {
            return false;
        } 
        $.ajax({
            type: "POST",
            url: "worker.php",
            data: $("#addform").serialize(),
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
                $("#addform").trigger("reset");
            }
        });
        return false;
    });
});
</script>
</body>
</html>