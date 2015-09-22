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
                    <a href="<?=site_url('admin');?>" class="navbar-brand"><img src="../../../images/spa_logo.png"></a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="menu-nav menubar-right">
                        <li><a id="myUsername" href="#"><i class="fa fa-user"></i> <?=$username;?> <span class="down-arrow">&#9660;</span></a>
                            <ul class="sub-menu">
                                <li><a href="#"><i class="fa fa-user-md"></i> Profile</a></li>
                                <li><a href="<?=site_url("api/logout");?>"><i class="fa fa-sign-out"></i> Log-out</a></li>
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
                    <li>
                        <a href="#"><i class="fa fa-dashboard fa-fw"></i> Administration <i class="fa fa-plus"></i></a>
                        <ul class="nav nav-second-level collapse" aria-expanded="false">
                            <li><a href="<?=site_url("admin/adminLogin") . "?v=companyProfile"; ?>"> Company Profile</a></li>
                            <li><a href="<?=site_url('admin/users'); ?>"> Users</a></li>
                            <li><a href="<?=site_url('admin/masseurs'); ?>"> Massuers</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-suitcase"></i> Business Partner<i class="fa fa-plus"></i></a>
                        <ul class="nav nav-second-level collapse" aria-expanded="false">
                            <li><a href="<?=site_url('admin/customers'); ?>"> Customers</a></li>
                        </ul>
                    </li>
                    <li class="active">
                        <a href="#"><i class="fa fa-car"></i> Services <i class="fa fa-minus"></i></a>
                        <ul class="nav nav-second-level collapse in" aria-expanded="true">
                            <li class="active"><a href="#"> List of Services</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-money"></i> Transactions <i class="fa fa-plus"></i></a>
                        <ul class="nav nav-second-level collapse" aria-expanded="false">
                            <li><a href="<?=site_url('admin/transactions'); ?>"> Add New Transaction</a></li>
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

        <div id="main-content">
            <div class="row">
                <div class="panel panel-success">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-1">Masseur</div>
                            <div class="col-sm-3">
                                <select id="masseur" name="masseur" class="form-control">
                                  <option value="0">-- select --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1">Services</div>
                            <div class="col-sm-4">
                                <select id="service" name="service" class="form-control">
                                    <option value="0">-- select --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1">Customers</div>
                            <div class="col-sm-4">
                                <select id="customer" name="customer" class="form-control">
                                  <option value="0">-- select --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1">Price</div>
                            <div class="col-sm-4">
                                <input class="form-control" type="text" id="price" value="0.00" readonly>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-sm-1">Amount Paid</div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="amountPaid" name="amountPaid" value="0.00" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1">Change</div>
                            <div class="col-sm-4">
                                <input class="form-control" type="text" id="change" value="0.00" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button id="add" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#transact_modal">Add</button>
                        <button id="btn-clear" class="btn btn-default btn-sm" type="button">Clear</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- confirmation modal -->
        <div class="modal fade" id="transact_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h5 class="modal-title" id="myModalLabel">Question</h5>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <h4>Are you sure you want to add this transaction?</h4>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-4">
                            <span>
                                <button id="yesButton" type="button" class="btn btn-primary btn-sm">Yes</button>
                            </span>
                            <button id="cancelButton" type="button" class="btn btn-default btn-sm" data-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end for add modal -->
        
    </div> <!-- end for wrapper -->

    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js"></script>

    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.0.2/metisMenu.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>includes/js/v2/metisMenuSettings.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>includes/js/v2/transaction.js"></script>

    </body>
</html>