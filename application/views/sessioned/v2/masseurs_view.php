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
                    <li class="active">
                        <a href="#"><i class="fa fa-dashboard fa-fw"></i> Administration <i class="fa fa-minus"></i></a>
                        <ul class="nav nav-second-level collapse in" aria-expanded="false">
                            <li><a href="<?=site_url("admin/adminLogin") . "?v=companyProfile"; ?>"> Company Profile</a></li>
                            <li class="active"><a href="#"> Massuers</a></li>
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

        <div id="main-content">
            <div class="row">
                <div class="panel panel-success">
                    <div class="panel-heading">List of Masseurs Records</div>
                    <div class="panel-body">                        
                        <table id="masseurTable" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="checkAll" id="checkAll" /> </th>
                                    <th>Masseur Id</th>
                                    <th>First</th>
                                    <th>Middle</th>
                                    <th>Last</th>
                                    <th>Gender</th>
                                    <th>Nickname</th>
                                    <th>Active</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="panel-footer">
                        <button id="addRow" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#masseur_modal">Add</button>
                        <button id="btn-delete" class="btn btn-danger btn-sm confirm" type="button" disabled>Delete</button>
                        <a href="http://yourspa.com/index.php/admin/masseurslist_download" class="btn btn-warning btn-sm">Download</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- add modal -->
        <div class="modal fade" id="masseur_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add new masseur record</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form id="form_masseur" action="masseurs" method="post" class="form-horizontal" role="form">
                                <input id="masseurId" name="masseurId" type="hidden" value="0" />
                                <div class="form-group form-group-sm">
                                    <label for="fName" class="control-label col-sm-2">First</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="fName" name="fName" class="form-control" placeholder="First" />
                                    </div>
                                    <div class="col-sm-3">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="midName" class="control-label col-sm-2">Middle</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="midName" name="midName" class="form-control" placeholder="Middle name" />
                                    </div>
                                    <div class="col-sm-3"></div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="lName" class="control-label col-sm-2">Last</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="lName" name="lName" class="form-control" placeholder="Last name" />
                                    </div>
                                    <div class="col-sm-3"></div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="gender" class="control-label col-sm-2">Gender</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="gender" name="gender">
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3"></div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="nickname" class="control-label col-sm-2">Nickname</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="nickname" name="nickname" class="form-control" placeholder="Nickname" />
                                    </div>
                                    <div class="col-sm-3"></div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="active" class="control-label col-sm-2">Active</label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="active" name="active">
                                            <option value="Y">Yes</option>
                                            <option value="N">No</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-4">
                            <span>
                                <button id="save" type="button" class="btn btn-primary btn-sm" data-toggle="popover" data-trigger="focus" title="Error" data-content="Fill-out the form completely." data-placement="top">Save</button>
                            </span>
                            <button id="cancel" type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
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

    <script type="text/javascript" src="<?php echo base_url(); ?>includes/js/v2/masseurs.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>includes/js/jquery.confirm.min.js"></script>

    </body>
</html>