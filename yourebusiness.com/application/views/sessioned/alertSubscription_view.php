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
        <li class="active"><a href="#"><span class="glyphicon glyphicon-home"></span> Home <span class="sr-only">(current)</span></a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-globe"></span> Modules <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php
              if ($userRights == 0) { ?> 
              <li class="dropdown-submenu">
                  <a tabindex="-1" href="#"><span class="glyphicon glyphicon-folder-close"></span> Administrations </a>
                  <ul class="dropdown-menu">
                    <li><a tabindex="-1" href="#"></a></li>
                    <li><a href="<?php echo site_url("admin/login") . "?v=companyProfile"; ?>"> Company Profile </a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url("admin/masseur"); ?>"><span class="glyphicon glyphicon-user"></span> Masseurs </a></li>
                    <li><a href="<?php echo site_url("admin/users"); ?>"><span class="glyphicon glyphicon-star-empty"></span> Users</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo site_url("admin/customers"); ?>"> Customers</a></li>
                  </ul>
              </li> <?php } ?>
            <li class="divider"></li>
            <li class="dropdown-submenu">
                <a tabindex="-1" href="#"><span class="glyphicon glyphicon-wrench"></span> Services </a>
                <ul class="dropdown-menu">
                  <li><a tabindex="-1" href="#"></a></li>
                  <li><a href="<?php echo site_url("admin/services"); ?>"><span class="glyphicon glyphicon-list"></span> List of Services</a></li>
                  <?php
                    if ($userRights == 0) {
                    ?> <li><a href="<?php echo site_url("admin/addService_view"); ?>"><span class="glyphicon glyphicon-plus-sign"></span> Add Services </a></li>
                    <?php } ?>
                </ul>
            </li>

            <li class="dropdown-submenu">
              <a tabindex="-1" href="#"><span class="glyphicon glyphicon-usd"></span> Transactions </a>
                <ul class="dropdown-menu">
                  <li><!-- <a tabindex="-1" href="#"></a> --></li>
                  <li><a href="<?php echo site_url("admin/transactions"); ?>"><span class="glyphicon glyphicon-plus-sign"></span> Add new Transaction</a></li>
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
            <li><a href="<?php echo site_url('admin/profile') ?>"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
            <li><a href="<?php echo site_url("api/logout"); ?>"><span class="glyphicon glyphicon-log-out"></span> Log-out</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="container">
  <h3>Please subscribe.</h3>

  <table class="table table-hover table-bordered">
    <thead>
      <tr>
        <th>Subscription name</th>
        <th>Price (Php)</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>2 Months</td>
        <td>1,000.00</td>
        <td>
          <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">

            <!-- Identify your business so that you can collect the payments. -->
            <input type="hidden" name="business" value="businesstest@yourspa.com">

            <!-- Specify a Subscribe button. -->
            <input type="hidden" name="cmd" value="_xclick-subscriptions">

            <!-- Identify the subscription. -->
            <input type="hidden" name="item_name" value="yourspa.com 1 months subscription">
            <input type="hidden" name="item_number" value="1 Months Subscription">

            <!-- Set the terms of the recurring payments. -->
            <input type="hidden" name="a3" value="500">
            <input type="hidden" name="p3" value="1">
            <input type="hidden" name="t3" value="M">
            <INPUT TYPE="hidden" NAME="currency_code" value="PHP">

            <!-- Set recurring payments to stop after 6 billing cycles. -->
            <input type="hidden" name="src" value="0">
            <!-- <input type="hidden" name="srt" value="1"> -->

            <!-- Display the payment button. -->
            <input type="image" name="submit" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribe_LG.gif" alt="PayPal - The safer, easier way to pay online" />
            <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" />
        </form>
        </td>
      </tr>
      <tr>
        <td>4 Months</td>
        <td>2,000.00</td>
        <td></td>
      </tr>
      <tr>
        <td>8 Months</td>
        <td>4,000.00</td>
        <td></td>
      </tr>
      <tr>
        <td>1 year</td>
        <td>6,000.00</td>
        <td></td>
      </tr>
    </tbody>
  </table>
</div>

<!-- All Javascript at the bottom of the page for faster page loading -->
    
    <!-- If no online access, fallback to our hardcoded version of jQuery -->
    <script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-1.11.2.min.js"><\/script>')</script>    
    <!-- Bootstrap JS -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/0.8.1/mustache.js"></script>
    
</body>
</html>