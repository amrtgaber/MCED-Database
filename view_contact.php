<?php
/* File: view_contact.php
 * Author: Amr Gaber
 * Created: 2013/4/27
 * Description: Shows contact for KC99 database.
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
    <title>KC99 - View Contact</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/jui-start-theme/jquery-ui-1.9.0.custom.css" rel="stylesheet">
    <link href="css/view_contact.css" rel="stylesheet">
      
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
          <legend>View Contact</legend>
          <form id="contact-form"></form>
        </div>
        
        <!-- Sidebar -->
        <div class="span3">
          <div id="sidebar"></div>
          <ul class="well">
            <li class="nav-header">Contact Sheets</li>
            <?php $mc = connect_to_database();
              
              $csqs = "SELECT contact_sheet.id,
                              contact_sheet.cs_date
                       FROM contact_sheet
                       WHERE contact_sheet.cid = " . $_GET[ 'id' ] . "
                       ORDER BY contact_sheet.cs_date DESC";

              $csqr = execute_query( $csqs, $mc );
              
              while( $csinfo = mysql_fetch_array( $csqr ) ) { ?>
                <li><a href="view_contact_sheet.php?csid=<?php echo( $csinfo[ 'id' ] ); ?>"><?php echo( $csinfo[ 'cs_date' ] ); ?></a></li>
              <?php } ?>
              <li><a href="add_contact_sheet.php?csid=<?php echo( $_GET[ 'id' ] ); ?>" class="btn btn-info">+</a></li>
          </ul>
        </div>
      </div>

      <!-- Footer -->
      <div id="footer"></div>
    </div>
    
    <!-- JavaScript -->
    <script type="text/javascript">
      var id = <?php echo( mysql_real_escape_string( $_GET[ 'id' ] ) ); ?>;
      var add = "";
    </script>
		<script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery-ui-1.9.0.custom.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/load_contact_form.js"></script>
    <script src="js/view_contact.js"></script>
  </body>

</html>
