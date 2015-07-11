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
							<li><a href="<?=site_url('api/logout');?>"><span class="glyphicon glyphicon-log-out"></span> Log-out</a></li>
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
		<div class="panel panel-default" style="width: 700px; margin-top: 30px;">
			<div class="panel-heading">
				<h3 class="panel-title">Company Profile</h3>
			</div>
			<div class="panel-body">
				<form id="form" method="get" action="<?=site_url("admin/editCompanyProfile"); ?>" class="form-horizontal" role="form">
					<input type="hidden" name="comId" value="<?=$companyId;?>" />
					<input type="hidden" name="uniqCod" value="<?=$uniqueCode;?>" />

					<div class="form-group form-group-sm">
						<label for="company" class="col-sm-3 control-label">Company name </label>
						<div class="col-sm-9">
							<input type="text" id="company" name="company" class="form-control" value="<?=$companyInfo[0]["companyName"]; ?>" placeholder="Company name" maxlength="40" aria-describedby="inputError2Status" />
						</div>
					</div>
					<div class="form-group form-group-sm">
			            <div class="col-sm-6">
			            	<label for="province" class="control-label col-sm-3">Province</label>
							<div class="col-sm-9">
								<select class="form-control" id="province" name="province">
									<option value="0">-- Province --</option>
									<?php foreach($provinces as $row) { ?>
									    <option value="<?=$row['id'];?>" <?php echo ($row['id'] == $companyInfo[0]['province']) ? "selected" : ""; ?>><?php echo $row["provinceName"]; ?></option>
									<?php } ?>
								</select>
							</div>
			            </div>
			            <div class="col-sm-6">
			            	<label for="city" class="control-label col-sm-2">City</label>
							<div class="col-sm-10">
								<select id="city" name="city" class="form-control">
									<option value="0">-- City --</option>
									<?php foreach($cities as $city) { ?>
										<option value="<?php echo $city['id']; ?>" <?php echo ($city['id'] == $companyInfo[0]['city']) ? "selected" : ""; ?>><?php echo $city["cityName"]; ?></option>
									<?php } ?>
								</select>
							</div>
			            </div>
			        </div>
			        <div class="form-group form-group-sm">
			        	<div class="col-sm-6">
			        		<label for="address" class="control-label col-sm-3">Address</label>
				            <div class="col-sm-9">
				            	<input id="address" name="address" type="text" class="form-control" placeholder="Address" value="<?=$companyInfo[0]["address"]; ?>" />
				            </div>
			        	</div>
			        	<div class="col-sm-6">
			        		<label for="phoneNo" class="control-label col-sm-4">Phone no.</label>
			        		<div class="col-sm-8">
			        			<input id="phoneNo" name="phoneNo" type="text" class="form-control" placeholder="Phone number" value="<?=$companyInfo[0]["telNo"]; ?>" />
			        		</div>
			        	</div>
			        </div>
			        <div class="form-group form-group-sm">
			        	<div class="col-sm-6">
			            	<label for="tin" class="control-label col-sm-3">TIN</label>
			            	<div class="col-sm-9">
			            		<input id="tin" name="tin" type="text" class="form-control" placeholder="TIN" value="<?=$companyInfo[0]["tin"]; ?>" />
			            	</div>
			            </div>
			        	<div class="col-sm-6">
			        		<label for="companyWebsite" class="control-label col-sm-3">Website </label>
			        		<div class="col-sm-9">
			        			<input id="companyWebsite" name="companyWebsite" class="form-control" type="text" placeholder="Company Website" value="<?=$companyInfo[0]["website"]; ?>" />
			        		</div>
			        	</div>
			        </div>
				</form>
			</div> <!-- end panel-body -->

			<div class="panel-footer">
				<input type="Submit" class="btn btn-primary" />
			    <input type="button" class="btn btn-default" value="Cancel" onclick="window.location.href='<?php echo site_url("admin"); ?>'" />
			</div>
		</div> <!-- end panel -->
	</div> <!-- end of page-wrapper -->

</div><!-- end of #wrapper -->
    
<!-- If no online access, fallback to our hardcoded version of jQuery -->
<script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-1.11.2.min.js"><\/script>')</script>    

<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<script src="http://yourspa.com/includes/js/v2/company.js"></script>

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