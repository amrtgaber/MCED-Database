<?php
/* File: search_contact_sheet.php
 * Author: Amr Gaber
 * Created: 2013/4/25
 * Description: Handles searching for a contact sheet.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION[ 'username' ] ) {
  header( 'Location: login.php' );
  exit;
} ?>

<!DOCTYPE html>

<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>KC99 - Database Search for Contact Sheet</title>

    <!-- CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="css/search_contact.css" rel="stylesheet">
    <link href="css/search_contact_sheet.css" rel="stylesheet">
      
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
          <legend>Search Contact Sheet</legend>

          <div class="well">
            <div class="row-fluid">
              <form id="search">
                <div class="span3">
                  <input type="text" id="firstName" name="firstName" class="span12 search-query" placeholder="First Name">
                </div>
                
                <div class="span3">
                  <input type="text" id="lastName" name="lastName" class="span12 search-query" placeholder="Last Name">
                </div>
                
                <button type="submit" class="btn btn-info span1" id="search-button"><i class="icon-search"></i></button>
                <button type="button" class="btn span1" id="clear-button">Clear</button>
              </form>
            </div>
          </div>
          
          <div class="accordion" id="search-results"></div>
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
    <script src="js/search_contact_sheet.js"></script>
  </body>

</html>
