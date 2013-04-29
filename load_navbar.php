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
      <span class="dropdown">
        <a class="brand dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-align-justify icon-white"></i></a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="index.php">Main Menu</a></li>
          <li><a href="search_contact.php">Contacts</a></li>
          <li><a href="search_contact_sheet.php">Contact Sheets</a></li>
          <li><a href="search_shop_profile.php">Shop Profiles</a></li>
          <li><a href="search_action.php">Actions</a></li>
        </ul>
      </span>

      <ul class="nav pull-right">
        <li class="navbar-text">
          <span class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo( $_SESSION[ 'username' ] ); ?></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#" id="change-password-menu">change password</a></li>
            </ul>
          </span>
        </li>
        <li class="divider-vertical"></li>
        <li style="padding-right: 45px;"><button type="button" id="logout" class="btn btn-small btn-inverse">Logout</button></li>
      </ul>
    </div>
  </div>
</div>
        
<div id="change-password-modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Change Password</h3>
  </div>
    
  <form id="change-password-form" class="form-horizontal">
    <div class="modal-body">
        <div class="control-group">
          <label class="control-label" for="newPassword">New password</label>
          <div class="controls">
            <input type="password" id="newPassword" name="newPassword" placeholder="Type new password here">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="confirmNewPassword">Confirm new password</label>
          <div class="controls">
            <input type="password" id="confirmNewPassword" name="confirmNewPassword" placeholder="Retype new password here">
          </div>
    </div>
    
    <div class="modal-footer">
      <span id="change-password-form-status" class="alert hide pull-left">
      </span>
      <button type="submit" id="change-password-button" class="btn btn-primary">Submit</button>
      <button type="button" class="btn" data-dismiss="modal">Cancel</button>
    </div>
  </form>
</div>
