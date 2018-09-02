<?php
require_once("session_check.php");

require_once("dbconnect.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
 <style>
 #loading {width: 100%;height: 100%;top: 0px;left: 0px;position: fixed;display: block; z-index: 99}

#loading-image {position: absolute;top: 40%;left: 45%;z-index: 100}

.margin {
    
    margin: 10px 10px 10px 10px;
    
}

</style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>My links</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
  </head>

  <body>


<?php


    
 
require_once('show_errors.php');

		require_once('config.php');
$user_id=$_SESSION["indication_user"];

 require_once("navigation.php");
 ?>
 <div class="margin">
     <div id="loading">
<img id="loading-image" src="ajax-loader.gif" alt="Loading..."  height="42" width="42"/>
</div>

<h2 class="sub-header">My Links</h2>
<div class="form-group has-feedback">
<input type="text" id="search" name="search" class="form-control" placeholder="Search your links..."> <span id="counter" class="text-muted form-control-feedback"></span>
</div>
<div class="table-responsive">
<table class="table table-striped results">
<thead>
<tr>
<th>Icon</th>
<th>Name</th>
<th>Url</th>
<th>Action</th>

</tr>
</thead>
<tbody>
<?php

 $getlinks = mysqli_query($con, "SELECT user_id,name,img_url,url FROM `available_links` WHERE user_id=$user_id");

while($links = mysqli_fetch_assoc($getlinks)) {
    echo "<tr>";
    echo "<td><img src=".$links["img_url"] ."  height=\"42\" width=\"42\"></td>";
    echo "<td>" . $links["name"] . "</td>";
     echo "<td><a href=\"" . $links["url"] ."\" target=\"_blank\"</a>" . $links["url"] ."</td>";
       echo "<td> <a class=\"delete\" data-id=\"" . $links["url"] . "\">Delete</a> | <a href=\"edit.php?url=" . $links["url"] . "\" >Edit</a></td> ";
   
   
    echo "</tr>";
}


mysqli_close($con);
?>


        <!-- JavaScript -->

 <link href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.9/themes/blitzer/jquery-ui.css"
        rel="stylesheet" type="text/css" />
        <script src="assets/bower_components/jquery/dist/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.9/jquery-ui.js" type="text/javascript"></script>
    <link href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.9/themes/blitzer/jquery-ui.css"
        rel="stylesheet" type="text/css" />
 
   <script src="js/bootstrap.js"></script>

<script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>

<script src="assets/bower_components/js-cookie/src/js.cookie.js" type="text/javascript" charset="utf-8"></script>

<script src="assets/bower_components/modernizr-load/modernizr.js" type="text/javascript" charset="utf-8"></script>

<script src="assets/bower_components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js" type="text/javascript" charset="utf-8"></script>

<script src="assets/bower_components/bootbox.js/bootbox.js" type="text/javascript" charset="utf-8"></script>

   <script type="text/javascript">
document.addEventListener('contextmenu', event => event.preventDefault());
$("td").on("click", ".delete", function() {
        var id = $(this).data("id");
        bootbox.confirm("Are you sure you wish to delete this link?", function(result) {
            if (result == true) {
                
document.getElementById("loading").style.display = "block" ;

                $.ajax({
                    type: "POST",
                    url: "worker.php",
                    data: "action=delete&id="+ id +"",
                    error: function() {
                        $.notify({
                            message: "Ajax query failed!",
                            icon: "glyphicon glyphicon-warning-sign",
                        },{
                            type: "danger",
                            allow_dismiss: true
                        });
                        
document.getElementById("loading").style.display = "none" ;
                    },
                    success: function(data) {
                        $.notify({
                            message: "Link deleted!",
                            icon: "glyphicon glyphicon-ok",
                        },{
                            type: "success",
                            allow_dismiss: true
                        });
                         console.log(data);
                        setTimeout(function() {
                            
document.getElementById("loading").style.display = "none" ;

                        	window.location.reload();
                        }, 1000);
                    }
                });
            }
        }); 
    });
 $("#search").keyup(function () {
        $("#counter").removeClass("hidden");
        var term = $("#search").val();
        if (term == "") {
            $("#counter").addClass("hidden");
        }
        var list_tem = $(".results tbody").children("tr");
        var search_split = term.replace(/ /g, "\"):containsi(\"")
        $.extend($.expr[":"], {"containsi": function(elem, i, match, array) {
                return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });
        $(".results tbody tr").not(":containsi(\"" + search_split + "\")").each(function(e){
            $(this).attr("visible","false");
        });
        $(".results tbody tr:containsi(\"" + search_split + "\")").each(function(e){
            $(this).attr("visible","true");
        });
        var count = $(".results tbody tr[visible=true]").length;
        $("#counter").text(count);
        if (count == "0") {
            
            $("#counter").text("0");
        }
    });
    
document.getElementById("loading").style.display = "none" ;

	</script>
	
	</tbody>
</table>
</div>
    </div><!-- /#wrapper -->

	
	

	</body>
	</html>