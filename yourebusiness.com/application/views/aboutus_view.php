
    <div class="container">

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
                        <form method="post" action="<?php echo site_url("api/signIn") ?>" class="form-horizontal">
                            <div class="form-group">
                                 <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                                 <div class="col-sm-8">
                                    <input type="email" class="form-control" id="email" name="username" placeholder="You email here">
                                 </div>
                                 <div class="col-sm-2"></div>
                            </div> <!-- end of form-group -->
                            <div class="form-group">
                                <label for="inputPassword" class="col-sm-2 control-label">Password</label>
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

                    <a href="<?php echo base_url(); ?>" class="navbar-brand"><img src="<?php echo base_url(); ?>images/spa_logo.png" alt="Go to Home page"></a>
                </div> <!-- end of navbar-header -->

                <div class="navbar-collapse collapse" id="collapsable_menu" role="navigation">
                    <ul class="nav navbar-nav">
                        <li><a href="http://yourspa.com">Home</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Customers</a></li>
                        <li class="active"><a href="#">About Us</a></li>
                    </ul>

                    <ul class="nav navbar-nav pull-right">
                        <li><a href="#myModal" role="button" data-toggle="modal"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                        <li><a href="<?php echo site_url("registration/view"); ?>"><span class="glyphicon glyphicon-registration-mark"></span> Register</a></li>
                    </ul>
                </div> <!-- end of navbar-collapse collapse -->
            </div> <!-- end container -->
        </nav> <!-- end of navbar navbar-default -->
        <br>
        <h4 class="page_title">About Us</h4>
        <hr />
        <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
        	The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here,
        	content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as
        	their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions
        	have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
        </p>

        <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC,
        	making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the
        	more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature,
        	discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum"
        	(The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during
        	the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
        </p>
        <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.
        </p>

        <br>
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