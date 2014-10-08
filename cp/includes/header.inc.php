<?php @session_start(); if(@$_GET['theme']!='') {$_SESSION['t'] = (int)$_GET['theme'];} else { $_SESSION['t'] = 1; } $CONFIG['theme'] = (int)@$_SESSION['t'];?>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/theme<?php echo $CONFIG['theme']; ?>.css">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<link href="css/bootstrap-editable.css" rel="stylesheet"/>
<script src="js/bootstrap-editable.min.js"></script>		
<script src="js/jquery.form.js"></script>
<style> html body { font-family: 'Ubuntu', 'sans-serif'; } .form-control.input-sm { width: 120px; } .navbar { background-color: #004918 !important; } .navbar { min-height: 105px !important; background-image: none !important; } .btn-danger { background-color: #e83c28 !important; background-image:  none !important; } .btn-success { background-color: #004918 !important; background-image:  none !important; } </style>

<!-- Fixed navbar -->
<div class="navbar navbar-default" role="navigation" style="margin-top: 15px;">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
						<?php
							if(isset($_SESSION["username"])){
								echo '
								<ul class="nav navbar-nav navbar-left">
									<li><a href="#">Control Panel</a></li>
								</ul>
								';
							}
						?>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<a class="navbar-brand"><img style="margin-top: -15px; margin-left: 350px; height: 100px;" src="./images/tb.jpg"/></a>
			</ul>
			<ul class="nav navbar-nav navbar-right">
										<li class="active"><a href="index.php">Home</a></li>
				<?php
				if(isset($_SESSION["username"])){
					echo '
					<li><a data-toggle="modal" href="metcreator.php" data-target="#myModal">MetCreator</a></li>
					<li><a href="logout.php">Logout</a></li>
					';
				}
				?>

			</ul>
		</div><!--/.nav-collapse -->
	</div>
</div>