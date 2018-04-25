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
				require "php/opendb.php";
				$name_kthma = $_GET['name'];
				// Ektelw to erwthma gia ton pinaka sensors
				$sql = "SELECT * FROM Farms";
				$data = mysqli_query($conn, $sql);
				$source = array();
								
				while ($row = mysqli_fetch_array($data)) 
					{	
						if ($row['RegionName'] == $name_kthma)
						{
							$NorhtWestLat = $row['NorhtWestLat'];
							$NorhtWestLong = $row['NorhtWestLong'];
							$SouthEastLat = $row['SouthEastLat'];
							$SouthEastLong = $row['SouthEastLong'];
						}
						array_push($source, $row['RegionName']);
					}    
					$arrlength = count($source);  
					
				$sql1 = "SELECT Sub_Name,markerLatitude,markerLongtitude FROM markers WHERE RegionName='$name_kthma'";
				$data1 = mysqli_query($conn, $sql1);
				$Markers = array();
				$PositionLat = array();
				$PositionLong = array();
								
				while ($row = mysqli_fetch_array($data1)) 
					{	
						array_push($Markers, $row['Sub_Name']);
						array_push($PositionLat, $row['markerLatitude']);
						array_push($PositionLong, $row['markerLongtitude']);
					}    
					$Marker_length = count($Markers);
			?>
