<?php
/* File: remove_user.php
 * Author: Amr Gaber
 * Created: 9/27/2012
 * Description: Handles modifying a contact for KC99 database.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION[ 'username' ] ) {
  header( 'Location: login.php' );
  exit;
}

/* Must have privilege level of 4 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 4 ) {
  header( 'Location: home.php' );
  exit;
}
?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KC99 - Database Remove User</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/remove_user.css" rel="stylesheet">
      
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
          <legend>Remove User</legend>
  
          <form>
            <h4>Please select from the list of users</h4>

            <div id="user-list" class="row-fluid">
            </div>

          <div class="row-fluid">
            <div id ="remove-user-form-status" class="span4 alert alert-error hide">
            </div>
          </div>

            <div class="row-fluid">
              <button type="submit" class="btn btn-primary btn-large btn-danger">Remove</button>
            </div>
          </form>
        </div><!--/.span9-->
          
        <div id="modal" class="modal hide fade">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Are you sure?</h3>
          </div>
            
          <div class="modal-body">
          </div>
          
          <div class="modal-footer">
            <button type="submit" id="removeConfirm" class="btn btn-primary btn-danger">Remove</button>
            <button type="button" class="btn" data-dismiss="modal">Cancel</button>
          </div>
        </div>
        
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
    <script src="js/remove_user.js"></script>
  </body>

</html>

