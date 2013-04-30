<?php
/* File: load_navbar.php
 * Author: Amr Gaber
 * Created: 2012/10/20
 * Description: Loads the navbar.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
} ?>

<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      
      <a class="brand" href="index.php">Database</a>
      
      <div class="nav-collapse collapse">
        <ul class="nav">
          <li><a href="search_contact.php">Contacts</a></li>
          <li><a href="search_contact_sheet.php">Contact Sheets</a></li>
          <li><a href="search_shop_profile.php">Shop Profiles</a></li>
          <li><a href="search_action.php">Actions</a></li>
        </ul>
        
        <ul class="nav pull-right">
          <li class="navbar-text"><?php echo( $_SESSION[ 'username' ] ); ?></li>
          <li class="divider-vertical"></li>
          <li><button type="button" id="logout-button" class="btn btn-small btn-inverse">Logout</button></li>
        </ul>
      </div>
    </div>
  </div>
</div>
