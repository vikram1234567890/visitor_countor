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
</style>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="assets/favicon.ico">
<title>Affiliate LInks</title>
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
        <div id="loading">
<img id="loading-image" src="ajax-loader.gif" alt="Loading..."  height="42" width="42"/>
</div>

<h2 class="sub-header">Affiliate links</h2>
<div class="form-group has-feedback">
<input type="text" id="search" name="search" class="form-control" placeholder="Search your links..."> <span id="counter" class="text-muted form-control-feedback"></span>
</div>
<div class="table-responsive">
<table class="table table-striped results">
<thead>
<tr>
<th>Icon</th>
<th>Name</th>
<th>Affiliate Links</th>
</tr>
</thead>
<tbody>
<?php

//url NOT IN (SELECT url FROM links WHERE links.user_id=$user_id) and 
	 $getlinks = mysqli_query($con, "SELECT name,img_url,url FROM `available_links` WHERE protect=0");
$available = mysqli_query($con, "select links.url from available_links,links where links.user_id=$user_id and links.url=available_links.url and available_links.protect =0");
 $ava="";
  
while($avil=mysqli_fetch_assoc($available)){
$ava.=",".$avil['url'];
 
}
while($links = mysqli_fetch_assoc($getlinks)  ) {
    
    
    echo "<tr>";
    echo "<td><img src=".$links["img_url"] ."  height=\"42\" width=\"42\"></td>";
    echo "<td>" . $links["name"] ."</td>";
    if( strpos($ava,$links['url'])!== false){
      echo "<td><a href=\"index.php\" >Link added to dashboard</a> </td>";
    }
else{
    echo "<td> <button onclick=\"addLink('" . $links["url"] ."')\" >Add to dashboard</button>
 </td>";
}
 
    echo "</tr>";
}
mysqli_close($con);
?>
<script src="assets/bower_components/jquery/dist/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/bootbox.js/bootbox.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/js-cookie/src/js.cookie.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/modernizr-load/modernizr.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">  
document.addEventListener('contextmenu', event => event.preventDefault());
function addLink(url1){

document.getElementById("loading").style.display = "block" ;

   $.ajax({  
        type: "POST",  
        url: "store_links.php",  
        data: "url="+url1,  
		  
      
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
        success: function(dataString) {  
            $.notify({
                            message: dataString,
                            icon: "glyphicon glyphicon-ok",
                        },{
                            type: "success",
                            allow_dismiss: true
                        });
                        setTimeout(function() {
                            console.log(dataString);
                            
document.getElementById("loading").style.display = "none" ;

                        	window.location.reload();
                        }, 1000);
        }  
    }); 
}

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
</div>

</body>
</html>