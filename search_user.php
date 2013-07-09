<?php
/* File: search_user.php
 * Author: Amr Gaber
 * Created: 2013/5/1
 * Description: Handles searching for a user for KC99 database.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION[ 'username' ] ) {
  header( 'Location: login.php' );
  exit;
} ?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KC99 - Database Search for User</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/search_user.css" rel="stylesheet">
      
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>
    <div id="navbar"></div>

    <div class="container-fluid">
      <div class="row-fluid">
        <!-- Body -->
        <div class="span9">
          <legend>Search User</legend>

          <div class="well">
            <div class="row-fluid">
              <form id="search">
                <div class="span3">
                  <input type="text" id="username" name="username" class="span12 search-query mobile-margin" placeholder="Username">
                </div>
                
                <button type="submit" class="btn btn-info span1 mobile-margin" id="search-button"><i class="icon-search"></i></button>
                <button type="button" class="btn span1 mobile-margin" id="clear-button">Clear</button>
                
                <div class="span7">
                  <a href="add_user.php" class="btn btn-primary span3 pull-right mobile-margin">Add User</a>
                </div>
              </form>
            </div>
          </div>
          
          <div id="search-results"></div>
        </div>
        
        <!-- Sidebar -->
        <div class="span3" id="sidebar"></div>
      </div>

      <!-- Footer -->
      <div id="footer"></div>
    </div>
    
    <!-- JavaScript -->
		<script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/search_user.js"></script>
  </body>

</html>
