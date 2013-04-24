<?php
/* File: modify_shop_profile.php
 * Author: Amr Gaber
 * Created: 2013/1/31
 * Description: Handles modifying a shop profile for KC99 database.
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

/*Get id passed from view if it's available*/
if( isset( $_GET[ 'id' ] ) ) { ?>
  <script type="text/javascript">var quickSelect = true; var quickId = <?php echo( $_GET[ 'id' ] ); ?>;</script>
<?php } else { ?>
  <script type="text/javascript">var quickSelect = false;</script>
<?php }

?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>KC99 - Database Modify Shop Profile</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/modify_shop_profile.css" rel="stylesheet">
      
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
        <legend>Modify Shop Profile</legend>

          <form id="search">
            <h4>Which shop would you like to modify?</h4>

            <div class="well"> 
              <div class="row-fluid">
                <div class="span2">Workplace Name</div>
                <div class="span10">
                  <input type="text" name="wname" class="span12" placeholder="Type workplace name here">
                </div>
              </div>
            </div>
            
            <div class="row-fluid">
              <div class="span3">
                <button type="submit" class="btn btn-primary btn-large">Search</button>
              </div>
            </div>
          </form>

          <div id="select" class="hide">
            <h4>Please select from the list of results</h4>

            <div id="selectTable" class="row-fluid">
            </div>

            <div class="row-fluid">
              <button type="button" id="backToSearch" class="btn btn-large">Back</button>
            </div>
          </div>

          <form id="update" class="hide">
            <div id="formFields">
            </div>

            <div class="row-fluid">
              <div id="edit-shop-profile-form-status" class="alert alert-error hide">
              </div>
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
    <script src="js/modify_shop_profile.js"></script>
  </body>

</html>

