<?php
/* File: search_contact.php
 * Author: Amr Gaber
 * Created: 18/11/2012
 * Description: Handles searching for a contact for KC99 database.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION[ 'username' ] ) {
  header( 'Location: login.php' );
  exit;
}

if( isset( $_GET[ 'firstName' ] ) || isset( $_GET[ 'lastName' ] ) ) { ?>
  <script type="text/javascript">var quickSearch = true;</script>
<?php } else { ?>
  <script type="text/javascript">var quickSearch = false;</script>
<?php }

?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>KC99 - Database Search for Contact</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/search_contact.css" rel="stylesheet">
      
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
        <legend>Search Contact</legend>

          <form id="search">
            <h4>Which contact would you like to search?</h4>

            <div class="well"> 
              <div class="row-fluid">
                <div class="span1">First Name</div>
                <div class="span5">
                  <input type="text" name="firstName" class="span12" placeholder="Type first name here" value="<?php echo( $_GET[ 'firstName' ] ); ?>">
                </div>
                
                <div class="span1">Last Name</div>
                <div class="span5">
                  <input type="text" name="lastName" class="span12" placeholder="Type last name here" value="<?php echo( $_GET[ 'lastName' ] ); ?>">
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
            <div id="contactInfo">
            </div>

            <div class="row-fluid">
              <div class="span5">
                <button type="submit" id="updateButton" class="btn btn-primary btn-large">Modify this contact &raquo;</button>
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
    <script src="js/search_contact.js"></script>
  </body>

</html>

