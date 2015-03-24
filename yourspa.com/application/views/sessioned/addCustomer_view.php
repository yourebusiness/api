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
        <li><a href="<?php echo base_url("admin"); ?>"><span class="glyphicon glyphicon-home"></span> Home <span class="sr-only">(current)</span></a></li>
        <li class="dropdown active">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-globe"></span> Modules <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#"><span class="glyphicon glyphicon-folder-close"></span> Administrations </a>
                <ul class="dropdown-menu">
                  <li><a tabindex="-1" href="#"></a></li>
                  <li><a href="<?php echo base_url("admin/masseur"); ?>"><span class="glyphicon glyphicon-user"></span> Masseurs </a></li>
                  <li><a href="<?php echo base_url("admin/users"); ?>"><span class="glyphicon glyphicon-star-empty"></span> Users</a></li>
                </ul>
            </li>

            <li class="dropdown-submenu">
                <a tabindex="-1" href="#"><span class="glyphicon glyphicon-wrench"></span> Services </a>
                <ul class="dropdown-menu">
                  <li><a tabindex="-1" href="#"></a></li>
                  <li><a href="<?php echo base_url("admin/services"); ?>"><span class="glyphicon glyphicon-list"></span> List of Services</a></li>
                  <li><a href="<?php echo base_url("admin/servicessadd"); ?>"><span class="glyphicon glyphicon-plus-sign"></span> Add Services</a></li>
                </ul>
            </li>

            <li><a href="#"><span class="glyphicon glyphicon-usd"></span> Transactions</a></li>
            <li class="dropdown-submenu">
              <a tabindex="-1" href="#"><span class="glyphicon glyphicon-file"></span> Reports</a>
              <ul class="dropdown-menu">
                <li><a tabindex="-1" href="#"></a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-user"></span> Per Masseur</a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-usd"></span> Per Services</a></li>
              </ul>
            </li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li class="divider"></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" style="color: blue;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $username; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
            <li><a href="<?php echo base_url("api/logout"); ?>"><span class="glyphicon glyphicon-log-out"></span> Log-out</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="container-fluid">
  <h4>Add Customer</h4> <hr />
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="alert alert-success alert-block fade in" id="successAlert">
        <h4>Success!!!</h4>
        <p>New record has been successfully added.</p>
      </div>

      <div class="alert alert-danger alert-block fade in" id="dangerAlert">
        <h4>Error!!!</h4>
        <p>First and last names are must fields.</p>
      </div>

      <form id="form" class="form-horizontal" role="form" method="get" action="<?php echo base_url("admin/addCustomer"); ?>">
        <div class="form-group">
          <div class="radio">
            <label class="radio-inline">
              <input type="radio" id="CustType" name="custType" value="1" checked />Member
            </label>
            <label class="radio-inline">
              <input type="radio" id="CustType" name="custType" value="0" />Guest
            </label>
          </div>
        </div>

        <div class="form-group">
          <div class="col-md-4">
            <input class="form-control" type="text" id="fName" name="fName" placeholder="First name" maxlength="30" />
          </div>
          <div class="col-md-4">
            <input class="form-control" type="text" id="midName" name="midName" placeholder="Middle name" maxlength="30" />
          </div>
          <div class="col-md-4">
            <input class="form-control" type="text" id="lName" name="lName" placeholder="Last name" maxlength="30" />
          </div>
        </div>

        <hr />

        <div class="form-group">
          <button id="addCustomer" class="btn btn-primary" type="submit">Submit</button>
          <button class="btn btn-default" type="button" onclick="window.location.href='<?php echo base_url("admin/customers"); ?>'">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

    <!-- All Javascript at the bottom of the page for faster page loading -->
    
    <!-- If no online access, fallback to our hardcoded version of jQuery -->
    <script>window.jQuery || document.write('<script src="../../../includes/js/jquery-1.11.2.min.js"><\/script>')</script>    
    <!-- Bootstrap JS -->
    <script src="../../../bootstrap/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="../../../includes/js/customer.js"></script>
    
</body>
</html>