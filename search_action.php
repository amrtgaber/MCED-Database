<?php
/* File: search_action.php
 * Author: Bryan Dorsey
 * Created: 3/3/2013
 * Description: Handles searching for actions and their corresponding contacts in the KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

if( !$_SESSION[ 'username' ] ) {
  header( 'Location: login.php' );
  exit;
}

/* Connect to database */
$mc = connect_to_database(); ?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KC99 - Database Search for Action</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/jquery.dataTables.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/search_action.css" rel="stylesheet">
      
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
            Search Action
            <a href="add_action.php" class="btn btn-primary span3 pull-right mobile-margin">Add Action</a>
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
    <script src="js/search_action.js"></script>
  </body>

</html>
