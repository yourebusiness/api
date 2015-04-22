
	<div class="container">
		<div class="container" id="socialMedia">
                <ul class="nav navbar-nav pull-right">
                    <li><a href="#"><img src="<?php echo base_url(); ?>images/facebook.png"></a></li>
                    <li><a href="#"><img src="<?php echo base_url(); ?>images/twitter.png"></a></li>
                    <li><a href="#"><img src="<?php echo base_url(); ?>images/googlePlus.png"></a></li>
                    <li><a href="#"><img src="<?php echo base_url(); ?>images/linkedin.png"></a></li>
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

                    <a href="<?php echo site_url(); ?>" class="navbar-brand"><img src="<?php echo base_url(); ?>images/spa_logo.png" alt="Go to Home page"></a>
                </div> <!-- end of navbar-header -->

                <div class="navbar-collapse collapse" id="collapsable_menu" role="navigation">
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo site_url(); ?>">Home</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Customers</a></li>
                        <li><a href="<?php echo site_url('aboutus/view'); ?>">About Us</a></li>
                    </ul>

                    <ul class="nav navbar-nav pull-right">
                        <li class="disabled"><a href="#" role="button"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                        <li class="active"><a href="<?php echo site_url("registration/view"); ?>"><span class="glyphicon glyphicon-registration-mark"></span> Register</a></li>
                    </ul>
                </div> <!-- end of navbar-collapse collapse -->
            </div> <!-- end container -->
        </nav> <!-- end of navbar navbar-default -->
	</div> <!-- end of container -->

    <div class="container">
        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                <div class="panel panel-default login">
                    <div class="panel-heading">
                        <h3 class="panel-title">Login</h3>
                    </div>
                    <div class="panel-body">
                        <form method="post" action="<?php echo site_url("api/signin"); ?>">
                            <div class="form-group">
                                <label for="username">Email</label>
                                <input type="email" class="form-control" id="username" name="username" placeholder="username">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                            <a href="#" class="pull-right">Forgot password</a>
                        </form>
                    </div> <!-- end of panel-body -->
                </div> <!-- end of .panel .panel-primary .login -->
            </div>
            <div class="col-md-4">
            </div>
        </div>
    </div>

<footer class="footer footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-sm-2">
                    <h6>Copyright &copy; 2015</h6>
                </div>
                <div class="col-sm-2">
                    <h6>Navigation</h6>
                    <ul class="list-unstyled">
                        <li><a href="http://yourspa.com"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-wrench"></span> Services</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-grain"></span> About Us</a></li>
                    </ul>
                </div> <!-- end of col-sm-2 -->
                <div class="col-sm-8">
                    <!-- some texts here -->
                </div> <!-- end of col-sm-8 -->
            </div> <!-- end of footer row -->
        </div> <!-- end of footer container -->
    </footer> <!-- end of footer -->

    <!-- All Javascript at the bottom of the page for faster page loading -->
    
    <!-- If no online access, fallback to our hardcoded version of jQuery -->
    <script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>includes/js/jquery-1.11.2.min.js"><\/script>')</script>
    
    <!-- Bootstrap JS -->
    <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
    
</body>
</html>