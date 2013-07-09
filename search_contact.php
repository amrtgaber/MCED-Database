<?php
/* File: search_contact.php
 * Author: Amr Gaber
 * Created: 18/11/2012
 * Modified: 02/17/2013
 * Modified By: Bryan Dorsey
 * Description: Handles searching for a contact for KC99 database.
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
    <title>KC99 - Database Search for Contact</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/search_contact.css" rel="stylesheet">
      
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
          <legend>Search Contact</legend>

          <div class="well">
            <div class="row-fluid">
              <form id="search">
                <div class="span3">
                  <input type="text" id="firstName" name="firstName" class="span12 search-query mobile-margin" placeholder="First Name">
                </div>
                
                <div class="span3">
                  <input type="text" id="lastName" name="lastName" class="span12 search-query mobile-margin" placeholder="Last Name">
                </div>
                
                <button type="submit" class="btn btn-info span1 mobile-margin" id="search-button"><i class="icon-search"></i></button>
                <button type="button" class="btn span1 mobile-margin" id="clear-button">Clear</button>
                
                <div class="span4">
                  <a href="add_contact.php" class="btn btn-primary span5 pull-right mobile-margin">Add Contact</a>
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
    <script src="js/search_contact.js"></script>
  </body>

</html>

