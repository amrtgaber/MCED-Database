<?php
/* File: load_navbar.php
 * Author: Amr Gaber
 * Created: 20/10/2012
 * Description: Loads the navbar.
 */

/* Start a new session or continue an existing one */
session_start();

/* Must be logged in for this to work */
if( !$_SESSION[ 'username' ] ) {
  header( "Location: login.php" );
  exit;
}
?>

<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
      <a class="brand" href="home.php">Database</a>

      <ul class="nav pull-right">
        <li class="navbar-text">
          Logged in as 
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
