<?php
/* File: add_contact_sheet.php
 * Author: Amr Gaber
 * Created: 2013/1/8
 * Description: Add contact sheet page.
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
    <title>KC99 - Database Add Contact Sheet</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/jui-start-theme/jquery-ui-1.9.0.custom.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/add_contact_sheet.css" rel="stylesheet">
      
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
          <legend>Add Contact Sheet</legend>
          
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
              <div class="span12">
                <button type="button" id="search-button" class="btn btn-primary btn-large">Search</button>
                <button type="button" class="btn btn-warning btn-large pull-right blank-contact-sheet-button">Use blank contact sheet</button>
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
              <button type="button" class="btn btn-warning btn-large pull-right blank-contact-sheet-button">Use blank contact sheet</button>
            </div>
          </div>
          
          <div id="view" class="hide">
            <form id="add-contact-sheet-form">
              
              <div id="form-fields">
              </div>

              <div class="row-fluid">
                <div id="add-contact-sheet-form-status" class="alert alert-error hide">
                </div>
              </div>
              
              <div class="row-fluid">
                <div class="span4">
                  <button type="submit" id="add-contact-sheet-button" class="btn btn-primary btn-large">Add Contact Sheet</button>
                  <button type="reset" class="btn btn-large">Clear</button>
                </div>
              </div>
            </form>
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
    <script src="js/jquery-ui-1.9.0.custom.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/add_contact_sheet.js"></script>
  </body>

</html>

