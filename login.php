<?php
/* File: login.php
 * Author: Amr Gaber
 * Created: 9/27/2012
 * Description: Handles login page for KC99 database.
 */
?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>KC99 - Database Login</title>
    <meta name="description" content="KC99 Organizing Database">
    <meta name="author" content="Amr Gaber">

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
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
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Database</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              Not logged in
            </p>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <!-- Body -->
        <div class="span3">
          <div class="well">
            <form>
              <h2>Login</h2>
              <label>Username</label>
              <input type="text" name="username" placeholder="Type username here" required>
              <label>Password</label>
              <input type="password" name="password" placeholder="Type password here" required>
              <br />
              <button type="submit" class="btn btn-primary">Login</button> 
              <span id="error"></span>
            </form>
          </div>
        </div><!--/.span3-->

        <div class="span9">
          <img src="img/kc99-banner-7-13.png">
        </div><!--/.span9-->
      </div><!--/.row-fluid-->

      <!-- Footer -->
      <hr>
      <footer class="footer">
        <p>Copyright &copy;&nbsp;2012 <a href="http://www.kansascity99.org/">KC99</a></p>
      </footer>
    </div><!--/.fluid-container-->

    <!-- JavaScript -->
    <script src="js/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/login.js"></script>
  </body>

</html>

