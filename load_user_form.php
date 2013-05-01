<?php
/* File: load_user_form.php
 * Author: Amr Gaber
 * Created: 2013/4/30
 * Description: Handles adding a user to KC99 database.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION[ 'username' ] ) {
  header( 'Location: login.php' );
  exit;
}

include( "db_credentials.php" );
include( "common.php" );

/* Connect to database */
$mc = connect_to_database();

if( $_GET[ 'add' ] ) {
  $uinfo = Array();
} else {
  $uid = mysql_real_escape_string( $_GET[ 'uid' ] );

  /* Get contact information */
  $qs = "SELECT username.*,
         FROM users
         WHERE users.id = " . $uid;
  
  $qr = execute_query( $qs, $mc );

  $uinfo = mysql_fetch_array( $qr );
} ?>
<div class="well">
  <div class="row-fluid">
    <div class="span1">Username</div>
    <div class="span5">
      <input type="text" name="username" class="span12"
             value="<?php echo( $uinfo[ 'username' ] ); ?>"
             placeholder="Type username here">
    </div>
  </div>
</div>

<form id="change-password-form" class="well">
  <h5>Change Password</h5>
  <div class="row-fluid">
    <div class="span4">
      <input type="password" id="newPassword" name="newPassword" class="span12" placeholder="Type new password here">
    </div>

    <div class="span4">
      <input type="password" id="confirmPassword" name="confirmPassword" class="span12" placeholder="Re-type new password here">
    </div>
    
    <button type="button" class="btn btn-info span1 mobile-search" id="change-password-submit-button"><i class="icon-refresh"></i></button>
    <button type="button" class="btn span1 mobile-search" id="change-pasword-clear-button">Clear</button>
  </div>
  
  <div class="row-fluid">
  <div id="change-password-form-status" class="hide"></div>
  </div>
</form>

<div class="well">
  <div class="row-fluid">
    <h4>Privileges</h4>
    <hr>
    Coming Soon...
  </div>
</div>

<div class="row-fluid">
  <div id="user-form-status" class="alert alert-error hide">
  </div>
</div>

<div class="row-fluid">
  <button type="submit" id="save-button" class="btn btn-primary btn-large">Save Changes</button>
  <button type="button" id="cancel-button" class="btn btn-inverse btn-large">Cancel</button>
  <button type="button" id="delete-button" class="btn btn-danger btn-large pull-right" data-toggle="modal" data-target="#delete-modal">Delete</button>
</div>

<div id="delete-modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Are you sure?</h3>
  </div>
    
  <div class="modal-body">
    This action cannot be undone. Clicking delete will permanently remove this contact from the database.
  </div>

  <div class="modal-footer">
    <button type="button" id="delete-confirm-button" class="btn btn-primary btn-danger">Delete</button>
    <button type="button" class="btn btn-inverse" data-dismiss="modal">Cancel</button>
  </div>
</div>

              

