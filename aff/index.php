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
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="assets/favicon.ico">
<title>Dashboard</title>
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

	date_default_timezone_set("Asia/Kolkata");
$date=date("Y-m-d");


require_once('show_errors.php');

		require_once('config.php');
if (!isset($_SESSION["indication_user"]) ) {
 
    echo '<script language="javascript">window.location.href ="login.html"</script>';
 exit;
}

 require_once("navigation.php");
$user_id=$_SESSION["indication_user"];
	
//Stats
$gettotal = mysqli_query($con, "SELECT COUNT(c.id) AS `count` FROM counts c,links l where c.link_id= l.id and l.user_id=$user_id");

$resultgettotal = mysqli_fetch_assoc($gettotal);

$getday = mysqli_query($con, "SELECT COUNT(c.id) AS `count` FROM counts c,links l where c.link_id= l.id and l.user_id=$user_id and c.date like '%$date%'");
$resultgetday = mysqli_fetch_assoc($getday);

$getweek = mysqli_query($con, "SELECT COUNT(c.id) AS `count` FROM counts c,links l where c.link_id= l.id and l.user_id=$user_id and WEEKOFYEAR(c.`date`) = WEEKOFYEAR(NOW())");
$resultgetweek = mysqli_fetch_assoc($getweek);

$getmonth = mysqli_query($con, "SELECT COUNT(c.id) AS `count` FROM counts c,links l where c.link_id= l.id and l.user_id=$user_id and YEAR(c.`date`) = YEAR(NOW()) AND MONTH(c.`date`) = MONTH(NOW())");
$resultgetmonth = mysqli_fetch_assoc($getmonth);



?>

<div class="margin">
    <div id="loading">
<img id="loading-image" src="ajax-loader.gif" alt="Loading..."  height="42" width="42"/>
</div>

<h1 class="page-header">Dashboard</h1>
<div class="row placeholders">
<div class="col-xs-6 col-sm-3 placeholder">
<span class="badge"><?php echo $resultgettotal["count"]; ?></span>
<h4>All Time</h4>
<span class="text-muted">Hits from clicks</span>
</div>
<div class="col-xs-6 col-sm-3 placeholder">
<span class="badge"><?php echo $resultgetday["count"]; ?></span>
<h4>Day</h4>
<span class="text-muted">Hits today</span>
</div>
<div class="col-xs-6 col-sm-3 placeholder">
<span class="badge"><?php echo $resultgetweek["count"]; ?></span>
<h4>Week</h4>
<span class="text-muted">Hits this week</span>
</div>
<div class="col-xs-6 col-sm-3 placeholder">
<span class="badge"><?php echo $resultgetmonth["count"]; ?></span>
<h4>Month</h4>
<span class="text-muted">Hits this month</span>
</div>
</div>
<h2 class="sub-header">Affiliate Links</h2>
<div class="form-group has-feedback">
<input type="text" id="search" name="search" class="form-control" placeholder="Search your links..."> <span id="counter" class="text-muted form-control-feedback"></span>
</div>
<div class="table-responsive">
<table class="table table-striped results">
<thead>
<tr>
<th>Name</th>
<th>Share link</th>
<th>Clicks</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php

$getlinks = mysqli_query($con, "SELECT  links.id,links.name, links.url,links.abbreviation, count(counts.id) AS `count`, links.protect, links.password FROM `links` LEFT JOIN `counts` ON links.id = counts.link_id  where links.user_id=$user_id group  BY links.id desc");

while($links = mysqli_fetch_assoc($getlinks)) {
    echo "<tr>";
    echo "<td>" . $links["name"] . "</td>";
	$link=WEBSITE."/m/aff/get.php?id=" . $links["abbreviation"] ;
    echo "<td><a href= \"".$link ."\" target=\"_blank_\">".$link."</a></td>";
    echo "<td><span class=\"badge\">" . $links["count"] . "</span></td>";
    echo "<td> <a class=\"delete_affiliate_link\" data-id=\"" . $links["id"] . "\">Delete</a> | <a  onclick=\"js_location( ". $links["id"] . ")\">Show clicks locations</a> </td>";
    echo "</tr>";
}





?>
</tbody>
</table>
</div>
   <div id="dialog" style="height: auto; width: auto;">
        <div id="map" style="height: 300px; width: 300px;">
        </div>
    </div>



<div id="id01" class="modal">

 <div class="modal-content animate" >
     
     
     
     
     </div>

