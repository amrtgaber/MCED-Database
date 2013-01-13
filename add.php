<?php
/* File: add.php
 * Author: Amr Gaber
 * Created: 9/27/2012
 * Description: Handles add page for KC99 database.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION[ 'username' ] ) {
  header( 'Location: login.php' );
  exit;
}

/* Must have privilege level of 1 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 1 ) {
  header( 'Location: home.php' );
  exit;
}
?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>KC99 - Database Add</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/add.css" rel="stylesheet">
      
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>
    <div id="navbar">
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <!-- Body -->
        <div class="span9">
          <div class="row-fluid">
            <div class="span6">
              <h2>Add Contact</h2>
              <p>Select this option if you'd like to add a contact (from a petition for example).</p>
            </div>
            <div class="span6">
              <h2>Add Shop Profile</h2>
              <p>Select this option if you'd like to add a shop profile.</p>
            </div>
          </div><!--/.row-fluid-->

          <div class="row-fluid">
            <div class="span6">
              <button class="btn btn-large btn-primary" type="button" id="add-contact">Add Contact &raquo;</button>
            </div>
            <div class="span6">
              <button class="btn btn-large btn-primary" type="button" id="add-shop-profile">Add Shop Profile &raquo;</button>
            </div>
          </div><!--./row-fluid-->
          
          <div class="row-fluid">
            <div class="span6">
              <h2>Add Contact Sheet</h2>
              <p>Select this option if you'd like to add a contact sheet.</p>
            </div>
          </div><!--/.row-fluid-->

          <div class="row-fluid">
            <div class="span6">
              <button class="btn btn-large btn-primary" type="button" id="add-contact-sheet">Add Contact Sheet &raquo;</button>
            </div>
          </div><!--./row-fluid-->
        </div><!--/.span9-->

        <!-- Sidebar -->
        <div class="span3">
          <div class="well sidebar-nav">
            <div id="sidebar">
            </div>
          </div>
        </div>
      </div><!--/.row-fluid-->

      <!-- Footer -->
      <div id="footer">
      </div>
    </div><!--/.fluid-container-->
    
    <!-- JavaScript -->
		<script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/add.js"></script>
  </body>

</html>

