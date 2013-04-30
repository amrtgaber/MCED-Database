<?php
/* File: index.php
 * Author: Amr Gaber
 * Created: 2013/4/29
 * Description: Handles home page for KC99 database.
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
    <title>KC99 - Database Home</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
      
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>
    <div id="navbar"></div>

    <div class="container-fluid">
      <div class="row-fluid" style="text-align: center;">
        <!-- Main Menu -->
        <div class="span9">
          <div class="row-fluid">
            <div class="span3">
              <!-- Contacts -->
              <h2>Contacts</h2>
              <p>Search, add, edit, or delete contacts.</p>
              <p><a href="search_contact.php" class="btn btn-large btn-primary">Contacts &raquo;</a></p>
            </div>

            <div class="span3">
              <!-- Contact Sheets -->
              <h2>Contact Sheets</h2>
              <p>Search, add, edit, or delete contact sheets.</p>
              <p><a href="search_contact_sheet.php" class="btn btn-large btn-primary">Contact Sheets &raquo;</a></p>
            </div>

            <div class="span3">
              <!-- Shop Profiles -->            
              <h2>Shop Profiles</h2>
              <p>Search, add, edit, or delete shop profiles.</p>
              <p><a href="search_shop_profile.php" class="btn btn-large btn-primary">Shop Profiles &raquo;</a></p>
            </div>

            <div class="span3">
              <!-- Actions -->
              <h2>Actions</h2>
              <p>Search, add, edit, or delete actions.</p>
              <p><a href="search_action.php" class="btn btn-large btn-primary">Actions &raquo;</a></p>
            </div>
          </div>
          
          <?php if( $_SESSION[ 'privilege_level' ] > 3 ) { ?>
            <div class="row-fluid">
              <div class="span3">
                <!-- Users -->
                <h2>Users</h2>
                <p>Search, add, edit, or delete users.</p>
                <p><a href="manage_users.php" class="btn btn-large btn-primary">Users &raquo;</a></p>
              </div>
            </div>
          <?php } ?>
        </div><!--Main Menu-->
        
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
    <script src="js/index.js"></script>
  </body>

</html>
