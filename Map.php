<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SB Admin - Start Bootstrap Template</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
</head>
<?php
	session_start();	
	if (isset($_SESSION["email1"])) { 
				?>
<script>
var map;
var rectangle;
function initMap() {
    map = new google.maps.Map(
    document.getElementById("map"), {
        center: new google.maps.LatLng(37.5506, 22.2025),
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.SATELLITE
		}); //some code omitted here
		var	drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.MARKER,
        drawingControl: true,
        drawingControlOptions: {
            drawingModes: [
            google.maps.drawing.OverlayType.RECTANGLE]
			},
        rectangleOptions: {
            editable: true,
			draggable: true
            // some code omitted here
			}
		});
		google.maps.event.addListener(drawingManager, 'overlaycomplete', function (event) {
        // when the overlay is complete, update the html form fields
        update_bounds_fields(event);
        rectangle = event.overlay;

        // don't allow the user to draw more than 1 rectangle
        // (disable drawing controls after the first one has been drawn)
        drawingManager.setDrawingMode(null);
        drawingManager.setOptions({
            drawingControl: false
        });

        // The drawn area is editable with mouse so also want to
        // update the html form when the area is edited
        google.maps.event.addListener(event.overlay, 'bounds_changed', function () {
            update_bounds_fields(event);
			});
		});

		drawingManager.setMap(map);
		// Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
}
google.maps.event.addDomListener(window, "load", initialize);
function update_bounds_fields(event) {
    document.getElementById('northwest-latitude').value = event.overlay.getBounds().getNorthEast().lat();
    document.getElementById('northwest-longitude').value = event.overlay.getBounds().getSouthWest().lng();
    document.getElementById('southeast-latitude').value = event.overlay.getBounds().getSouthWest().lat();
    document.getElementById('southeast-longitude').value = event.overlay.getBounds().getNorthEast().lng();
}
function update_map_bounds() {
    var north = parseFloat(document.getElementById('northwest-latitude').value);
    var south = parseFloat(document.getElementById('southeast-latitude').value);
    var east = parseFloat(document.getElementById('southeast-longitude').value);
    var west = parseFloat(document.getElementById('northwest-longitude').value);
    if (!isNaN(north) && !isNaN(south) && !isNaN(west) && !isNaN(east)) {
        var NE = new google.maps.LatLng(north, east);
        var SW = new google.maps.LatLng(south, west);
        var newRect = new google.maps.LatLngBounds(SW,NE);
        rectangle.setBounds(newRect);
        map.fitBounds(newRect);
    }
}
function removeRectangle() {  
    rectangle.setMap(null); 
	location.reload();
 } 
</script>
<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="index.php">Start Bootstrap</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="CreateFarm">
          <a class="nav-link" href="map.php">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text">Create Farm</span>
          </a>
        </li>
		<?php
				require "php/opendb.php";
				
				$sql = "SELECT RegionName FROM Farms";
				$data = mysqli_query($conn, $sql);
				$source = array();
									
				while ($row = mysqli_fetch_array($data)) 
					{	
						array_push($source, $row['RegionName']);
					}
                        
					$arrlength = count($source);
			?>
		<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Farm">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseFarm" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-area-chart"></i>
            <span class="nav-link-text">Farms</span>
          </a>
		  <ul class="sidenav-second-level collapse" id="collapseFarm">

			<?php
				for($x=0;$x < $arrlength; $x++)
					{
						echo "<li><a>$source[$x]</a></li>";
					}
			?>
			
          </ul>
        </li>     
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <form class="form-inline my-2 my-lg-0 mr-lg-2">
            <div class="input-group">
              <input class="form-control" type="text" placeholder="Search for...">
              <span class="input-group-btn">
                <button class="btn btn-primary" type="button">
                  <i class="fa fa-search"></i>
                </button>
              </span>
            </div>
          </form>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
        </li>
      </ul>
    </div>
  </nav>
    <div class="content-wrapper">
		<div class="row" role="main">
          <div class="">
            <div class="page-title">
              </div>
            </div>
            <div class="col">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Create Farm<small></small></h2>           
                    <div class="clearfix"></div>
                  </div>
					<div class="x_content">
						<div id="map" style="width:700px; height:300px; border: 2px solid #3872ac;"></div>
						<input id="pac-input" class="controls" type="text" placeholder="Search Box">
					</div>
				</div>
			</div>
		</div>
				<div class="col">
				</br/>
					<form name="bounds_form" action="php/DataFromMap.php" method="post">
						<b>Region Name : </b><br/> 
						<input type="text" size="20" name="region_name"/> <br/>
						<b>Description : </b><br/><textarea name="region_desc" cols="20" rows="3"></textarea><br/>
						<input type="text" id="northwest-latitude" name="nw-lat" onchange="update_map_bounds();" />
						<br/>
						<input type="text" id="northwest-longitude" name="nw-lng" onchange="update_map_bounds();" />
						<br/>
						<input type="text" id="southeast-latitude" name="se-lat" onchange="update_map_bounds();" />
						<br/>
						<input type="text" id="southeast-longitude" name="se-lng" onchange="update_map_bounds();" />
						<br/>
						<input type="submit" id="submit-button" onclick="validate_bounds(bounds_form)" />
						<input onclick="removeRectangle();" type=button value="Remove Rectangle"> 
					</form>
					</div>
		</div>
   <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
   <footer class="sticky-footer">
      <div class="container">
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.php">Logout</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Page level plugin JavaScript-->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="js/sb-admin-datatables.min.js"></script>
    <script src="js/sb-admin-charts.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAj4Pcgxred5R824agRiRcgCbEC7Pgkjws&libraries=drawing,places&callback=initMap"
         async defer></script>
	<script type="text/javascript" > 
		$(document).ready(function() {
			$('#collapseFarm li').click(function() {
				//Get the id of list items
				var name = $(this).text();
				window.location.href = 'Farm.php?name='+name;
				});
			});
			
	</script>
  </div>
   <?php
	}
	else{ ?>
		<script>
			window.alert("Try again");
			window.location.href = 'login.php';
		</script>
        <?php
    }
    ?>
</body>
</html>