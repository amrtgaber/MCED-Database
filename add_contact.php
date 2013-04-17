<?php
/* File: add_contact.php
 * Author: Amr Gaber
 * Created: 9/27/2012
 * Description: Handles add contact page for KC99 database.
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
    <link href="css/jui-start-theme/jquery-ui-1.9.0.custom.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/add_contact.css" rel="stylesheet">
      
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
          <form>
            <legend>Add Contact</legend>
            
            <div id="form-fields">
            </div>

            <div class="row-fluid">
              <div id="add-contact-form-status" class="alert alert-error hide">
              </div>
            </div>
            
            <div class="row-fluid">
              <div class="span3">
                <button type="submit" class="btn btn-primary btn-large">Add Contact</button>
                <button type="reset" class="btn btn-large">Clear</button>
              </div>
            </div>
          </form>
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
    <script src="js/jquery-ui-1.9.0.custom.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/add_contact.js"></script>
  </body>

</html>

