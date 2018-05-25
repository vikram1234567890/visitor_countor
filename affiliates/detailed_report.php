<!DOCTYPE html>
<html lang="en">
  <head>
 <style>
.margin {
    
    margin: 10px 10px 10px 10px;
    
}
#map {
        height: 400px;
        width: 100%;
       }
</style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Detailed report</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
  </head>

  <body>
<?php



session_start();
require_once("navigation.php");
require_once("session_check.php");

require_once("dbconnect.php");
$user_id=$_SESSION["indication_user"];

 $php_location = array(
          'lat' => array(),
          'lng'=>array()

    );

    
    
$latlng = mysqli_query($con, "SELECT lat,lng FROM approximate_location,counts WHERE approximate_location.ip=counts.ip and counts.link_id in (SELECT links.id from links WHERE links.user_id=$user_id)");


	$i=0;
	while($a= mysqli_fetch_array($latlng)){
	$php_location['lat'][$i]=$a['lat'];
	$php_location['lng'][$i]=$a['lng'];

	$i++;
}

    $len=sizeof($php_location['lat']);






?>

<div class="margin">
    
<h1 class="page-header">Detailed report</h1>


<h2 class="page-header"><?php echo "Showing approximate location of ".$len." click(s)"; ?></h2>
    <div id="map"></div></div>
    
     <script src="assets/bower_components/jquery/dist/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="js/bootstrap.js"></script>
<script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/bootbox.js/bootbox.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/js-cookie/src/js.cookie.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/modernizr-load/modernizr.js" type="text/javascript" charset="utf-8"></script>
<script src="assets/bower_components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
     var locations= <?php echo json_encode($php_location); ?>;
  var len= <?php echo $len; ?>;
      function initMap() {
          
      
 

 	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 2,
		center: new google.maps.LatLng(18, 70),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var infowindow = new google.maps.InfoWindow({});

	var marker, i;
	


	for (i = 0; i <len; i++) {
	 

		marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations['lat'][i], locations['lng'][i]),
				center: new google.maps.LatLng(locations['lat'][i], locations['lng'][i]),

			map: map
		});

		google.maps.event.addListener(marker, 'click', (function (marker, i) {
			return function () {
				infowindow.setContent(displayLocation(locations['lat'][i], locations['lng'][i]));
				infowindow.open(map, marker);
			}
		})(marker, i));
	}
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
              
                }
                else  {
                   
                }
            }
            else {
               
            }
        }
    );
    return city+','+state+','+country;
}



    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQ6m5bK5P1Y_SOwFF8K71t5HDYnZf45NY&callback=initMap"
        async defer></script>
  </body>
</html>
