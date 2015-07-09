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
				<a href="<?php echo site_url("admin"); ?>" class="navbar-brand"><img src="../../../images/spa_logo.png"></a>
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
						<li class="active"><a href="#"> Company Profile</a></li>
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
			</ul> <!-- nav in -->
		</div> <!-- sidebar-nav navbar-collapse -->
	</div> <!-- sidebar -->

	<div id="page-wrapper">
		<div class="panel panel-default" style="width: 600px; margin-top: 30px;">
			<div class="panel-heading">
				<h3 class="panel-title">Company Profile</h3>
			</div>
			<div class="panel-body">
				<form id="form" method="get" action="<?php echo site_url("admin/editCompanyProfile"); ?>" class="form-horizontal" role="form">
					<input type="hidden" name="comId" value="<?php echo $companyId; ?>" />
					<input type="hidden" name="uniqCod" value="<?=$uniqueCode; ?>" />
				</form>
			</div>
		</div>
	</div> <!-- end of page-wrapper -->

</div><!-- end of #wrapper -->
<!-- All Javascript at the bottom of the page for faster page loading -->
    
<!-- If no online access, fallback to our hardcoded version of jQuery -->
<script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-1.11.2.min.js"><\/script>')</script>    
<!-- Bootstrap JS -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>    

<script src="http://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.0.2/metisMenu.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>includes/js/v2/metisMenuSettings.js"></script>

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