<script>  
	var rectangle;
	var markers = {};
	var marker;
	var markerId;
	var infoWnd = null;
	

	function initMap() { 
		var lat_lng = {lat: <?php echo $NorhtWestLat;?>, lng: <?php echo $SouthEastLong;?>};     
		var map = new google.maps.Map(document.getElementById('map'), {  
		zoom: 17,  
		center: lat_lng,  
		mapTypeId: google.maps.MapTypeId.SATELLITE  
	});  

	rectangle = new google.maps.Rectangle({  
		strokeColor: '#FF0000',  
		strokeOpacity: 0.8,  
		strokeWeight: 2,  
		fillColor: '#FF0000',  
		fillOpacity: 0.35, 
		center: lat_lng,
		draggable:false,
		bounds: new google.maps.LatLngBounds(  
				new google.maps.LatLng(<?php echo $NorhtWestLat;?> , <?php echo $NorhtWestLong; ?>),  
				new google.maps.LatLng(<?php echo $SouthEastLat;?> , <?php echo $SouthEastLong;?>))  
  });
    rectangle.setMap(map);  

	var getMarkerUniqueId= function(lat, lng) {
		return lat + '_' + lng;
	}

	var getLatLng = function(lat, lng) {
		return new google.maps.LatLng(lat, lng);
	};
	
	
	
	
	function showMarker() { 
	  //alert("Show Markers");
//DEN DOULEUEI SWSTA TO FOR!!!!!!!!!!!!	  
	 <?php for($i=0; $i<$Marker_length; $i++) { ?>
	  marker = new google.maps.Marker({
          position: {lat: <?php echo $PositionLat[$i];?>, lng: <?php echo $PositionLong[$i];?>},
		  html: "<?php echo $Markers[$i]; ?>",
          map: map
        });
	
	//(3)Set a flag property which stands for the editing mode.
    marker.set("editing", false);
    //(4)Create a div element to display the HTML strings.
    var htmlBox = document.createElement("div");
    htmlBox.innerHTML = marker.html || "";
    htmlBox.style.width = "200px";
    htmlBox.style.height = "100px";
    
    //(5)Create a textarea for edit the HTML strings.
    var textBox = document.createElement("textarea");
    textBox.innerText = marker.html || "";
    textBox.style.width = "200px";
    textBox.style.height = "100px";
    textBox.style.display = "none";

    //(6)Create a div element for container.
    var container = document.createElement("div");
    container.style.position = "relative";
    container.appendChild(htmlBox);
    container.appendChild(textBox);

	//(7)Create a button to switch the edit mode
    var editBtn = document.createElement("button");
    editBtn.innerText = "Edit";
    container.appendChild(editBtn);
	    
    //(8)Create an info window
    infoWnd = new google.maps.InfoWindow({
      content : container
    });



	//(9)The info window appear when the marker is clicked.
    google.maps.event.addListener(marker, "click", function() {
     infoWnd.open(marker.getMap(), this);
    });	
	//(10)Switch the mode. Since Boolean type for editing property,
    //the value can be change just negation.
    google.maps.event.addDomListener(editBtn, "click", function() {
      marker.set("editing", !marker.editing);
    });
    
    //(11)A (property)_changed event occur when the property is changed.
    google.maps.event.addListener(marker, "editing_changed", function(){
      textBox.style.display = this.editing ? "block" : "none";
      htmlBox.style.display = this.editing ? "none" : "block";
    });
    
    //(12)A change DOM event occur when the textarea is changed, then set the value into htmlBox.
    google.maps.event.addDomListener(textBox, "change", function(){
      htmlBox.innerHTML = textBox.value;
      marker.set("html", textBox.value);
    });

	//(7)Create a button to switch the edit mode
    var submitBtn = document.createElement("button");
    submitBtn.innerText = "Submit";
    container.appendChild(submitBtn);
	
	google.maps.event.addDomListener(submitBtn, "click", function() {
	  window.location.href = 'php/updateMarker.php?RegionName='+("<?php echo $name_kthma; ?>")+'&Sub_Name='+htmlBox.innerHTML+'&position='+marker.position;
    });
	<?php
	}
	?>
	
}	
	window.onload=showMarker;
		
		
	
	var addMarker = rectangle.addListener('click', function(e) { 
		var lat = e.latLng.lat(); // lat of clicked point
		var lng = e.latLng.lng(); // lng of clicked point
		var markerId = getMarkerUniqueId(lat, lng); // an that will be used to cache this marker in markers object.
		marker = createEditableMarker({
			position: {lat: lat, lng: lng},
			html : "Βάλε το όνομα του υποκόμβου...",
			map: map,
			id: 'marker_' + markerId
		});
	markers[markerId] = marker; // cache marker in markers object
    bindMarkerEvents(marker); // bind right click event to marker
	});

	var bindMarkerEvents = function(marker) {
		google.maps.event.addListener(marker, "rightclick", function (point) {
			markerId = getMarkerUniqueId(point.latLng.lat(), point.latLng.lng()); // get marker id by using clicked point's coordinate
				marker = markers[markerId]; // find marker
				removeMarker(marker, markerId); // remove it
		});
	};


	var removeMarker = function(marker, markerId) {
		marker.setMap(null); // set markers setMap to null to remove it from map
		delete markers[markerId]; // delete marker instance from markers object
		window.location.href = 'php/deleteMarker.php?RegionName='+"<?php echo $name_kthma; ?>"+'&Sub_Name='+htmlBox.innerHTML+'&position='+marker.position;
	};
}

