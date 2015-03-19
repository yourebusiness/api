    <div class="container" id="main">

        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                        <div class="modal-title">
                            <h4>Log-in</h4>
                        </div> <!-- end of modal-title -->
                        <div class="modal-subtitle"><small>Enter your username and password</small></div>
                    </div> <!-- end of modal-header -->
                    <div class="modal-body">
                        <form action="login.html" class="form-horizontal">
                            <div class="form-group">
                                 <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                                 <div class="col-sm-8">
                                    <input type="email" class="form-control" id="inputEmail" placeholder="You email here">
                                 </div>
                                 <div class="col-sm-2"></div>
                            </div> <!-- end of form-group -->
                            <div class="form-group">
                                <label for="inputPassword" class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-8">
                                    <input type="password" id="inputPassword" class="form-control" placeholder="Input password here">
                                </div> <!-- end of col-sm-8 -->
                            </div> <!-- end of form-group -->
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <div class="checkbox">
                                        <label><input type="checkbox"> Remember me</label>
                                    </div>
                                </div>
                            </div> <!-- end of form-group -->
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Log-in</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div> <!-- end of col-sm-offset col-sm-10 -->
                            </div> <!-- end of form-group -->
                        </form> <!-- end of form -->
                    </div> <!-- end of modal-body -->
                </div> <!-- end of modal-content -->
            </div> <!-- end of modal-dialog -->
        </div> <!-- end of modal -->

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

                    <a href="http://yourspa.com" class="navbar-brand"><img src="../../../images/spa_logo.png" alt="Go to Home page"></a>
                </div> <!-- end of navbar-header -->

                <div class="navbar-collapse collapse" id="collapsable_menu" role="navigation">
                    <ul class="nav navbar-nav">
                        <li><a href="http://yourspa.com">Home</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Customers</a></li>
                        <li><a href="<?php echo base_url('aboutus/view'); ?>">About Us</a></li>
                    </ul>

                    <ul class="nav navbar-nav pull-right">
                        <li><a href="#myModal" role="button" data-toggle="modal"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                        <li class="active"><a href="<?php echo base_url("registration/view"); ?>"><span class="glyphicon glyphicon-registration-mark"></span> Register</a></li>
                    </ul>
                </div> <!-- end of navbar-collapse collapse -->
            </div> <!-- end container -->
        </nav> <!-- end of navbar navbar-default -->

        <div class="container">
            <h4 class="page_title">Successful</h4>
            <p>Congratualtions!!! You have successfull registered.</p>
            <p>Please check your email for the registration code that you have to enter on your first login.</p>
        </div> <!-- end of container -->

    </div> <!-- end of container main -->
    

<!-- All Javascript at the bottom of the page for faster page loading -->
        
<!-- First try for the online version of jQuery-->
<script src="http://code.jquery.com/jquery.js"></script>

<!-- If no online access, fallback to our hardcoded version of jQuery -->
<script>window.jQuery || document.write('<script src="../../../includes/js/jquery-1.11.2.min.js"><\/script>')</script>

<!-- Bootstrap JS -->
<script src="../../../bootstrap/js/bootstrap.min.js"></script>

<!-- Custom JS -->
<script src="../../../includes/js/register.js"></script>

</body>
</html>