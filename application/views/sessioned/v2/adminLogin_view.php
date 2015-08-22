<div id="wrapper">
	<!-- the whole menu bar -->
        <nav class="navbar navbar-default">
            <div class="myContainer">
                <div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a href="<?=site_url('admin'); ?>" class="navbar-brand"><img src="../../../images/spa_logo.png"></a>
				</div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="menu-nav menubar-right">
                        <li>
                            <a href="#"><i class="fa fa-user"></i> <?=$username;?> <span class="down-arrow">&#9660;</span></a>
                            <ul class="sub-menu">
                                <li><a href="#"><i class="fa fa-user-md"></i> Profile</a></li>
                                <li><a href="<?=site_url('api/logout'); ?>"><i class="fa fa-sign-out"></i> Log-out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div> <!-- container -->
        </nav> <!-- menubar -->

        <!-- the left side menu -->
        <div class="sidebar-default sidebar" role="navigation">
            <div class="sidebar-nav sidebar-collapse">
                <ul class="nav in" id="menu">
                    <li class="active">
                        <a href="#"><i class="fa fa-dashboard fa-fw"></i> Administration <i class="fa fa-minus"></i></a>
                        <ul class="nav nav-second-level collapse in" aria-expanded="false">
                            <li class="active"><a href="<?=site_url("admin/adminLogin") . "?v=companyProfile"; ?>"> Company Profile</a></li>
                            <li><a href="<?=site_url('admin/masseurs'); ?>"> Massuers</a></li>
                            <li><a href="<?=site_url('admin/users'); ?>"> Users</a></li>
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
                </ul>
            </div>
        </div> <!-- end of the left side menu -->
	
	<div id="page-wrapper"> <!-- the content -->
		<div class="container-fluid" style="margin-top: 30px;">
			<div class="row">
				<div class="col-md-4 col-md-offset-3">
					
					<div class="alert alert-danger" role="alert" id="alert">
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

</body>
</html>