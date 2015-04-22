    <div class="container">

        <div class="container" id="socialMedia">
            <ul class="nav navbar-nav pull-right">
                <li><a href="#"><img src="images/facebook.png"></a></li>
                <li><a href="#"><img src="images/twitter.png"></a></li>
                <li><a href="#"><img src="images/googlePlus.png"></a></li>
                <li><a href="#"><img src="images/linkedin.png"></a></li>
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

                    <a href="http://yourspa.com" class="navbar-brand"><img src="images/spa_logo.png" alt="Go to Home page"></a>
                </div> <!-- end of navbar-header -->

                <div class="navbar-collapse collapse" id="collapsable_menu" role="navigation">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Customers</a></li>
                        <li><a href="<?php echo site_url('aboutus/view'); ?>">About Us</a></li>
                    </ul>

                    <ul class="nav navbar-nav pull-right">
                        <li><a href="#myModal" role="button" data-toggle="modal"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                        <li><a href="<?php echo site_url("registration/view"); ?>"><span class="glyphicon glyphicon-registration-mark"></span> Register</a></li>
                    </ul>
                </div> <!-- end of navbar-collapse collapse -->
            </div> <!-- end container -->
        </nav> <!-- end of navbar navbar-default -->

        <h4><?php echo $message; ?></h4>

    </div> <!-- end of container -->