<?php
/* File: remove_contact.php
 * Author: Amr Gaber
 * Created: 16/10/2012
 * Description: Handles removing a contact from KC99 database.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION[ 'username' ] ) {
  header( 'Location: login.php' );
  exit;
}

/* Must have privilege level of 3 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 3 ) {
  header( 'Location: home.php' );
  exit;
}
?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>KC99 - Database Remove</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/remove_contact.css" rel="stylesheet">
      
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
        <legend>Remove Contact</legend>
          <form id="search">
            <h4>Which contact would you like to remove?</h4>

            <div class="well"> 
              <div class="row-fluid">
                <div class="span1">First Name</div>
                <div class="span5">
                  <input type="text" name="firstName" class="span12" placeholder="Type first name here">
                </div>
                
                <div class="span1">Last Name</div>
                <div class="span5">
                  <input type="text" name="lastName" class="span12" placeholder="Type last name here">
                </div>
              </div>
            </div>
            
            <div class="row-fluid">
              <div class="span3">
                <button type="submit" class="btn btn-primary btn-large">Search</button>
              </div>
            </div>
          </form>

          <div id="select">
            <h4>Please select from the list of results</h4>

            <div id="selectTable" class="row-fluid">
            </div>

            <div class="row-fluid">
              <button type="button" id="selectButton" class="btn btn-primary btn-large btn-danger">Remove</button>
              <button type="button" id="backToSearch" class="btn btn-large">Back</button>
            </div>
          </div>
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
    <script src="js/common.js"></script>
    <script src="js/remove_contact.js"></script>
  </body>

</html>

