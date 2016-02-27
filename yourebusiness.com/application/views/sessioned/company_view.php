<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><img src="../../../images/spa_logo.png"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo site_url("admin"); ?>"><span class="glyphicon glyphicon-home"></span> Home <span class="sr-only">(current)</span></a></li>
        <li class="dropdown active">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-globe"></span> Modules <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#"><span class="glyphicon glyphicon-folder-close"></span> Administrations </a>
                <ul class="dropdown-menu">
                  <li><a tabindex="-1" href="#"></a></li>
                  <li><a href="<?php echo site_url("admin/masseur"); ?>"><span class="glyphicon glyphicon-user"></span> Masseurs </a></li>
                  <li><a href="<?php echo site_url("admin/users"); ?>"><span class="glyphicon glyphicon-star-empty"></span> Users</a></li>
                </ul>
            </li>
            <li class="divider"></li>
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#"><span class="glyphicon glyphicon-wrench"></span> Services </a>
                <ul class="dropdown-menu">
                  <li><a tabindex="-1" href="#"></a></li>
                  <li><a href="<?php echo site_url("admin/services"); ?>"><span class="glyphicon glyphicon-list"></span> List of Services</a></li>
                  <li><a href="<?php echo site_url("admin/addService_view"); ?>"><span class="glyphicon glyphicon-plus-sign"></span> Add Services</a></li>
                </ul>
            </li>

            <li class="dropdown-submenu">
              <a tabindex="-1" href="#"><span class="glyphicon glyphicon-usd"></span> Transactions </a>
                <ul class="dropdown-menu">
                  <li><!-- <a tabindex="-1" href="#"></a> --></li>
                  <li><a href="#"><span class="glyphicon glyphicon-plus-sign"></span> Add new Transaction</a></li>
                  <!-- <li><a href=""><span class="glyphicon glyphicon-usd"></span> --- </a></li> -->
                </ul>
            </li>
            <li class="divider"></li>
            <li class="dropdown-submenu">
              <a tabindex="-1" href="#"><span class="glyphicon glyphicon-file"></span> Reports</a>
              <ul class="dropdown-menu">
                <li><a tabindex="-1" href="#"></a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-user"></span> Per Masseur</a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-usd"></span> Per Services</a></li>
              </ul>
            </li>

          </ul>
        </li>
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" style="color: blue;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> <?php echo $username; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
            <li><a href="<?php echo site_url("api/logout"); ?>"><span class="glyphicon glyphicon-log-out"></span> Log-out</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="container-fluid">
    <h4>Company Profile</h4> <hr />

    <div class="col-md-2">
    </div>
    <div class="col-md-8">
      <div class="alert alert-danger" role="alert">
        <p>
          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
          <span class="sr-only">Error:</span>
          <span id="errorMessage"></span>
        </p>
      </div>

      <form id="form" method="get" action="<?php echo site_url("admin/editCompanyProfile"); ?>" class="form-horizontal" role="form">
        <input type="hidden" name="companyId" value="<?php echo $companyId; ?>" />
          <div class="form-group">
            <div class="col-md-4">
              <input id="company" name="company" type="text" class="form-control" placeholder="Company name" value="<?php echo $companyInfo[0]["companyName"]; ?>" />
            </div>
              <label class="col-md-8"></label>
          </div>

          <div class="form-group">
            <div class="col-md-6">
              <select class="form-control" id="province" name="province">
                <option value="0">-- Province --</option>
                <?php foreach($provinces as $row) { ?>
                    <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $companyInfo[0]['province']) ? "selected" : ""; ?>><?php echo $row["provinceName"]; ?></option>
                <?php } ?>
              </select>
            </div>
              <div class="col-md-6">
                <select class="form-control" id="city" name="city">
                  <option value="0">-- City --</option>
                  <?php foreach($cities as $city) { ?>
                    <option value="<?php echo $city['id']; ?>" <?php echo ($city['id'] == $companyInfo[0]['city']) ? "selected" : ""; ?>><?php echo $city["cityName"]; ?></option>
                <?php } ?>
                </select>
              </div>
          </div>

          <div class="form-group">
            <div class="col-md-4">
              <input id="address" name="address" type="text" class="form-control" placeholder="Address" value="<?php echo $companyInfo[0]["address"]; ?>" />
            </div>
            <div class="col-md-4">
              <input id="phoneNo" name="phoneNo" type="text" class="form-control" placeholder="Phone number" value="<?php echo $companyInfo[0]["telNo"]; ?>" />
            </div>
            <div class="col-md-4">
              <input id="tin" name="tin" type="text" class="form-control" placeholder="TIN" value="<?php echo $companyInfo[0]["tin"]; ?>" />
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-4">
              <input id="companyWebsite" name="companyWebsite" type="text" class="form-control" placeholder="Company Website" value="<?php echo $companyInfo[0]["website"]; ?>" />
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
            </div>
          </div>

          <hr />

          <div class="form-group">
            <div class="col-md-12">
              <input type="Submit" class="btn btn-primary" />
            <input type="button" class="btn btn-default" value="Cancel" onclick="window.location.href='<?php echo site_url("admin"); ?>'" />
            </div>
          </div>

      </form>

    </div>
    <div class="col-md-2">
    </div>
</div>

<!-- All Javascript at the bottom of the page for faster page loading -->
    
    <!-- If no online access, fallback to our hardcoded version of jQuery -->
    <script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-1.11.2.min.js"><\/script>')</script> 
    
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    
    <script src="http://yourspa.com/includes/js/company.js"></script>
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/0.8.1/mustache.js"></script>
    
</body>
</html>