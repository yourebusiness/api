<!DOCTYPE html>
<html>
    <head>
        
        <!-- Website Title & Description for Search Engine purposes -->
        <title>Your Spa</title>
        <meta name="description" content="">
        
        <!-- Mobile viewport optimized -->  
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        
        <!-- Bootstrap CSS -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="includes/css/bootstrap-glyphicons.css" rel="stylesheet">

        <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
        
        <!-- Custom CSS -->
        <link href="includes/css/styles.css" rel="stylesheet">
        
        <!-- Include Modernizr in the head, before any other Javascript -->
        <script src="includes/js/modernizr-2.6.2.min.js"></script>
        
    </head>
    <body>

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
                        <form action="<?php echo site_url("api/signIn") ?>" method="post" class="form-horizontal">
                            <div class="form-group">
                                 <label for="username" class="col-sm-2 control-label">Email</label>
                                 <div class="col-sm-8">
                                    <input type="email" class="form-control" id="username" name="username" placeholder="You email here">
                                 </div>
                                 <div class="col-sm-2"></div>
                            </div> <!-- end of form-group -->
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-8">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Input password here">
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

                    <a href="<?php site_url("welcome"); ?>" class="navbar-brand"><img src="images/spa_logo.png" alt="Go to Home page"></a>
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

        <div class="carousel slide" id="myCarousel">
            <ol class="carousel-indicators">
                <li class="active" data-slide-to="0" data-target="#myCarousel"></li>
                <li data-slide-to="1" data-target="#myCarousel"></li>
                <li data-slide-to="2" data-target="#myCarousel"></li>
            </ol>

            <div class="carousel-inner">
                <div class="item active" id="slide1"></div>
                <div class="item" id="slide2"></div>
                <div class="item" id="slide3"></div>
            </div> <!-- end of carousel-inner -->

            <!-- carousel-control -->
            <a href="#myCarousel" class="left carousel-control" data-slide="prev"><span class="icon-prev"></span></a>
            <a href="#myCarousel" class="right carousel-control" data-slide="next"><span class="icon-next"></span></a>
        </div> <!-- end of carousel slide -->

        <div class="well">
            <div class="page-header">
                <h4>Designed for small Spa Business</h4>
            </div> <!-- end of page-header -->
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
        </div> <!-- end of well -->

    </div> <!-- end of container -->

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-2">
                    <h6>Copyright &copy; 2015</h6>
                </div>
                <div class="col-sm-2">
                    <h6>Navigation</h6>
                    <ul class="list-unstyled">
                        <li><a href="#"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-wrench"></span> Services</a></li>
                        <li><a href="<?php echo site_url('aboutus/view'); ?>"><span class="glyphicon glyphicon-grain"></span> About Us</a></li>
                    </ul>
                </div> <!-- end of col-sm-2 -->
                <div class="col-sm-8">
                    <!-- some texts here -->
                </div> <!-- end of col-sm-8 -->
            </div> <!-- end of footer row -->
        </div> <!-- end of footer container -->
    </footer>
    

    <!-- All Javascript at the bottom of the page for faster page loading -->
        
    <!-- First try for the online version of jQuery-->
    <script src="http://code.jquery.com/jquery.js"></script>
    
    <!-- If no online access, fallback to our hardcoded version of jQuery -->
    <script>window.jQuery || document.write('<script src="includes/js/jquery-1.11.2.min.js"><\/script>')</script>
    
    <!-- Bootstrap JS -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    
    <!-- Custom JS -->
    <script src="includes/js/script.js"></script>
    
    </body>
</html>