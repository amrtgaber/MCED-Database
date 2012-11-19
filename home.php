<?php
/* File: home.php
 * Author: Amr Gaber
 * Created: 9/27/2012
 * Description: Handles home page for KC99 database.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION[ 'username' ] ) {
  header( 'Location: login.php' );
  exit;
}
?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>KC99 - Database Home</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet">
      
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
              <h2>View</h2>
              <p>Select this option if you'd like to generate a phone bank list, look at a shop profile, or view any other information.</p>
            </div>

            <?php if( $_SESSION[ 'privilege_level' ] > 0 ) { ?>
              <div class="span6">
                <h2>Add</h2>
                <p>Select this option if you'd like to add a contact (from a petition for example) or add a shop profile.</p>
              </div>
            <?php } ?>
          </div><!--/.row-fluid-->

          <div class="row-fluid">
            <div class="span6">
              <button class="btn btn-large btn-primary" type="button" id="view">View Database &raquo;</button>
            </div>

            <?php if( $_SESSION[ 'privilege_level' ] > 0 ) { ?>
              <div class="span6">
                <button class="btn btn-large btn-primary" type="button" id="add">Add to Database &raquo;</button>
              </div>
            <?php } ?>
          </div><!--./row-fluid-->
          
          <div class="row-fluid">
            <?php if( $_SESSION[ 'privilege_level' ] > 1 ) { ?>
            <div class="span6">
              <h2>Modify</h2>
              <p>Select this option if you'd like to modify a contact or shop profile.</p>
            </div>
            <?php } ?>

            <?php if( $_SESSION[ 'privilege_level' ] > 2 ) { ?>
              <div class="span6">
                <h2>Remove</h2>
                <p>Select this option if you'd like to remove a contact or shop profile.</p>
              </div>
            <?php } ?>
          </div><!--/.row-fluid-->
          
          <div class="row-fluid">
            <?php if( $_SESSION[ 'privilege_level' ] > 1 ) { ?>
              <div class="span6">
                <button class="btn btn-large btn-primary" type="button" id="modify">Modify Database &raquo;</button>
              </div>
            <?php } ?>

            <?php if( $_SESSION[ 'privilege_level' ] > 2 ) { ?>
              <div class="span6">
                <button class="btn btn-large btn-primary" type="button" id="remove">Remove from Database &raquo;</button>
              </div>
            <?php } ?>
          </div><!--./row-fluid-->
          
          <div class="row-fluid">
            <?php if( $_SESSION[ 'privilege_level' ] > 3 ) { ?>
              <div class="span6">
                <h2>Manage Users</h2>
                <p>Select this option if you'd like to manage user accounts or privileges.</p>
              </div>
            <?php } ?>
          </div><!--/.row-fluid-->
          
          <div class="row-fluid">
            <?php if( $_SESSION[ 'privilege_level' ] > 3 ) { ?>
              <div class="span6">
                <button class="btn btn-large btn-primary" type="button" id="manage-users">Manage Users &raquo;</button>
              </div>
            <?php } ?>
          </div><!--./row-fluid-->
        </div><!--/.span9-->
        
        <!-- Sidebar -->
        <div class="span3">
          <div class="well sidebar-nav">
            <div id="sidebar">
            </div>

            <br>

            <div id="quick-search">
              <input type="text" id="firstName" name="firstName" class="span6" placeholder="First name">
              <input type="text" id="lastName" name="lastName" class="span6" placeholder="Last name">
              <button type="button" id="quick-search-button" class="btn btn-small">Search</button>
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
    <script src="js/home.js"></script>
  </body>

</html>

