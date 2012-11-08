<?php
/* File: change_password_action.php
 * Author: Amr Gaber
 * Created: 7/11/2012
 * Description: Allows user to change password.
 */

/* Must be logged in to access this page */
session_start();

if( !$_SESSION[ 'username' ] ) {
  header( 'Location: login.php' );
  exit;
}

/* Parse and sanitize $_POST[] input */
if( !isset( $_POST[ 'newPassword' ] ) || $_POST[ 'newPassword' ] == "" || !isset( $_POST[ 'confirmNewPassword' ] ) || $_POST[ 'confirmNewPassword' ] == "" ) { ?>
  <div class="alert alert-error">
    New Password and Confirm Password are required fields.
  </div> <?php
  exit;
} else if( strcmp( $_POST[ 'newPassword' ], $_POST[ 'confirmNewPassword' ] ) != 0 ) { ?>
  <div class="alert alert-error">
    Passwords must match.
  </div> <?php
  exit;
} else {
  $new_password = mysql_real_escape_string( $_POST[ 'newPassword' ] );
  $new_password = hash( "sha256", $new_password );
}

/* Connect to database */
$mc = mysql_connect( "localhost", "root", "mceddb" ) or die( mysql_error() );
mysql_select_db( "kc99_data" );

$qs = "UPDATE users
       SET password = '" . $new_password . "'
       WHERE username = '" . $_SESSION[ 'username' ] . "'";

$qr = mysql_query( $qs, $mc );

if( !$qr ) {
  mysql_error();
  exit;
}

?>

<div class="alert alert-success">
  Password change successful.
  <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
</div>