</div>
    </div><!-- /#wrapper -->
 <script src="assets/bower_components/jquery/dist/jquery.min.js" type="text/javascript" charset="utf-8"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQ6m5bK5P1Y_SOwFF8K71t5HDYnZf45NY"
        async defer></script>
      
 <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
     <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
 
   <script src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.1/jquery-ui.js" type="text/javascript"></script>
    <link href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.1/themes/blitzer/jquery-ui.css"
        rel="stylesheet" type="text/css" />
        
     <script src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.1/jquery-ui.js" type="text/javascript"></script>
    <link href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.1/themes/blitzer/jquery-ui.css"
        rel="stylesheet" type="text/css" />
 
 <script>
	var infowindow = new google.maps.InfoWindow({});

	var marker, i;
	
  function js_location(linkId){
	$.ajax({
                    type: "POST",
                    url: "get_locations.php",
                    data: "link_id="+linkId,
                    error: function() {
                        $.notify({
                            message: "Ajax query failed!",
                            icon: "glyphicon glyphicon-warning-sign",
                        },{
                            type: "danger",
                            allow_dismiss: true
                        });
                    },
                    success: function(data) {
                   
                      display(data);
                    }
                });
    }

      function display(arr) {
          var a=jQuery.parseJSON(arr);
        // alert(arr);
     var locations= a;
              //locations=arr;
  var len= locations['lat'].length;

          $("#dialog").dialog({
                    modal: true,
                    title: "Showing clicks of "+len+" location",
                    width: "auto",
                    hright: "auto",
                    buttons: {
                        Close: function () {
                            $(this).dialog('close');
                        }
                    },
                    open: function () {


 

 	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 2,
		center: new google.maps.LatLng(18, 70),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});




	for (i = 0; i <len; i++) {
	 
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations['lat'][i], locations['lng'][i]),
				center: new google.maps.LatLng(locations['lat'][i], locations['lng'][i]),

			map: map
		});

		google.maps.event.addListener(marker, 'click', (function (marker, i) {
			return function () {
			    displayLocation(locations['lat'][i], locations['lng'][i])

			}
		})(marker, i));
	}
                    }
	});
}




function displayLocation(latitude,longitude){
    var geocoder;
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(latitude, longitude);

    geocoder.geocode(
        {'latLng': latlng}, 
        function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    var add= results[0].formatted_address ;
                    var  value=add.split(",");

                    count=value.length;
                    country=value[count-1];
                    state=value[count-2];
                    city=value[count-3];
                    				infowindow.setContent(city+','+state+','+country);
				infowindow.open(map, marker);
              
                }
                else  {
                   
                }
            }
            else {
          
            }
        ;
        }
    );

}

    </script>
    
    
    <!-- JavaScript -->
    <script src="js/bootstrap.js"></script>
<script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/bootbox.js/bootbox.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/js-cookie/src/js.cookie.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/modernizr-load/modernizr.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript"> 
getLocation();
 $("td").on("click", ".delete_affiliate_link", function() {
        var id = $(this).data("id");
        bootbox.confirm("Are you sure you wish to delete this link?", function(result) {
            if (result == true) {
                
document.getElementById("loading").style.display = "block" ;

                $.ajax({
                    type: "POST",
                    url: "worker.php",
                    data: "action=delete_affiliate_link&id="+ id +"",
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
                    success: function(string) {
                        $.notify({
                            message: "Link deleted!",
                            icon: "glyphicon glyphicon-ok",
                        },{
                            type: "success",
                            allow_dismiss: true
                        });
                        
document.getElementById("loading").style.display = "none" ;

                        console.log(string);
                        setTimeout(function() {
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



    
    function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
      //  x.innerHTML = "Geolocation is not supported by this browser.";
    }
    
    }
  


function showPosition(position) {
           var lat,lng;

var user_id = <?php echo $user_id; ?>;
		lat=position.coords.latitude;
		lng=position.coords.longitude;
		
		var array = {lat:lat,lng:lng,user_id:user_id,login:true};
		$.ajax({
                    type: "POST",
                    url: "store_location.php",
                    data: array,
                    error: function() {
                        $.notify({
                            message: "Ajax query failed!",
                            icon: "glyphicon glyphicon-warning-sign",
                        },{
                            type: "danger",
                            allow_dismiss: true
                        });
                    },
                    success: function() {
                   
                      
                    }
                });
//	post_to_url("store_location.php", array, "post");
}

document.getElementById("loading").style.display = "none" ;




	</script>
  </body>
</html>