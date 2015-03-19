    <div class="container">
        <div class="container" id="socialMedia">
                <ul class="nav navbar-nav pull-right">
                    <li><a href="#"><img src="../../../images/facebook.png"></a></li>
                    <li><a href="#"><img src="../../../images/twitter.png"></a></li>
                    <li><a href="#"><img src="../../../images/googlePlus.png"></a></li>
                    <li><a href="#"><img src="../../../images/linkedin.png"></a></li>
                </ul>
        </div> <!-- end of container -->

        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">

                    <button class="navbar-toggle collapsed" type="button" data-target="#collapsable_menu" data-toggle="collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a href="<?php echo base_url("welcome"); ?>" class="navbar-brand"><img src="../../../images/spa_logo.png" alt="Go to Home page"></a>
                </div> <!-- end of navbar-header -->

                <div class="navbar-collapse collapse" id="collapsable_menu" role="navigation">
                    <ul class="nav navbar-nav">
                        <li><a href="http://yourspa.com">Home</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Customers</a></li>
                        <li><a href="<?php echo base_url("aboutus/view"); ?>">About Us</a></li>
                    </ul>

                    <ul class="nav navbar-nav pull-right">
                        <li class="disabled"><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                        <li class="active"><a href="#"><span class="glyphicon glyphicon-registration-mark"></span> Register</a></li>
                    </ul>
                </div> <!-- end of navbar-collapse collapse -->
            </div> <!-- end container -->
        </nav> <!-- end of navbar navbar-default -->

        <div class="row">
            <div class="col-sm-6">
                <h4 class="page_title">Register</h4>
                <hr>
                <h5>Company Details</h5> <hr>
                <p>Fill out the form completely to use the services for free.</p>
                
                <div class="alert alert-danger" role="alert">
                  <p>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    <span id="errorMessage"></span>
                  </p>                  
                </div>

                <?php $this->load->library("form_validation"); ?>

                <form class="form-horizontal" id="form" method="post" action="<?php echo base_url('registration/register'); ?>">
                    <div class="form-group registerSubGroup">
                        <label class="col-sm-3 control-label" for="company">Company name</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" id="company" name="company" placeholder="Company name">
                        </div>
                    </div> <!-- end of form-group -->

                    <div class="form-group registerSubGroup">
                        <label class="col-sm-3 control-label" for="province">Province</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="province" name="province">
                                <option value="0">-- select --</option>
                                <?php foreach($province as $row) { ?>
                                    <option value="<?php echo $row["id"]; ?>"><?php echo $row["provinceName"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div> <!-- end of form-group -->

                    <div class="form-group registerSubGroup">
                        <label class="col-sm-3 control-label" for="city">City</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="city" name="city">
                                <option value="0">-- select --</option>
                            </select>
                        </div>
                    </div> <!-- end of form-group -->

                    <div class="form-group registerSubGroup">
                        <label class="col-sm-3 control-label" for="address">Bldg/St/Dist</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" id="address" name="address" placeholder="Bldg/St/Dist">
                        </div>
                    </div> <!-- end of form-group -->

                    <div class="form-group registerSubGroup">
                        <label class="col-sm-3 control-label" for="phoneNo">Phone number</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" id="phoneNo" name="phoneNo" placeholder="Phone number">
                        </div>
                    </div> <!-- end of form-group -->

                    <div class="form-group registerSubGroup">
                        <label class="col-sm-3 control-label" for="companyWebsite">Company website</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" id="companyEmail" name="companyWebsite" placeholder="Company website">
                        </div>
                    </div> <!-- end of form-group -->

                    <div class="form-group registerSubGroup">
                        <label class="col-sm-3 control-label" for="tin">TIN</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="tin" name="tin" placeholder="Company TIN">
                        </div>
                    </div> <!-- end of form-group -->
                    <hr>
                    <h5>Administrator</h5> <hr>
                    <div class="form-group registerSubGroup">
                        <label class="col-sm-2 control-label" for="fName">First name</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="fName" name="fName" placeholder="First name">
                        </div>
                        <label class="col-sm-2 control-label" for="lName">Last name</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="lName" name="lName" placeholder="Last name">
                        </div>
                    </div>
                    
                    <div class="form-group registerSubGroup">
                        <label class="col-sm-3 control-label" for="userEmail">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="userEmail" name="userEmail" placeholder="User email">
                        </div>
                    </div> <!-- end of form-group -->

                    <div class="form-group registerSubGroup">
                        <label class="col-sm-3 control-label" for="gender">Gender</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="gender" name="gender">
                                <option>-- select --</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group registerSubGroup">
                        <label class="col-sm-3 control-label" for="password">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                    </div> <!-- end of form-group -->
                    <div class="form-group registerSubGroup">
                        <label class="col-sm-3 control-label" for="confirmPassword">Confirm Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
                        </div>
                    </div> <!-- end of form-group -->

                    <div class="form-group">
                        <hr>
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default" onclick="window.location.href='<?php echo base_url(); ?>'">Cancel</button>
                        </div>
                    </div> <!-- end of form-group -->

                </form> <!-- endo of form registration -->

            </div> <!-- first half of col-sm-6 -->

            <div class="col-sm-6">
            </div> <!-- second half of col-sm-6 -->
        </div>
    </div> <!-- end of container -->