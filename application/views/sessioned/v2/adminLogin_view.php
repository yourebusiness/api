<div id="wrapper">
	<nav class="navbar navbar-default" style="margin-bottom: 0">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="<?php echo site_url('admin'); ?>" class="navbar-brand"><img src="../../../images/spa_logo.png"></a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <?php echo $username; ?><span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
							<li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Log-out</a></li>
						</ul>
					</li>
				</ul>
			</div> <!-- #bs-example-navbar-collapse-1 ->
		</div> <!-- container-fluid -->
	</nav>

  	<div class="navbar-default sidebar" role="navigation">
		<div class="sidebar-nav navbar-collapse">
			<ul class="nav in" id="menu">
				<li>
					<a href="#"><i class="fa fa-dashboard fa-fw"></i> Administration <i class="fa fa-plus"></i></a>
					<ul class="nav nav-second-level collapse" aria-expanded="false">
						<li class="active"><a href="<?php echo site_url("admin/adminLogin") . "?v=companyProfile"; ?>"> Company Profile</a></li>
						<li><a href="#"> Massuers</a></li>
						<li><a href="#"> Users</a></li>
					</ul>
				</li>
				<li>
					<a href="#"><i class="fa fa-car"></i> Services <i class="fa fa-plus"></i></a>
			          <ul class="nav nav-second-level collapse" aria-expanded="false">
			            <li><a href="#"> List of Services</a></li>
			            <li><a href="#"> Add Service</a></li>
			          </ul>
				</li>
		        <li>
		          <a href="#"><i class="fa fa-money"></i> Transactions <i class="fa fa-plus"></i></a>
		          <ul class="nav nav-second-level collapse" aria-expanded="false">
		            <li><a href="#"> Add New Transaction</a></li>
		          </ul>
		        </li>
		        <li>
		          <a href="#"><i class="fa fa-bar-chart"></i> Reports <i class="fa fa-plus"></i></a>
		          <ul class="nav nav-second-level collapse" aria-expanded="false">
		            <li><a href="#"> Per Masseur</a></li>
		            <li><a href="#"> Per Service</a></li>
		          </ul>
		        </li>
			</ul> <!-- end of id=menu -->
		</div> <!-- sidebar-nav navbar-collapse -->
	</div>

	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<h4>Log-in</h4>
				<div class="col-md-4 col-md-offset-3">
					
					<div class="alert alert-danger" role="alert" id=>
						<p>
						  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						  <span class="sr-only">Error:</span>
						  <span id="errorMessage">Fill-out the form completely.</span>
						</p>
				    </div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h2 class="panel-title">Log-in to enter</h2>
						</div>
						<div class="panel-body">
							<form method="post" action="<?=site_url("admin/checkLogin");?>" id="adminLogin">
								<div class="form-group">
									<input type="email" class="form-control" placeholder="Email" name="username" id="username" />
								</div>
								<div class="form-group">
									<input type="password" class="form-control" placeholder="Password" name="password" id="password" />
								</div>
								<button type="submit" class="btn btn-primary" id="login">Log-in</button>
							</form>
						</div>
					</div> <!-- panel panel-default -->
				</div>
			</div> <!-- row -->
		</div>
	</div> <!-- #page-wrapper -->

</div><!-- #wrapper -->

<!-- All Javascript at the bottom of the page for faster page loading -->
    
<!-- If no online access, fallback to our hardcoded version of jQuery -->
<script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-1.11.2.min.js"><\/script>')</script>    

<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<script src="http://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.0.2/metisMenu.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/v2/metisMenuSettings.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/v2/adminLogin.js"></script>

<script type="text/javascript">
	$(function() {
		var first_li_tag = $(".sidebar .nav li").first();
		first_li_tag.addClass("active");

		var last_i_tag = $(".sidebar .nav li a").first().children(":last");
		last_i_tag.removeClass("fa-plus");
		last_i_tag.addClass("fa-minus");
	});
</script>
</body>
</html>