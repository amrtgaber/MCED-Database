<?php
/* File: view_user.php
 * Author: Amr Gaber
 * Created: 2013/5/1
 * Description: Shows user for KC99 database.
 */

include( "db_credentials.php" );
include( "common.php" );

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
} ?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KC99 - View User</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/jui-start-theme/jquery-ui-1.9.0.custom.css" rel="stylesheet">
    <link href="css/view_user.css" rel="stylesheet">
      
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
          <legend>View User</legend>
          <form id="user-form"></form>
        </div>
        
        <!-- Sidebar -->
        <div id="sidebar"></div>
      </div>

      <!-- Footer -->
      <div id="footer"></div>
    </div>
    
    <!-- JavaScript -->
    <script type="text/javascript">
      var uid = <?php echo( mysql_real_escape_string( $_GET[ 'uid' ] ) ); ?>;
      var add = "";
    </script>
		<script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery-ui-1.9.0.custom.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/load_user_form.js"></script>
    <script src="js/view_user.js"></script>
  </body>

</html>
