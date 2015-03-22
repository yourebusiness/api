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
                  <li><a href="#"><span class="glyphicon glyphicon-star-empty"></span> Users</a></li>
                </ul>
            </li>
            <li class="divider"></li>
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#"><span class="glyphicon glyphicon-wrench"></span> Services </a>
                <ul class="dropdown-menu">
                  <li><a tabindex="-1" href="#"></a></li>
                  <li><a href="<?php echo base_url("admin/services"); ?>"><span class="glyphicon glyphicon-list"></span> List of Services</a></li>
                  <?php if ($userRights != 0)
                  ?><li><a href="<?php echo base_url("admin/addService_view"); ?>"><span class="glyphicon glyphicon-plus-sign"></span> Add Services</a></li>
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

<div class="container">
  <div class="row">
    <div class="col-sm-4">
    </div>

    <div class="col-sm-4">

      <div class="alert alert-success alert-block fade in" id="successAlert">
        <h4>Success!!!</h4>
        <p>New record has been successfully added.</p>
      </div>

      <div class="alert alert-danger alert-block fade in" id="dangerAlert">
        <h4>Error!!!</h4>
        <p>All fields must have values.</p>
      </div>

      <p>Fill-out the form below to add new service.</p>

      <form>
        <div class="form-group">
          <input type="text" class="form-control" id="serviceName" name="serviceName" placeholder="Service name" maxlength="120">
        </div>
        <div class="form-group">
          <textarea class="form-control" id="description" name="description" placeholder="Service Description" rows="4" maxlength="500"></textarea>
        </div>
        <div class="form-group">
          <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
          <div class="input-group">
            <div class="input-group-addon">Php</div>
            <input type="text" class="form-control" id="regPrice" name="regPrice" placeholder="Reg. Price" maxlength="10">
          </div>
        </div>
        <div class="form-group">
          <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
          <div class="input-group">
            <div class="input-group-addon">Php</div>
            <input type="text" class="form-control" id="memberPrice" name="memberPrice" placeholder="Member Price" maxlength="10">
          </div>
        </div>

        <button id="add-service" type="button" class="btn btn-primary">Submit</button>
        <button id="cancel" type="button" class="btn btn-default" onclick="window.location.href='<?php echo base_url("admin/services"); ?>'">Cancel</button>
      </form>
    </div>

    <div class="col-sm-4">
    </div>
  </div>
</div>

    <!-- All Javascript at the bottom of the page for faster page loading -->
    
    <!-- If no online access, fallback to our hardcoded version of jQuery -->
    <script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-1.11.2.min.js"><\/script>')</script>    
    <!-- Bootstrap JS -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="http://yourspa.com/includes/js/services.js"></script>
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/0.8.1/mustache.js"></script>
</body>
</html>