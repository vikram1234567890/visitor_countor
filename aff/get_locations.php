<?php
require_once('dbconnect.php');
    
         $link_id=$_POST['link_id'];
     // $link_id=66;
$php_location = array(
          'lat' => array(),
          'lng'=>array()

    );
    
    
    
    
      
$latlng = mysqli_query($con, "SELECT DISTINCT lat,lng FROM approximate_location,counts where approximate_location.ip=counts.ip and counts.link_id=$link_id");

echo mysqli_error($con);

	$i=0;
	while($a= mysqli_fetch_array($latlng)){
	$php_location['lat'][$i]=$a['lat'];
	$php_location['lng'][$i]=$a['lng'];

	$i++;
}

   // $len=sizeof($php_location['lat']);
echo json_encode($php_location);
mysqli_close($con);
    ?>