<?php
/* File: add_user.php
 * Author: Amr Gaber
 * Created: 10/10/2012
 * Description: Handles adding a user to KC99 database.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION[ 'username' ] ) {
  header( 'Location: login.php' );
  exit;
}

/* Must have privilege level of 4 or greater to access this page */
if( $_SESSION[ 'privilege_level' ] < 4 ) {
  header( 'Location: index.php' );
  exit;
}
?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>KC99 - Database Add User</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/jui-start-theme/jquery-ui-1.9.0.custom.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/add_user.css" rel="stylesheet">
      
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
            <div class="span12">
              <h2>Add User</h2>
            </div>
          </div><!--/.row-fluid-->

          <form class="form-horizontal">
            <div class="row-fluid">
              <div class="span6 well">
                <div class="control-group">
                  <label class="control-label">Username</label>
                  <div class="controls">
                    <input type="text" name="username" placeholder="Type username here">
                  </div>
                </div>
                
                <div class="control-group">
                  <label class="control-label">Password</label>
                  <div class="controls">
                    <input type="password" name="password" id="password" placeholder="Type password here">
                  </div>
                </div>
                
                <div class="control-group">
                  <label class="control-label">Confirm Password</label>
                  <div class="controls">
                    <input type="password" name="confirmPassword" placeholder="Retype password here">
                  </div>
                </div>

                <div id="add-user-form-status" class="alert hide"></div>
              </div>

              <div class="span5 well">
                <h4 id="privilege-header">Privileges Granted</h4>

                <div class="row-fluid">
                  <span>Least privileges</span>
                  <span class="pull-right">Most privileges</span>
                  
                  <div id="slider"></div>
                </div>
                  
                <div class="row-fluid">
                  <div>
                  <br />
                    <ul id="privilege-description" class="unstyled">
                      <li><span class="span4"></span><i class="icon-plus"></i>View Database</li>
                      <li><i class="icon-plus"></i>Add to Database</li>
                      <li><span class="span4"></span><i class="icon-plus"></i>Modify Database</li>
                      <li><i class="icon-plus"></i>Remove from Database</li>
                      <li><span class="span4"></span><i class="icon-plus"></i>Manage Users</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
         
            <div class="row-fluid">
              <div class="span5">
                <button type="submit" class="btn btn-primary btn-large">Add User</button>
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
    <script src="js/add_user.js"></script>
  </body>

</html>

