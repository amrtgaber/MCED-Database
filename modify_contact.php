<?php
/* File: modify_contact.php
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

/* Must have privilege level of 2 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 2 ) {
  header( 'Location: home.php' );
  exit;
}
?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>KC99 - Database Modify</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/modify_contact.css" rel="stylesheet">
      
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
        <legend>Modify Contact</legend>

          <form id="search">
            <h4>Which contact would you like to modify?</h4>

            <div class="well"> 
              <div class="row-fluid">
                <div class="span1">First Name</div>
                <div class="span5">
                  <input type="text" name="firstName" class="span12" placeholder="Type first name here" required>
                </div>
                
                <div class="span1">Last Name</div>
                <div class="span5">
                  <input type="text" name="lastName" class="span12" placeholder="Type last name here" required>
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
              <button type="button" id="selectButton" class="btn btn-primary btn-large">Select</button>
              <button type="button" id="backToSearch" class="btn btn-large">Back</button>
            </div>
          </div>

          <form id="update">
            <div id="formFields">
            </div>
            
            <div class="row-fluid">
              <div class="span3">
                <button type="submit" id="updateButton" class="btn btn-primary btn-large">Save Changes</button>
                <button type="button" id="backToSelect" class="btn btn-large">Back</button>
              </div>
            </div>
          </form>
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
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/modify_contact.js"></script>
  </body>

</html>

