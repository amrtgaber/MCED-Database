<?php
/* File: view.php
 * Author: Amr Gaber
 * Created: 9/29/2012
 * Description: Handles view page for KC99 database.
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
    <title>KC99 - Database View</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/view.css" rel="stylesheet">
      
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
              <h2>Generate List</h2>
              <p>Select this option if you'd like to generate a list of contacts, a phone bank, or any other kind of list.</p>
            </div>
            <div class="span6">
              <h2>View Shop Profile</h2>
              <p>Select this option if you'd like to take a look at a shop profile.</p>
            </div>
          </div><!--/.row-fluid-->

          <div class="row-fluid">
            <div class="span6">
              <button class="btn btn-large btn-primary" type="button" id="generate-list">Generate List &raquo;</button>
            </div>
            <div class="span6">
              <button class="btn btn-large btn-primary" type="button" id="view-shop-profile">View Shop Profile &raquo;</button>
            </div>
          </div><!--./row-fluid-->
        </div><!--/.span9-->
        
        <!-- Sidebar -->
        <div class="span3">
          <div class="well sidebar-nav">
            <div id="sidebar">
            </div>

            <ul class="nav nav-list">
              <li class="nav-header">Database Statistics</li>
            </ul>

            <?php
              $mc = mysql_connect( "localhost", "root", "mceddb" ) or die( mysql_error() );
              mysql_select_db( "kc99_data" );
              
              $qs = "SELECT *
                     FROM contacts
                     WHERE contact_type='worker'";
              $qr = mysql_query( $qs, $mc );
              $numWorkers = mysql_num_rows( $qr );
              
              $qs = "SELECT *
                     FROM contacts
                     WHERE contact_type='student'";
              $qr = mysql_query( $qs, $mc );
              $numStudents = mysql_num_rows( $qr );
              
              $qs = "SELECT *
                     FROM contacts
                     WHERE contact_type='supporter'";
              $qr = mysql_query( $qs, $mc );
              $numSupporters = mysql_num_rows( $qr );
              
              $qs = "SELECT *
                     FROM contacts
                     WHERE contact_type='organizer'";
              $qr = mysql_query( $qs, $mc );
              $numOrganizers = mysql_num_rows( $qr );
              
              $qs = "SELECT *
                     FROM contacts
                     WHERE contact_type='other'";
              $qr = mysql_query( $qs, $mc );
              $numOther = mysql_num_rows( $qr );
            ?>

             <table class="table table-condensed">
              <thead>
                <tr>
                  <th>Contact Type</th>
                  <th>Amount</th>
                </tr>
              </thead>

              <tbody>
                <tr>
                  <td>Workers</td>
                  <td><?php echo( $numWorkers ); ?></td>
                </tr>
                
                <tr>
                  <td>Students</td>
                  <td><?php echo( $numStudents ); ?></td>
                </tr>
                
                <tr>
                  <td>Supporters</td>
                  <td><?php echo( $numSupporters ); ?></td>
                </tr>
                
                <tr>
                  <td>Organizers</td>
                  <td><?php echo( $numOrganizers ); ?></td>
                </tr>
                
                <tr>
                  <td>Other</td>
                  <td><?php echo( $numOther ); ?></td>
                </tr>
                
                <tr>
                  <td><em>Total</em></td>
                  <td><em><?php echo( $numWorkers + $numStudents + $numSupporters + $numOrganizers + $numOther ); ?></em></td>
                </tr>
              </tbody>
            </table>
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
    <script src="js/view.js"></script>
  </body>

</html>

