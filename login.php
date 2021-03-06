<?php
/* File: login.php
 * Author: Amr Gaber
 * Created: 9/27/2012
 * Description: Handles login page for KC99 database.
 */

/* If user is already logged in redirect to home page */
session_start();

if( $_SESSION[ 'username' ] ) {
  header( "Location: index.php" );
  exit;
}
?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KC99 - Database Login</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
      
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="brand">Database</li>
            </ul>

            <ul class="nav pull-right">
              <li class="navbar-text">Not Logged In</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <!-- Body -->
        <div class="span3">
          <div class="well">
            <form>
              <div class="row-fluid">
                <h2>Login</h2>
                
                <label>Username</label>
                <input type="text" name="username" placeholder="Type username here" required>
                
                <label>Password</label>
                <input type="password" name="password" placeholder="Type password here" required>
              </div>

              <div class="row-fluid">
                <button type="submit" class="btn btn-primary">Login</button> 
              </div>
            </form>
            
            <div id="error" class="alert alert-error hide">
            </div>
          </div>
        </div>

        <div class="span9">
          <img src="img/kc99-banner-7-13.png">
        </div>
      </div><!--/.row-fluid-->

      <!-- Footer -->
      <hr>
      <footer class="footer">
        <p>Copyright &copy;&nbsp;2012 <a href="http://kc99.org/">KC99</a></p>
      </footer>
    </div><!--/.fluid-container-->

    <!-- JavaScript -->
		<script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/login.js"></script>
  </body>

</html>

