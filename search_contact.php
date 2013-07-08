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
    <link href="css/jquery.dataTables.css" rel="stylesheet">
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
          <legend>
            Search Contact
            <a href="add_contact.php" class="btn btn-primary pull-right mobile-margin">Add Contact</a>
          </legend>
          
          <div id="records"></div>
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
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/search_contact.js"></script>
  </body>

</html>

