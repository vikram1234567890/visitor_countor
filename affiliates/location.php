

<script>
var lat,lng;
//http:localhost:8070/web_projects/php/location.php?user_id=89&url_redirect=https://www.google.co.in&table_name=location

var url_redirect = <?php echo $_POST['url_redirect'] ?>;
var table_name = <?php echo $_POST['table_name'] ?>;
var user_id = <?php echo $_POST['user_id'] ?>;

/*
var url_redirect = <?php echo $_GET['url_redirect'] ?>;
var table_name = <?php echo $_GET['table_name'] ?>;
var user_id = <?php echo $_GET['user_id'] ?>;*/

getLocation();
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
      //  x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    
		lat=position.coords.latitude;
		lng=position.coords.longitude;
		
		var array = {lat:lat,lng:lng,user_id:user_id,url_redirect:url_redirect,table_name:table_name};
	post_to_url("store_location.php", array, "post");
}
 function post_to_url(path, params, method) {
    method = method || "post"; // Set method to post by default, if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    var addField = function( key, value ){
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", value );

        form.appendChild(hiddenField);
    }; 

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            if( params[key] instanceof Array ){
                for(var i = 0; i < params[key].length; i++){
                    addField( key, params[key][i] )
                }
            }
            else{
                addField( key, params[key] ); 
            }
        }
    }

    document.body.appendChild(form);
    form.submit();
}

</script>



