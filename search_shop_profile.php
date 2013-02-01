<?php
/* File: search_contact.php
 * Author: Bryan Dorsey
 * Created: 1/31/2013
 * Description: Handles searching for a shop profile in the KC99 database.
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
    <title>KC99 - Database Search for Shop</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/search_contact.css" rel="stylesheet">
    <link href="css/load_contact_profile.css" rel="stylesheet">
      
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
        <legend>Search Shop Profile</legend>

          <form id="search">
            <h4>Which shop would you like to search?</h4>

            <div class="well"> 
              <div class="row-fluid">
                <div class="span2">Workplace Name</div>
                <div class="span10">
                  <input type="text" name="wname" class="span12" placeholder="Type workplace name here" value="<?php echo( $_GET[ 'wname' ] ); ?>">
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
              <button type="button" id="selectButton" class="btn btn-primary btn-large">Select</button>
              <button type="button" id="backToSearch" class="btn btn-large">Back</button>
            </div>
          </div>

          <div id="view" class="hide">
            <div id="shopInfo">
            </div>

            <div class="row-fluid">
              <div class="span5">
                <button type="submit" id="updateButton" class="btn btn-primary btn-large">Modify this shop &raquo;</button>
                <button type="button" id="backToSelect" class="btn btn-large">Back</button>
              </div>
            </div>
          </div>
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
    <script src="js/search_shop_profile.js"></script>
  </body>

</html>

