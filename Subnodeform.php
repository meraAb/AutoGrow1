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
      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Form">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseForm" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-area-chart"></i>
            <span class="nav-link-text">SubNode</span>
          </a>
		  <ul class="sidenav-second-level collapse" id="collapseForm">
            <li>
              <a href="subnodeform.php">Create SubNode</a>
            </li>
			<li>
              <a href="DeleteNode.php">Delete SubNode</a>
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
    <div class="container-fluid">
    </div>
	<div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Create SubNode<small></small></h2>           
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                
					  <form name="subnode" method="post" action="php/AddSubNode.php">
                        <p>
                            <label for="Name" >
								Name SubNode : <input tyte="text" name="Sub_Name"></br> 
							</label>
                        </p>
                       
					   <p>
                            <label for="Protocol" >
                            <span> Protocol : </span>
							
							<select name="Protocol" id="Protocol">
							
								<option value="">Select...</option>
								<option value="TCP/IP">TCP/IP</option>
								<option value="GSM">GSM</option>
								<option value="3G">3G</option>
							</select>
                            </label>
                        </p>
						<p>
						<?php
									require "php/opendb.php";
									
									$sql = "SELECT Name FROM sensors ";
									$data = mysqli_query($conn, $sql);
									$source = array();
									
									while ($row = mysqli_fetch_array($data)) 
										{
											array_push($source, $row['Name']);
										}
                        
									$arrlength = count($source);
						?>
						
                            <label for="Port" >
                            <span> Select sensor by port : </span> </br>
							<span> PortA : </span> 
							<select name='PortA' id='PortA'>
							
								<option>Select...</option>
								<?php
									for($x=0;$x < $arrlength; $x++)
										{
											echo "<option>$source[$x]</option>";
										}            
								?>
							</select>
								
							
							<span> PortB : </span> 
							<select name="PortB" id="PortB">
							
								<option value="">Select...</option>
								<?php
									for($x=0;$x < $arrlength; $x++)
										{
											echo "<option>$source[$x]</option>";
										}            
								?>
							</select>
							
							<span> PortC : </span> 
							<select name="PortC" id="PortC">
							
								<option value="">Select...</option>
								<?php
									for($x=0;$x < $arrlength; $x++)
										{
											echo "<option>$source[$x]</option>";
										}            
								?>
							</select>
							<br/>
							<br>
							<span> PortD : </span> 
							<select name='PortD' id='PortD'>
							
								<option>Select...</option>
								<?php
									for($x=0;$x < $arrlength; $x++)
										{
											echo "<option>$source[$x]</option>";
										}            
								?>
							</select>
							
							<span> PortE : </span> 
							<select name='PortE' id='PortE'>
							
								<option>Select...</option>
								<?php
									for($x=0;$x < $arrlength; $x++)
										{
											echo "<option>$source[$x]</option>";
										}            
								?>
							</select>
                            </label>
                        </p>

						
                     <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						  <button class="btn btn-primary" type="reset">Reset</button>
                          <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
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
			window.alert("Try again!!!");
			window.location.href = 'login.php';
		</script>
        <?php
    }
    ?>
</body>

</html>