function createEditableMarker(options) {
    //(2) Create a marker normally.
    //Marker class accepts any properties even if it's not related with Marker.
    marker = new google.maps.Marker(options);
    
    //(3)Set a flag property which stands for the editing mode.
    marker.set("editing", false);
    
    //(4)Create a div element to display the HTML strings.
    var htmlBox = document.createElement("div");
    htmlBox.innerHTML = options.html || "";
    htmlBox.style.width = "200px";
    htmlBox.style.height = "100px";
    
    //(5)Create a textarea for edit the HTML strings.
    var textBox = document.createElement("textarea");
    textBox.innerText = options.html || "";
    textBox.style.width = "200px";
    textBox.style.height = "100px";
    textBox.style.display = "none";
    
    //(6)Create a div element for container.
    var container = document.createElement("div");
    container.style.position = "relative";
    container.appendChild(htmlBox);
    container.appendChild(textBox);
    
    //(7)Create a button to switch the edit mode
    var editBtn = document.createElement("button");
    editBtn.innerText = "Edit";
    container.appendChild(editBtn);
	    
    //(8)Create an info window
    var infoWnd = new google.maps.InfoWindow({
      content : container
    });
    
    //(9)The info window appear when the marker is clicked.
    google.maps.event.addListener(marker, "click", function() {
      infoWnd.open(marker.getMap(), marker);
    });
    
    //(10)Switch the mode. Since Boolean type for editing property,
    //the value can be change just negation.
    google.maps.event.addDomListener(editBtn, "click", function() {
      marker.set("editing", !marker.editing);
    });
    
    //(11)A (property)_changed event occur when the property is changed.
    google.maps.event.addListener(marker, "editing_changed", function(){
      textBox.style.display = this.editing ? "block" : "none";
      htmlBox.style.display = this.editing ? "none" : "block";
    });
    
    //(12)A change DOM event occur when the textarea is changed, then set the value into htmlBox.
    google.maps.event.addDomListener(textBox, "change", function(){
      htmlBox.innerHTML = textBox.value;
      marker.set("html", textBox.value);
    });
	
	//(7)Create a button to switch the edit mode
    var submitBtn = document.createElement("button");
    submitBtn.innerText = "Submit";
    container.appendChild(submitBtn);
	
	google.maps.event.addDomListener(submitBtn, "click", function() {
	  window.location.href = 'php/saveMarker.php?RegionName='+("<?php echo $name_kthma; ?>")+'&Sub_Name='+htmlBox.innerHTML+'&position='+marker.position;
    });
    return marker;
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
      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Form">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseForm" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-area-chart"></i>
            <span class="nav-link-text">SubNodes</span>
          </a>
		  <ul class="sidenav-second-level collapse" id="collapseForm">
            <li>
              <a href="subnodeform.php">Create SubNode</a>
            </li>
			<li>
              <a href="deleteNode.php">Delete SubNode</a>
            </li>
          </ul>
            
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
           <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseChart" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-area-chart"></i>
            <span class="nav-link-text">Charts</span>
          </a>
        </li>
		 <ul class="sidenav-second-level collapse" id="collapseChart">
            <li>
              <a href="charts.php">Humidity</a>
            </li>
          </ul>
        </li>
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
          <a class="nav-link" href="tables.php">
            <i class="fa fa-fw fa-table"></i>
            <span class="nav-link-text">Tables</span>
          </a>
        </li>     
		</ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
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
                    <h2>Πληροφορίες Κτήματος : <?php echo $name_kthma?><small></small></h2>           
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
					<div id="map" style="width:700px; height:300px; border: 2px solid #3872ac;"></div>
					<a class="nav-link" data-toggle="modal" data-target="#deleteModal">
					<button>Delete SubNode</button></a>
                  </div>
                </div>
              </div>
            </div>
			
			</br/>
			<div class="col">
			</br/>
			<h2> Name of SubNodes : </h2>
				<ul id="subname">       
					<?php
						require "php/opendb.php";
						$sql = "SELECT Sub_Name FROM sub_node";
						$result = mysqli_query($conn, $sql);
						$j=0;
						while($row = $result->fetch_assoc()){
							$subname[$j] = $row['Sub_Name'];
							$j++;	
						}
						for ($i=0;$i<$j;$i++)
							{	
								echo "<li><a>$subname[$i]</a></li>";
							}
					?>
				</ul>	
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
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Είσαι σίγουρος ??</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Delete" to delete the farm .</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Ακύρωση</button>
			<?php echo "<a href=php/DeleteFarm.php?RegionName=",urlencode($name_kthma),">Διαγραφή</a>"; ?>
          </div>
        </div>
      </div>
    </div>
	<!-- Διαγραφή κτήματος-->
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
			window.alert("Εγκατάσταση ιού....!!!");
			window.location.href = 'login.php';
		</script>
        <?php
    }
    ?>
</body>
</html>