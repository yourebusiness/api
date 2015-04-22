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
          <a href="#" style="color: blue;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $username; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
            <li><a href="<?php echo site_url("api/logout"); ?>"><span class="glyphicon glyphicon-log-out"></span> Log-out</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="container">
  <button type="button" onclick="window.location.href='<?php echo site_url("admin/usersadd_view"); ?>'" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Add</button>
  <br><br>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>First Name</th>
          <th>Mid Name</th>
          <th>Last Name</th>
          <th>Address</th>
          <th>Gender</th>
          <th>Active</th>
          <th>Access Rights</th>
          <th></th> <!-- for the trans -->
        </tr>
      </thead>
      <tbody>
        <?php foreach($users as $row) { ?>
        <tr>
          <td><?php echo $row["userId"]; ?></td>
          <td><div data-col="username"><?php echo $row["username"]; ?></div></td>
          <td><div data-col="fName"><?php echo $row["fName"]; ?></div></td>
          <td><div data-col="midName"><?php echo $row["midName"]; ?></div></td>
          <td><div data-col="lName"><?php echo $row["lName"]; ?></div></td>
          <td><div data-col="address"><?php echo $row["address"]; ?></div></td>
          <td><div data-col="gender"><?php echo $row["gender"]; ?></div></td>
          <td><a class="changeStatus" href="<?php echo site_url("admin/usersChangeStatus") . "/" . $row["userId"] . "/" . $row["active"] ; ?>"><?php echo $row["active"]; ?></a></td>
          <td>
              <?php
                if ($userRights == 0) {
                  ?> <a class="changeUserRights" href="<?php echo site_url('admin/changeUserRights') . '/' . $row['userId'] . '/', (($row['role'] == 0) ? 'administrator' : 'user'); ?>">
                  <?php echo ($row["role"] == 0) ? "Administrator" : "User";
                  ?> </a>
                <?php } else {
                  echo ($row["role"] == 0) ? "Administrator" : "User";
                }
              ?>
          </td>
          <td>
            <?php
              if ($userRights == 0)
            ?>  <a href="<?php echo site_url("admin/usersEdit") . "/" . $row["userId"]; ?>" class="editRecord"><span class="glyphicon glyphicon-edit"></span> Edit </a>
          &nbsp;
            <?php
              if ($row["trans"] == 'N')
            ?>  <a href="<?php echo site_url("admin/usersDelete") . "/" . $row["userId"]; ?>" class="deleteRecord"><span class="glyphicon glyphicon-trash"></span> Delete </a>
          </td>
        </tr>
        <?php }  /* for the foreach() */ ?>
      </tbody>
    </table>
  </div>

  <nav>
    <ul class="pagination">
      <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
      <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
      <li><a href="#">2</a></li>
      <li><a href="#">3</a></li>
      <li><a href="#">4</a></li>
      <li><a href="#" aria-label="Next">&raquo;</a></li>
    </ul>
  </nav>

</div>
    <!-- All Javascript at the bottom of the page for faster page loading -->
    
    <!-- If no online access, fallback to our hardcoded version of jQuery -->
    <script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-1.11.2.min.js"><\/script>')</script>
    <!-- Bootstrap JS -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="http://yourspa.com/includes/js/users.js"></script>
    
</body>
</html>