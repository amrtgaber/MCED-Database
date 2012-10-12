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
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="home.php">Database</a>
          <div class="nav-collapse collapse">
            <ul class="nav pull-right">
              <li><p class="navbar-text pull-right">
                Logged in as <?php echo( $_SESSION[ 'username' ] ); ?>
              </p></li>
              <li class="divider-vertical"></li>
              <li><button type="button" id="logout" class="btn btn-small btn-inverse pull-right">Logout</button></li>
            </ul>
            <ul class="nav">
              <li><a href="view.php">View</a></li>
 
              <?php if( $_SESSION[ 'privilege_level' ] > 0 ) { ?>
                <li><a href="add.php">Add</a></li>
              <?php } ?>
              
              <?php if( $_SESSION[ 'privilege_level' ] > 1 ) { ?>
                <li><a href="modify.php">Modify</a></li>
              <?php } ?>

              <?php if( $_SESSION[ 'privilege_level' ] > 2 ) { ?>
                <li><a href="remove.php">Remove</a></li>
              <?php } ?>

              <?php if( $_SESSION[ 'privilege_level' ] > 3 ) { ?>
                <li><a href="manage_users.php">Manage Users</a></li>
              <?php } ?>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
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
            <img src="img/kc99-logo-9-27.png">
          </div><!--/.well -->
        </div><!--/span-->
      </div><!--/.row-fluid-->

      <!-- Footer -->
      <hr>
      <footer class="footer">
        <p>Copyright &copy;&nbsp;2012 <a href="http://www.kansascity99.org/">KC99</a></p>
      </footer>
    </div><!--/.fluid-container-->
    
    <!-- JavaScript -->
		<script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/common.js"></script>
    <script src="js/home.js"></script>
  </body>

</html>

